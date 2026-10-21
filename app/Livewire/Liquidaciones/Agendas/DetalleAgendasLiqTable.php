<?php

namespace App\Livewire\Liquidaciones\Agendas;

use App\Models\Administracion\DetalleAgendas;
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

final class DetalleAgendasLiqTable extends PowerGridComponent
{
    // use WithExport;
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        //$this->showCheckBox();

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
        // return DetalleAgendas::query();
        $searchTerm = trim($this->search);

        return DetalleAgendas::query()
            ->when(empty($searchTerm) || strlen($searchTerm) < 3, function ($query) {
                // si la busqueda tienen menos de 3 caracteres no busca 
                return $query->whereRaw('1 = 0');
            });
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('matricula_seguro')
            ->add('asegurado', fn($row) => $row->nombres . ' ' . $row->p_apellido . ' ' . $row->s_apellido)
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('nombres')
            ->add('especialidad')
            ->add('especilista', fn($row) => $row->medico_nombres . ' ' . $row->medico_p_apellido . ' ' . $row->medico_s_apellido)
            ->add('medico_p_apellido')
            ->add('medico_s_apellido')
            ->add('medico_nombres')
            ->add('fecha_agenda_formatted', fn(DetalleAgendas $model) => Carbon::parse($model->fecha_agenda)->format('d/m/Y'))
            ->add('hora_agenda')
            ->add('hora_agenda_formatted', fn(DetalleAgendas $model) => Carbon::parse($model->hora_agenda)->format('H:i'))
            ->add('created_at')
            ->add('nombre_servicio')
            ->add('usuario_creacion')
            ->add('anulacion_ficha')
            ->add('fecha_anulacion')
            ->add('motivo_anulacion')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('MATRICULA', 'matricula_seguro')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw('"administracion"."detalle_agendas"."matricula_seguro"'),

            Column::make('ASEGURADO', 'asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                // ->searchable(),
                ->searchableRaw('nombres || \' \' || p_apellido || \' \' || s_apellido'),

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw('"administracion"."detalle_agendas"."especialidad"'),

            Column::make('ESPECIALISTA', 'especilista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchableRaw('medico_nombres || \' \' || medico_p_apellido || \' \' || medico_s_apellido'),

            Column::make('FECHA AGENDA', 'fecha_agenda_formatted', 'fecha_agenda')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('HORA AGENDA', 'hora_agenda_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
            //   Filter::datepicker('fecha_agenda'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(DetalleAgendas $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER AGENDA')
            ->dispatchTo('liquidaciones.agendas.agendas-main', 'detalleAgendas', ['data' => $row['id']])
            ->can(allowed: auth()->user()->can('ver.agendas-liquidacion'));
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
