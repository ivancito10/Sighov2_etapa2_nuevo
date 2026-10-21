<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\MedicamentosPorVencer;

use App\Models\Farmacia\MedicamentoStock;
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
use App\Models\Farmacia\Medicamento;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Facades\Rule as PGRule;
use App\Services\ListaUnidades;

final class MedicamentoStockPorAgotarse extends PowerGridComponent
{

    public string $tableName = 'MedicamentosPorAgotarseTablee';
    use WithExport;

    public function setUp(): array
    {
        // $this->showCheckBox();

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

    //  public function obtenerIdAlmacenFarmacia()
    //  {
    //      $datosUnidades = new ListaUnidades();
    //      return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    //  }

    public function datasource(): Builder
    {
        $id_almacen =  89;
        //$id_almacen =  $this->obtenerIdAlmacenFarmacia();
        return MedicamentoStock::query()
            ->join('farmacia.medicamento as medicamento', function ($medicamento) {
                $medicamento->on('medicamento_stock.id_medicamento', '=', 'medicamento.id');
            })

            ->where('medicamento_stock.id_unidad', '=', $id_almacen)
            //->where('medicamento_stock.')
            ->select(
                'medicamento_stock.id_medicamento as id',
                DB::raw('SUM(medicamento_stock.stock_actualizado) as stock_actual'),
                'medicamento.nombre as nombre_medicamento',
                'medicamento.forma_farmaceutica as forma_farmaceutica_medicamento',
                'medicamento.concentracion as concentracion_medicamento',
                'medicamento.stock_minimo_almacen as stock_minimo',
                //'medicamento.fecha_vencimiento as fecha_vencimiento_medicamento',
            )

            ->groupBy(
                'medicamento_stock.id_medicamento',
                'medicamento.nombre',
                'medicamento.forma_farmaceutica',
                'medicamento.concentracion',
                'medicamento.stock_minimo_almacen'
            )
            ->havingRaw('SUM(medicamento_stock.stock_actualizado) <= medicamento.stock_minimo_almacen');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('cantidad_inicial')
            ->add('precio_unitario')
            // ->add('stock_actualizado', fn(MedicamentoStock $model) => number_format($model->stock_actualizado, 0, ',', '.'))
            ->add('nro_lote')
            // ->add('stock_actual')
            ->add('stock_actual', function ($cierre) {
                return number_format($cierre->stock_actual, 0, ',', '.');
            })
            ->add('id_medicamento')
            ->add('id_unidad')
            ->add('cierre_gestion')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('nro_ingreso')
            ->add('nombre_medicamento')
            ->add('forma_farmaceutica')
            ->add('concentracion')
            ->add('fecha_vencimiento')
            ->add('fecha_vencimiento_formatted', fn(MedicamentoStock  $model) => Carbon::parse($model->fecha_vencimiento)->format('d/m/Y'))
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index()
                // ->sortable()
                ->searchable(),

            /*             Column::make('Nro ingreso', 'nro_ingreso')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
 */
            Column::make('Medicamento', 'nombre_medicamento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Forma farmaceutica', 'forma_farmaceutica_medicamento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Concentración', 'concentracion_medicamento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Fecha vencimiento', 'fecha_vencimiento_formatted', 'fecha_vencimiento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('Cantidad', 'stock_actual')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

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


    public function actionRules($row): array
    {
        if (empty($row->stock_minimo) || $row->stock_minimo == 0) {
            $porcentaje = 0;
        } else {
            $porcentaje = ($row->stock_actual / $row->stock_minimo) * 100;
        }
        return [
            PGRule::rows()->when(fn($r) => $porcentaje < 50)
                ->setAttribute('class', 'table-danger'),


            PGRule::rows()->when(fn($r) => $porcentaje >= 50 && $porcentaje <= 100)
                ->setAttribute('class', 'table-warning'),

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
