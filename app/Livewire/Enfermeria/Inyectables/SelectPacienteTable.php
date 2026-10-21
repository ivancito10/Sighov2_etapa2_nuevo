<?php

namespace App\Livewire\Enfermeria\Inyectables;

use App\Services\EnfermeriaNestService;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class SelectPacienteTable extends PowerGridComponent
{
    public string $tableName = 'SelectPacienteTable';
    public string $primaryKey = 'id';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [
            Header::make()
                ->showSearchInput(),
            Footer::make()
                ->showPerPage(perPage: 5, perPageValues: [5, 10, 25, 50])
                ->showRecordCount(),
        ];
    }

    /**
     * Fuente de datos: Microservicio NestJS (/api/pacientes)
     */
    public function datasource(): Collection
    {
        try {
            $nestService = app(EnfermeriaNestService::class);
            $respuesta = $nestService->buscarPacientes(
                termino: $this->search ?? '',
                page: 1,
                limit: 150
            );

            $items = $respuesta['data'] ?? $respuesta ?? [];

            if (!is_array($items)) {
                return collect([]);
            }

            // Normalizamos cada elemento asegurando que exista la propiedad 'id' requerida por PowerGrid
            return collect($items)->map(function ($item) {
                $obj = is_array($item) ? (object) $item : $item;
                
                // Asignamos 'id' a partir de idPersona para satisfacer a PowerGrid
                $obj->id             = $obj->idPersona ?? $obj->id ?? 0;
                $obj->ci             = !empty($obj->ci) ? trim((string) $obj->ci) : 'S/C';
                $obj->matricula      = !empty($obj->matricula) ? trim((string) $obj->matricula) : 'S/M';
                $obj->nombreCompleto = !empty($obj->nombreCompleto) ? trim((string) $obj->nombreCompleto) : 'SIN NOMBRE';
                $obj->tipoAsegurado  = !empty($obj->tipoAsegurado) ? trim((string) $obj->tipoAsegurado) : 'TITULAR';

                return $obj;
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('SelectPacienteTable error NestJS: ' . $e->getMessage());
            return collect([]);
        }
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id', fn ($row) => (string) ($row->id ?? ''))
            ->add('ci', fn ($row) => (string) ($row->ci ?? 'S/C'))
            ->add('matricula', fn ($row) => (string) ($row->matricula ?? 'S/M'))
            ->add('nombreCompleto', fn ($row) => (string) ($row->nombreCompleto ?? 'SIN NOMBRE'))
            ->add('tipoAsegurado', fn ($row) => (string) ($row->tipoAsegurado ?? 'TITULAR'));
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')->index()
                ->headerAttribute('text-center', 'font-size:13px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('C.I.', 'ci')
                ->searchable()
                ->sortable()
                ->headerAttribute('text-center', 'font-size:13px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('MATRÍCULA', 'matricula')
                ->searchable()
                ->sortable()
                ->headerAttribute('text-center', 'font-size:13px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE COMPLETO', 'nombreCompleto')
                ->searchable()
                ->sortable()
                ->headerAttribute('text-center', 'font-size:13px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-left'),

            Column::make('TIPO ASEGURADO', 'tipoAsegurado')
                ->searchable()
                ->sortable()
                ->headerAttribute('text-center', 'font-size:13px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:13px; background-color: #2471a3; color:white;')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions($row): array
    {
        $idPersona      = $row->idPersona ?? $row->id ?? null;
        $nombreCompleto = $row->nombreCompleto ?? '';
        $matricula      = $row->matricula ?? 'S/M';
        $ci             = $row->ci ?? 'S/C';

        return [
            Button::add('seleccionar')
                ->slot('<i class="fas fa-check-circle text-success" style="font-size: 17px;"></i>')
                ->class('btn btn-sm btn-outline-success')
                ->tooltip('Seleccionar Paciente')
                ->dispatch('pacienteSeleccionado', [
                    'id'              => $idPersona,
                    'nombre_completo' => $nombreCompleto,
                    'matricula'       => $matricula,
                    'ci'              => $ci,
                ]),
        ];
    }
}