<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Internacion;

use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Livewire\AtencionMedica\OtrasConsultas\RegistroSoap;
use App\Models\ConsultaExterna\Internacion;
use App\Models\ConsultaExterna\HCPlanes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class RegistrarInternacion extends Component
{
    public $datoHistoriaClinica;
    public $plan = 6; // 6= Internacion
    public $nro_internacion, $fecha_internacion, $indicaciones, $estado;
    public $tipo_consulta = null;

    protected $rules = [
        'fecha_internacion' => 'required',
        'indicaciones' => 'required',
    ];


    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.internacion.registrar-internacion');
    }
    public function mount()
    {
        $this->tipo_consulta = request()->query('tipo_at');
    }

    #[On('recibirHCInternacion')]
    public function recibirHCInternacion($data)
    {
        $this->datoHistoriaClinica = $data;
    }

    #[On('guardarInternacion')]
    public function guardarInternacion($indicacion)
    {
        $this->indicaciones = $indicacion;
        $this->validate();
        try {
            DB::beginTransaction();
            $inicioAnio = Carbon::now()->startOfYear();
            $finAnio    = Carbon::now()->endOfYear();
            //Consulta entre esas dos fechas
            $ultimo = Internacion::whereBetween('created_at', [$inicioAnio, $finAnio])
                ->orderBy('nro_internacion', 'desc')
                ->lockForUpdate()
                ->value('nro_internacion');

            $siguiente = $ultimo ? $ultimo + 1 : 1;
            $datosInternacion = [
                'nro_internacion' => $siguiente,
                'fecha_internacion' => $this->fecha_internacion,
                'indicaciones' => $this->indicaciones,
                'estado' => true,
                'id_persona' => $this->datoHistoriaClinica['id_persona'],
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];

            $internacionCreada = Internacion::create($datosInternacion);

            $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], $this->plan, $internacionCreada->id);
            DB::commit();
            // Limpiar o cerrar modal
            $this->reset('fecha_internacion', 'indicaciones');
            $this->dispatch('internacionGuardada');
            // para que se descargue el pdf internacion
            $this->js('window.open("/consulta_externa/pdf/internacion/' . $internacionCreada->id . '", "_blank")');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al guardar imagenología', [
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
            if ($HCplanCreado) {
                if ($this->tipo_consulta != 1) {
                    $this->dispatch('actualizarPlanes')->to(RegistroSoap::class);
                } else {
                    $this->dispatch('actualizarPlanes')->to(ConsultaExternaRegister::class);
                }
            } else {
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
