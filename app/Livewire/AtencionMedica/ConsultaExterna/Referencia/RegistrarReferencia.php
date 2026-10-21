<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Referencia;

use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Livewire\AtencionMedica\OtrasConsultas\RegistroSoap;
use App\Models\Administracion\LinkServicio;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\Referencia;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistrarReferencia extends Component
{   
    public $datoHistoriaClinica, $plan = 7; // 1= receta
    public $especialidades = [];
    public $indicaciones_ref, $id_especialidad, $fecha_fin_tratamiento,
    $dias_tratamiento, $nro_referencia, $estado;
    public $tipo_consulta = null;

    protected $rules = [
        'id_especialidad' => 'required',
        'indicaciones_ref' => 'required',
    ];

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.referencia.registrar-referencia');
    }

    public function mount()
    {   //petición HTTP
        $this->tipo_consulta = request()->query('tipo_at');
        $token = session('access_token');
        $link_servicio = LinkServicio::where('nombre_servicio', 'LISTAR ESPECIALIDADES EXTERNAS')->first();
        $url = $link_servicio->link_servicio;
        $response = Http::withToken($token)->get($url);
        //dd($response, $response->json());
        if ($response->successful()) {
            $this->especialidades = $response->json('data');
            //dd($this->especialidades);
        }
    }

    #[On('recibirHCReferencia')]
    public function recibirHistoriaClinica($data){
        $this->datoHistoriaClinica = $data;
    }

    #[On('guardarReferencia')]
    public function guardarReferencia(){
        $this->validate();
        try{
            DB::beginTransaction();

            $inicioAnio = Carbon::now()->startOfYear();
            $finAnio    = Carbon::now()->endOfYear();
            // 2) Consulta entre esas dos fechas
            $ultimo = Referencia::whereBetween('created_at', [$inicioAnio, $finAnio])
                    ->orderBy('nro_referencia', 'desc')
                    ->lockForUpdate()
                    ->value('nro_referencia');

            $siguiente = $ultimo ? $ultimo + 1 : 1;
            $datos = [
                'indicaciones' =>$this->indicaciones_ref ,
                'id_especialidad' => (int) $this->id_especialidad['value'] ?? null,
                'nro_referencia' => $siguiente,
                'estado' => true,
                'id_persona' => $this->datoHistoriaClinica['id_persona'],
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];
            $datoCreado = Referencia::create($datos);
            if($datoCreado){
                $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], $this->plan,$datoCreado->id);
                DB::commit(); 
                $this->dispatch('referenciaGuardada');
                $this->reset(['indicaciones_ref']);
                // para que se descargue el pdf referencia   
                $this->js('window.open("/consulta_externa/pdf/referencia/' . $datoCreado->id . '", "_blank")');
                // $this-> 
            }else{
                $this->dispatch('error');
            }
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            Log::error('Error al registrar referencia', [
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);
            $this->dispatch('error');
        }
    }

    public function registrarTablaHCPlanes($id_hc, $id_plan, $id_corresponde)
    {
        try {
            $attributes = [
                'id_historia_clinica' => $id_hc,
                'id_plan'             => $id_plan,
                'id_corresponde'      => $id_corresponde,
            ];

            $values = [
                'id_user_created' => auth()->id(),
                'created_at'      => now(),
            ];

            // Busca o crea; si existe, no hace nada, si no existe crea
            $HCplanCreado = HCPlanes::firstOrCreate($attributes, $values);
            if($HCplanCreado){
                if($this->tipo_consulta!=1){
                    $this->dispatch('actualizarPlanes')->to(RegistroSoap::class);
                }else{
                    $this->dispatch('actualizarPlanes')->to(ConsultaExternaRegister::class);
                }
            }else{
                $this->dispatch('error');
            }

        } catch (\Throwable $e) {
            Log::error('Error al registrar en HC_planes', [
                'error'   => $e->getMessage(),
                'linea'   => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);
            $this->dispatch('error');
        }
    }

}
