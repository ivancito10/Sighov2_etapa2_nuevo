<?php

namespace App\Livewire\Enfermeria\Inyectables;

use App\Models\Enfermeria\RegistroInyeccion;
use App\Services\EnfermeriaNestService;
use Livewire\Component;
use Livewire\Attributes\On;

class InyectablesMain extends Component
{
    // Propiedades para controlar el modal
    public bool $modalVerAbierto = false;
    public ?array $detalleAtencion = null;

    public function render()
    {
        return view('livewire.enfermeria.inyectables.inyectables-main');
    }

    public function redirectToCreate()
    {
        return redirect()->route('enfermeria.inyectables.create');
    }

    #[On('verInyeccion')]
    public function verInyeccion($id)
    {
        // Tolera si PowerGrid envía $id como número o como array ['id' => X]
        $idRegistro = is_array($id) ? ($id['id'] ?? null) : $id;

        if (!$idRegistro) {
            return;
        }

        // Cargar datos de Fase 2
        $registro = RegistroInyeccion::with([
            'receta',
            'medicamentos.medicamento',
            'procedimiento'
        ])->find($idRegistro);

        if (!$registro) {
            $this->dispatch('errorActualizacion');
            return;
        }

        // Resolver paciente desde NestJS (Fase 1)
        $paciente = [
            'nombre'    => "PACIENTE #{$registro->id_persona}",
            'ci'        => 'S/C',
            'matricula' => 'S/M',
        ];

        try {
            $nestService = app(EnfermeriaNestService::class);
            $res = $nestService->obtenerPacientePorId((int)$registro->id_persona);
            $p = isset($res['data']) && is_array($res['data']) ? $res['data'] : $res;

            if (!empty($p) && is_array($p)) {
                $nombre = $p['nombreCompleto'] ?? $p['nombre_completo'] ?? null;
                if (!empty($nombre)) {
                    $paciente = [
                        'nombre'    => trim($nombre),
                        'ci'        => !empty($p['ci']) ? trim($p['ci']) : 'S/C',
                        'matricula' => !empty($p['matricula']) ? trim($p['matricula']) : 'S/M',
                    ];
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Error modal paciente {$registro->id_persona}: " . $e->getMessage());
        }

        // Estructurar información para el modal
        $this->detalleAtencion = [
            'id'             => $registro->id,
            'fecha_registro' => optional($registro->created_at)->format('d/m/Y H:i:s') ?? 'Sin fecha',
            'paciente'       => $paciente,
            'nro_receta'     => optional($registro->receta)->nro_receta ?? ($registro->id_receta ?? 'S/N (Manual)'),
            'medicamentos'   => $registro->medicamentos->map(function ($med) {
                return [
                    'nombre'      => optional($med->medicamento)->nombre ?? "Medicamento #{$med->id_medicamento}",
                    'observacion' => $med->observacion ?: 'Sin observaciones',
                ];
            })->toArray(),
            'procedimiento'  => $registro->procedimiento ? [
                'curacion_plana'       => (bool) $registro->procedimiento->curacion_plana,
                'curacion_infectada'   => (bool) $registro->procedimiento->curacion_infectada,
                'oxigenoterapia'       => (bool) $registro->procedimiento->oxigenoterapia,
                'retiro_puntos'        => (bool) $registro->procedimiento->retiro_puntos,
                'prueba_sensibilidad'  => (bool) $registro->procedimiento->prueba_sensibilidad,
                'sangria'              => (bool) $registro->procedimiento->sangria,
                'observaciones'        => $registro->procedimiento->observaciones ?: 'Ninguna',
            ] : null,
        ];

        // Abrir la ventana emergente
        $this->modalVerAbierto = true;
    }

    public function cerrarModalVer()
    {
        $this->modalVerAbierto = false;
        $this->detalleAtencion = null;
    }

    #[On('cambiarEstadoInyeccion')]
    public function cambiarEstadoInyeccion($id)
    {
        $idRegistro = is_array($id) ? ($id['id'] ?? null) : $id;
        try {
            $inyeccion = RegistroInyeccion::find($idRegistro);
            if ($inyeccion) {
                $inyeccion->estado = !$inyeccion->estado;
                $inyeccion->id_user_updated = auth()->id();
                $inyeccion->save();

                $this->dispatch('pg:eventRefresh-InyectablesTable');
                $this->dispatch('estadoActualizado');
            }
        } catch (\Throwable $th) {
            $this->dispatch('errorActualizacion');
        }
    }

    #[On('editarInyeccion')]
    public function editarInyeccion($id)
    {
        $idRegistro = is_array($id) ? ($id['id'] ?? null) : $id;
        return redirect()->route('enfermeria.inyectables.edit', ['id' => $idRegistro]);
    }
}