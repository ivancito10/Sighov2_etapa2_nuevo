<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Imagenologia;

use App\Livewire\AtencionMedica\ConsultaExterna\HistoriaClinica\ConsultaExternaRegister;
use App\Livewire\AtencionMedica\OtrasConsultas\RegistroSoap;
use App\Models\ConsultaExterna\Estudio;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\Imagenologia;
use App\Models\ConsultaExterna\TipoImagenologia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class RegistrarImagenologia extends Component
{
    public $plan = 3;

    public $tipos_imagenologia;

    public $estudios = [];

    public $nombreImagenologia;

    public $tipo_img;

    public $estudios_seleccionados = [];

    public $datoHistoriaClinica = null;

    public $indicaciones = '';

    public $tipo_consulta = null;

    public function render()
    {
        return view('livewire.atencion-medica.consulta-externa.imagenologia.registrar-imagenologia');
    }

    public function mount()
    {
        $this->tipo_consulta = request()->query('tipo_at');
    }

    protected $rules = [

        'estudios_seleccionados' => 'required',
    ];

    #[On('modal_imagenologia')]
    public function cargarDato($dato)
    {
        $this->reset('estudios_seleccionados');
        $this->tipo_img = $dato;
        $this->indicaciones = '';
        $this->nombreImagenologia = TipoImagenologia::find($dato)->nombre;
        //dd($this->nombreImagenologia);
        $this->estudios = Estudio::where('id_tipo_img', $this->tipo_img)->orderBy('nombre', 'asc')->get();
        $this->dispatch('abrirModalImagenologia');
    }

    #[On('recibirHCImagenologia')]
    public function recibirHCImagenologia($data)
    {
        $this->datoHistoriaClinica = $data;
    }

    public function agregarEstudios($estudios)
    {
        $this->estudios_seleccionados = $estudios;
        $this->skipRender();
    }

    #[On('guardarImagenologia')]
    public function guardarImagenologia()
    {
        try {
            $this->validate();

            DB::beginTransaction();
            $inicioAnio = Carbon::now()->startOfYear();
            $finAnio = Carbon::now()->endOfYear();
            // 2) Consulta entre esas dos fechas
            $ultimo = Imagenologia::whereBetween('created_at', [$inicioAnio, $finAnio])
                ->orderBy('nro_solicitud', 'desc')
                ->lockForUpdate()
                ->value('nro_solicitud');

            $siguiente = $ultimo ? $ultimo + 1 : 1;

            $datosImagenologia = [
                'nro_solicitud' => $siguiente,
                'indicaciones' => $this->indicaciones,
                'id_tipo' => $this->tipo_img,
                'id_persona' => $this->datoHistoriaClinica['id_persona'],
                'id_user_created' => auth()->user()->id,
                'created_at' => now(),
            ];

            $imagenologiaCreada = Imagenologia::create($datosImagenologia);
            foreach ($this->estudios_seleccionados as $estudio_id) {
                $imagenologiaCreada->imagenologiaEstudio()->attach($estudio_id, [
                    'id_user_created' => auth()->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $id_imagenologia = $imagenologiaCreada->id;
            $this->registrarTablaHCPlanes($this->datoHistoriaClinica['id'], $this->plan, $imagenologiaCreada->id);
            DB::commit();
            // para que se descargue el pdf receta
            $this->js('window.open("/consulta_externa/pdf/imagenologia/' . $id_imagenologia . '", "_blank")');
            // Limpiar o cerrar modal
            $this->reset('estudios_seleccionados', 'indicaciones');
            $this->dispatch('pg:eventRefresh-ImagenPersonaTable');
            $this->dispatch('imagenologiaGuardada');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->skipRender();
            $mensajes = $e->validator->errors()->all();
            // Si solo quieres el primero:
            $primerMensaje = $mensajes[0];
            $this->dispatch('campoIncompleto', mensaje: $primerMensaje);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
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
                'id_plan' => $id_plan,
                'id_corresponde' => $id_corresponde,
            ];

            $values = [
                'id_user_created' => auth()->id(),
                'created_at' => now(),
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
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);
            $this->dispatch('error');
        }
    }
}
