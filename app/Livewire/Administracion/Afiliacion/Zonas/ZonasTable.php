<?php

namespace App\Livewire\Administracion\Afiliacion\Zonas;

use App\Models\Administracion\Zona;
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
use App\LiveWire\Administracion\Afiliacion\Zonas\ZonasMain;

final class ZonasTable extends PowerGridComponent
{
    use WithExport;
    public $zonaId;
    public string $tableName = 'ZonasRegistro';

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
        return Zona::leftjoin('administracion.users', 'zona.id_user_created', '=', 'users.id')
            ->leftjoin('administracion.municipio as m', 'zona.id_municipio', '=', 'm.id')
            ->leftjoin('administracion.provincia as p', 'p.id', '=', 'm.id_provincia')
            ->leftjoin('administracion.departamento as d', 'd.id', '=', 'p.id_departamento')
            ->select(
                'zona.*',
                'users.name as usuario',
                'm.nombre as m_nombre',
                'p.nombre as p_nombre',
                'd.nombre as d_nombre'
            )
            ->orderBy('zona.id', 'desc');
    }

    public function relationSearch(): array
    {
        return [
            
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('nombre')
            ->add('m_nombre')
            ->add('p_nombre')
            ->add('d_nombre')
            ->add('usuario')
            ->add('id_municipio')
            ->add('estado', function ($estadoContrato) {
                if ($estadoContrato->estado) {
                    return "<span style='color: green'>ACTIVO</span>";
                } else {
                    return "<span style='color:red'>INACTIVO</span>";
                }
            })
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
            
    }

    public function columns(): array
    {
        return [
            Column::make('Departamento', 'd_nombre')
                ->sortable()
                ->searchable(),

            Column::make('Provincia', 'p_nombre')
                ->sortable()
                ->searchable(),

            Column::make('Municipio', 'm_nombre')
                ->sortable()
                ->searchable(),

            Column::make('Zona', 'nombre')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'estado')
                ->sortable()
                ->searchable(),

            Column::make('Usuario Editor', 'usuario')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('habilitar')]
    public function habilitar($rowId): void
    {
        
        $this->zonaId = $rowId;
        $this->dispatch('zonaId', [$this->zonaId])->to(ZonasMain::class);
    }

    #[\Livewire\Attributes\On('deshabilitar')]
    public function deshabilitar($rowId): void
    {
         $this->zonaId = $rowId;
        $this->dispatch('zonaId', [$this->zonaId])->to(ZonasMain::class);
    }

    

    public function actions(Zona $row): array
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
                ->tooltip('EDITAR ZONA')
                ->dispatchTo('administracion.afiliacion.zonas.zonas-main', 'editarZonas', ['data' => $row->id ])
                ->can(allowed: auth()->user()->can('editar.zonas')),

        ];

        if ($row->estado ==false) {
            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>')
                ->id()
                ->class('btn btn-green')
                ->tooltip('HABILITAR')
                ->dispatch('deshabilitar', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('habilitar_desabilitar.zonas'));
        } else {
            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>')
                ->id()
                ->class('btn btn-red')
                ->tooltip('DESHABILITAR')
                ->dispatch('habilitar', ['rowId' => $row->id])
                ->can(allowed: auth()->user()->can('habilitar_desabilitar.zonas'));
        }

        return $buttons;
    }

}
