<?php

namespace App\Livewire\Enfermeria\Inyectables;

use App\Models\Enfermeria\RegistroInyeccion;
use App\Services\EnfermeriaNestService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class InyectablesTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'InyectablesTable';
    public string $sortField = 'enfermeria.registro_inyeccion.id';
    public string $sortDirection = 'desc';

    /**
     * Memoización en memoria para no llamar al servicio 3 veces por cada fila
     */
    protected array $pacientesMemo = [];

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return RegistroInyeccion::query()
            ->with(['receta'])
            //->where('estado', true)
            ->latest('id');
    }

    public function relationSearch(): array
    {
        return [
            'receta' => ['nro_receta'],
        ];
    }

    private function resolverPaciente(?int $idPersona): array
    {
        if (!$idPersona) {
            return ['nombre' => 'SIN PACIENTE', 'ci' => 'S/C', 'matricula' => 'S/M'];
        }

        return $this->pacientesMemo[$idPersona] ??= $this->consultarPaciente($idPersona);
    }

    private function consultarPaciente(int $idPersona): array
    {
        $userId = auth()->id();

        // Caché corto y por usuario para no omitir la autorización
        $datos = Cache::remember("nest:paciente:{$userId}:{$idPersona}", 120, function () use ($idPersona) {
            return app(EnfermeriaNestService::class)->obtenerPacientePorId($idPersona);
        });

        $nombre = $datos['nombreCompleto'] ?? $datos['nombre_completo'] ?? null;

        if (empty($nombre)) {
            Cache::forget("nest:paciente:{$userId}:{$idPersona}"); // No cachear fallos temporales
            return ['nombre' => "PACIENTE #{$idPersona}", 'ci' => 'S/C', 'matricula' => 'S/M'];
        }

        return [
            'nombre'    => trim($nombre),
            'ci'        => !empty($datos['ci']) ? trim($datos['ci']) : 'S/C',
            'matricula' => !empty($datos['matricula']) ? trim($datos['matricula']) : 'S/M',
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre_completo_paciente', fn ($row) => $this->resolverPaciente($row->id_persona)['nombre'])
            ->add('carnet_formatted',         fn ($row) => $this->resolverPaciente($row->id_persona)['ci'])
            ->add('matricula',                fn ($row) => $this->resolverPaciente($row->id_persona)['matricula'])
            ->add('receta_badge', function ($row) {
                $nro = optional($row->receta)->nro_receta;
                if (!empty($nro)) {
                    return '<span class="badge badge-primary" style="font-size:13px;">' . $nro . '</span>';
                } elseif (!empty($row->id_receta)) {
                    return '<span class="badge badge-primary" style="font-size:13px;">' . $row->id_receta . '</span>';
                }
                return '<span class="badge badge-secondary" style="font-size:13px;">S/N</span>';
            })
            ->add('estado_badge', function ($row) {
                return $row->estado
                    ? '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> ACTIVO</span>'
                    : '<span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> ANULADO</span>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE PACIENTE', 'nombre_completo_paciente')
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-left'),

            Column::make('C.I.', 'carnet_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('MATRÍCULA', 'matricula')
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('# RECETA', 'receta_badge')
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ESTADO', 'estado_badge')
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions(RegistroInyeccion $row): array
    {
        $botones = [];

        // Botón Ver: SVG del ojo y emisión directa del evento
        $botones[] = Button::add('ver')
            ->slot('
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>
            ')
            ->class('btn btn-sm btn-outline-primary mr-1')
            ->tooltip('VER ATENCIÓN')
            ->dispatch('verInyeccion', ['id' => $row->id]);

        // Botón Editar
        /* $botones[] = Button::add('editar')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>')
            ->class('btn btn-sm btn-outline-warning mr-1')
            ->tooltip('EDITAR ATENCIÓN')
            ->dispatch('editarInyeccion', ['id' => $row->id]); */

        // Botón Anular / Habilitar según el estado
        if ($row->estado) {
            $botones[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>')
                ->class('btn btn-sm btn-outline-danger')
                ->tooltip('ANULAR ATENCIÓN')
                ->dispatch('cambiarEstadoInyeccion', ['id' => $row->id]);
        } else {
            $botones[] = Button::add('activar')
                ->slot('<i class="fas fa-check"></i>')
                ->class('btn btn-sm btn-outline-success')
                ->tooltip('HABILITAR ATENCIÓN')
                ->dispatch('cambiarEstadoInyeccion', ['id' => $row->id]);
        }

        return $botones;
    }
}