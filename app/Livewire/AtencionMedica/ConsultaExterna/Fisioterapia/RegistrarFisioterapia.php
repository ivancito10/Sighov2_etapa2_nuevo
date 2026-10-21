<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Fisioterapia;

use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Livewire\AtencionMedica\OtrasConsultas\RegistroSoap;
use App\Models\ConsultaExterna\Fisioterapia;
use Illuminate\Support\Facades\DB;
use App\Models\ConsultaExterna\HCPlanes;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistrarFisioterapia extends Component
{   
    public $plan = 5; // 5 = Fisioterapia
    public $nro_sesiones,$indicaciones;
    public $datoHistoriaClinica =null;
    public $tipo_consulta = null;
    protected $rules = [
        'indicaciones' => 'required',
        'nro_sesiones' => 'required|min:1',
    ];

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.fisioterapia.registrar-fisioterapia');
    }

    public function mount(){
        $this->tipo_consulta = request()->query('tipo_at');
    }

    #[On('recibirHCFisioterapia')]
    public function recibirHCFisioterapia($data){
        $this->datoHistoriaClinica = $data;
    }

    #[On('guardarFisioterapia')]
    public function guardarFisioterapia(){
        $this->validate();
        try{
            DB::beginTransaction();

            $ultimo = Fisioterapia::orderBy('nro_solicitud', 'desc')
                ->lockForUpdate()
                ->value('nro_solicitud');

            $siguiente = $ultimo ? $ultimo + 1 : 1;

            $datos = [
                'nro_solicitud' => 1, //REVISAR NRO_CORRELATIVO
                'nro_sesion' => $this->nro_sesiones,
                'indicaciones' => $this->indicaciones,
                'id_persona' => $this->datoHistoriaClinica['id_persona'],
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];
            $fisioterapiaCreada = Fisioterapia::create($datos);
            if($fisioterapiaCreada){
                $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], $this->plan,$fisioterapiaCreada->id);
                DB::commit();
                //dd('todo positron xd');
                $this->dispatch('fisioterapiaGuardada');
                $this->reset('nro_sesiones', 'indicaciones');
                $this->js('window.open("/consulta_externa/pdf/fisioterapia/' . $fisioterapiaCreada->id . '", "_blank")');
            }else{
                DB::rollBack();
                $this->dispatch('error');
            }
        }catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            Log::error('Error al guardar Fisioterapia', [
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
