<?php

namespace App\Livewire\Administracion\Aportes\ValidarPersona;

use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
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

final class ValidarPersonaTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

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
        ->join('administracion.tipo_asegurado', 'persona.id_tipo_asegurado', '=', 'tipo_asegurado.id')
        ->select('persona.*', 'tipo_asegurado.tipo_asegurado as nombre_asegurado');
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
            ->add('complemento')
            ->add('nombres')
            ->add('p_apellido')
            ->add('s_apellido')
            ->add('matricula_seguro')
            ->add('id_tipo_asegurado')
            //->add('nombre_asegurado')

            ->add('nombre_asegurado')
            ->add('es_extranjero')
            ->add('estado_asuss', function ($dish) {
                if ($dish->estado_asuss) {
                    return "<span style='color: green'><b>VALIDADO</b></span>";
                } else {
                    return "<span style='color:red'><b>OBSERVADO</b></span>";
                }
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
            ->headerAttribute('text-center', 'font-size:17px')
                ->sortable()
                ->searchable(),

            

            Column::make('Nombres', 'nombres')
            ->headerAttribute('text-center', 'font-size:17px')
                ->sortable()
                ->searchable(),

            Column::make('Primer A.', 'p_apellido')
            ->headerAttribute('text-center', 'font-size:17px')
                ->sortable()
                ->searchable(),

            Column::make('Segundo A.', 's_apellido')
            ->headerAttribute('text-center', 'font-size:17px')
                ->sortable()
                ->searchable(),

            Column::make('C.I', 'ci')
            ->headerAttribute('text-center', 'font-size:17px')
            ->sortable()
            ->searchable(),

            Column::make('Complemento', 'complemento')
            ->headerAttribute('text-center', 'font-size:17px')
                ->sortable()
                ->searchable(),

            Column::make('Tipo Asegurado', 'nombre_asegurado')
            ->headerAttribute('text-center', 'font-size:17px')
                ->sortable()
                ->searchable(),
            Column::make('ASSUS', 'estado_asuss')
            ->headerAttribute('text-center', 'font-size:17px')
                ->sortable()
                ->searchable(),

            Column::action('Action')
            ->headerAttribute('text-center', 'font-size:17px')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_nacimiento'),
            Filter::datepicker('fecha_nacimiento'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Persona $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit: '.$row->id)
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
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
