<?php

namespace App\Livewire\Administracion\Unidades;

use App\Models\Administracion\Unidad;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class UnidadesTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'unidadesRegistro';

    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Unidad::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('nombre')
            ->add('sigla')
            ->add('id_unidad_dep', function ($row) {
                // Verificar si la relación 'unidadUnidad' existe y retornar su nombre
                return $row->unidadUnidad ? $row->unidadUnidad->nombre : 'No asignado';
            })
            ->add('estado', function ($estadoUnidad) {
                if ($estadoUnidad->estado) {
                    return "<span style='color: green'>ACTIVO</span>";
                } else {
                    return "<span style='color:red'>INACTIVO</span>";
                }
            });
    }

    public function columns(): array
    {
        return [

            Column::make('Nombre', 'nombre')
                ->sortable()
                ->searchable(),

            Column::make('Sigla', 'sigla')
                ->sortable()
                ->searchable(),

            // Modificar para mostrar el nombre de la unidad dependiente
            Column::make('Unidad Dependiente', 'id_unidad_dep')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'estado')
                ->sortable()
                ->searchable(),

            Column::action('Acciones')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Unidad $row): array
    {
        $buttons = [
            Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-pencil-square"
                viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
            </svg>')
                ->class('btn btn-orange')
                ->tooltip('EDITAR UNIDAD')
                ->dispatchTo('administracion.unidades.unidades-main', 'editarUnidad', ['data' => $row->id ]),

            Button::add('eliminar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip('ELIMINAR UNIDAD')
                ->dispatchTo('administracion.unidades.unidades-main','confirmarEliminar', ['rowId' => $row->id]),

        ];
        return $buttons;
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
