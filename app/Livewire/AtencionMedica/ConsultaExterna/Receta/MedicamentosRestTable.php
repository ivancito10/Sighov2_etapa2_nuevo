<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Receta;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class MedicamentosRestTable extends PowerGridComponent
{   
    public array $medicamentos = [];

    public function datasource(): ?Collection
    {   
        //dd('datasource',$this->medicamentos);
        return collect($this->medicamentos);
    }

    #[On('abrirModalVerMedicamentosRest')]
    public function cargarMedicamentosNoVigentes($medicamentos)
    {
        $this->medicamentos = collect($medicamentos)->map(function ($item) {
            return (object) array_merge($item, [
                'nombre' => $item['medicamento_receta_medicamento']['nombre'] ?? '',
                'codigo' => $item['medicamento_receta_medicamento']['codigo_medicamento'] ?? '',
            ]);
        })->toArray();

        //dd($this->medicamentos);
    }

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            // ->add('nombre',function($model){
            //     return $model->medicamento_receta_medicamento['nombre'];
            // })
            ->add('nombre')
            ->add('codigo')
            ->add('nro_receta')
            ->add('fecha_ini', function ($entry) {
                return Carbon::parse($entry->fecha_ini)->format('d/m/Y');
            })
            ->add('fecha_fin', function ($entry) {
                return Carbon::parse($entry->fecha_fin)->format('d/m/Y');
            })
            ->add('especialista_nombre')
            ->add('especialista_especialidad')
            ->add('created_at_formatted', function ($entry) {
                return Carbon::parse($entry->created_at)->format('d/m/Y');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('NRO RECETA', 'nro_receta')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('CODIGO', 'codigo')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('MEDICAMENTO', 'nombre')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('FECHA INICIO', 'fecha_ini')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
            
            Column::make('FECHA FIN', 'fecha_fin')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ESPECIALISTA', 'especialista_nombre')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;') 
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ESPECIALIDAD', 'especialista_especialidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
        ];
    }
}
