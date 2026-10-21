<?php

namespace App\Livewire\RayosX\Solicitudes;

use App\Models\ConsultaExterna\Imagenologia;
use App\Models\ConsultaExterna\ResultadoSolicitudImagenologia;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class SolicitudesRegister extends Component
{
    use WithFileUploads;

    public $placas_rx = [];

    public $observaciones;

    public $id_solicitud;

    public function render()
    {

        return view('livewire.rayos-x.solicitudes.solicitudes-register');
    }

    public function rules()
    {
        return [
            'observaciones' => 'required|string',
            'placas_rx' => 'required|array|min:1',
            'placas_rx.*' => 'image|max:2048',
        ];
    }

    #[On('cargarSolicitudRX')]
    public function cargarSolicitudRX($id_solicitud)
    {
        $this->id_solicitud = $id_solicitud;
    }

    #[On('guardarPlacasRX')]
    public function guardarPlacasRX()
    {
        // dd($this->id_solicitud);
        $this->validate();
        try {
            // $this->guardarImagenesFTP();
            $id_sol = $this->id_solicitud;
            $g_resultado = Imagenologia::find($id_sol);
            $g_resultado->resultado = true;
            $g_resultado->save();
            foreach ($this->placas_rx as $rowId => $imagen) {
                $extension = $imagen->getClientOriginalExtension();
                $fileName = $id_sol . '_' . ($rowId + 1) . '.' . $extension;
                $ftpPath = 'ConsultaExterna/Imagenologia/Rx/' . $fileName;
                $fileContent = file_get_contents($imagen->getRealPath());
                Storage::disk('ftp')->put($ftpPath, $fileContent);
                ResultadoSolicitudImagenologia::create([
                    'observaciones' => $this->observaciones,
                    'id_solicitud' => $id_sol,
                    'nombre_placa' => $fileName,
                    'id_user_created' => auth()->id(),
                    'id_user_updated' => null,
                ]);
                // $imagen->store('placas_rx', 'public');
            }
            $this->placas_rx = [];
            $this->dispatch('placas_registradas_g');
            $this->dispatch('pg:eventRefresh-RayosXSolicitudesTable');
        } catch (\Exception $e) {
            $this->dispatch('error_guardar_placas_rx', ['message' => $e->getMessage()]);
        }
    }
}
