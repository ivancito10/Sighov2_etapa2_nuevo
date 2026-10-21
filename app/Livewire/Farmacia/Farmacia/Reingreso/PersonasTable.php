<?php

namespace App\Livewire\Farmacia\Farmacia\Reingreso;

use App\Models\Administracion\Persona;
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

final class PersonasTable extends PowerGridComponent
{

    use WithExport;
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
        return Persona::query()
            ->where('afiliado', true)
            // ->whereNot('id_tipo_asegurado',0)
        ;
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('ci')
            ->add('ci_completo', function ($row) {
                return $row->ci . ($row->complemento ? '-' . $row->complemento : '');
            })
            ->add('complemento')
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('sexo')
            ->add('id_estado_civil')
            ->add('id_nacimiento_municipio')
            ->add('id_dept_exp')
            ->add('fecha_nacimiento_formatted', fn(Persona $model) => Carbon::parse($model->fecha_nacimiento)->format('d/m/Y'))
            ->add('matricula_seguro')
            ->add('id_tipo_asegurado')
            ->add('es_extranjero')
            ->add('estado_asuss')
            ->add('afiliado')
            ->add('grupo_sanguineo')
            ->add('alergias')
            ->add('clave_unica')
            ->add('excepcion')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),


            Column::make('CARNET IDENTIDAD', 'ci_completo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw('ci'),

            Column::make('NOMBRES', 'nombres')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw('nombres'),

            Column::make('PRIMER APELLIDO', 'p_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw('p_apellido'),

            Column::make('SEGUNDO APELLIDO', 's_apellido')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw('s_apellido'),

            Column::make('MATRICULA ASEGURADO', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw('matricula_seguro'),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),
        ];
    }


    public function filters(): array
    {
        return [];
    }


    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('seleccionar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                        <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                        </svg>')
                ->class('btn btn-orange')
                ->tooltip('SELECCIONAR ASEGURADO')
                ->dispatchTo('farmacia.farmacia.reingreso.reingreso-register', 'seleccionarPersona', ['data' => $row->id]),
        ];
    }
}
