<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Interconsulta;

use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Livewire\AtencionMedica\OtrasConsultas\RegistroSoap;
use App\Models\Administracion\LinkServicio;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\Interconsulta;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistrarInterconsulta extends Component
{   
    public $datoHistoriaClinica, $plan = 8; // 8 = receta
    public $especialidades = [];
    public $indicaciones, $id_especialidad, $fecha_fin_tratamiento,
    $dias_tratamiento, $nro_interconsulta, $estado;
    public $tipo_consulta = null;

    protected $rules = [
        'id_especialidad' => 'required',
        'indicaciones' => 'required',
    ];

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.interconsulta.registrar-interconsulta');
    }

    public function mount()
    {   //petición HTTP
        $this->tipo_consulta = request()->query('tipo_at');
        $token = session('access_token');
        $link_servicio = LinkServicio::where('nombre_servicio', 'LISTAR ESPECIALIDADES')->first();
        $url = $link_servicio->link_servicio;
        $response = Http::withToken($token)->get($url);
        //dd($response, $response->json());
        if ($response->successful()) {
            $this->especialidades = $response->json('data');
            //dd($this->especialidades);
        }
    }

    #[On('recibirHCInterconsulta')]
    public function recibirHistoriaClinica($data){
        $this->datoHistoriaClinica = $data;
    }

    #[On('guardarInterconsulta')]
    public function guardarInterconsulta(){
        $this->validate();
        try{
            DB::beginTransaction();

            $inicioAnio = Carbon::now()->startOfYear();
            $finAnio    = Carbon::now()->endOfYear();
            // 2) Consulta entre esas dos fechas
            $ultimo = Interconsulta::whereBetween('created_at', [$inicioAnio, $finAnio])
                    ->orderBy('nro_interconsulta', 'desc')
                    ->lockForUpdate()
                    ->value('nro_interconsulta');

            $siguiente = $ultimo ? $ultimo + 1 : 1;
            //dd($this->datoHistoriaClinica);
            $datos = [
                'indicaciones' =>$this->indicaciones ,
                'id_especialidad' => (int) $this->id_especialidad['value'] ?? null,
                'nro_interconsulta' => $siguiente,
                'estado' => true,
                'id_persona' => $this->datoHistoriaClinica['id_persona'],
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];
            $datoCreado = Interconsulta::create($datos);
            if($datoCreado){
                $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], $this->plan,$datoCreado->id);
                DB::commit(); 
                $this->dispatch('interconsultaGuardada');
                $this->reset(['indicaciones']);
                // para que se descargue el pdf interconsulta   
                $this->js('window.open("/consulta_externa/pdf/interconsulta/' . $datoCreado->id . '", "_blank")');
            }else{
                $this->dispatch('error');
            }
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            Log::error('Error al registrar interconsulta', [
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
