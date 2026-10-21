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
use PowerComponents\LivewirePowerGrid\Facades\Rule as PGRule;

use App\Models\Farmacia\Medicamento;

use App\Services\ListaUnidades;

final class MedicamentoStockPorVencer extends PowerGridComponent
{
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

    //   public function obtenerIdAlmacenFarmacia()
    //   {
    //       $datosUnidades = new ListaUnidades();
    //       return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    //   }

    public function datasource(): Builder
    {
        $id_almacen =  89;
        $hoy = Carbon::today();
        $limite_6 = Carbon::today()->addMonth(6);
        // dd($hoy);
        // dd($id_almacen);
        return MedicamentoStock::query()
            ->join('farmacia.medicamento as medicamento', function ($medicamento) {
                $medicamento->on('medicamento_stock.id_medicamento', '=', 'medicamento.id');
            })
            ->leftJoin('farmacia.detalle_ingreso as di', function ($join) {
                $join->on('di.id_medicamento', '=', 'medicamento_stock.id_medicamento')
                    ->whereColumn('di.nro_lote', '=', 'medicamento_stock.nro_lote');
            })
            ->leftJoin('farmacia.ingreso as i', function ($join) {
                $join->on('di.id_ingreso', '=', 'i.id');
            })
            ->where('medicamento_stock.id_unidad', '=', $id_almacen)
            ->whereBetween('medicamento_stock.fecha_vencimiento', [$hoy, $limite_6])
            ->select(
                'medicamento_stock.*',
                'medicamento.nombre as nombre_medicamento',
                'medicamento.codigo_medicamento as codigo_medicamento',
                'medicamento.forma_farmaceutica as forma_farmaceutica_medicamento',
                'medicamento.concentracion as concentracion_medicamento',
                'i.cod_ingreso as codigo_ingreso'
            );
    }



    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('cantidad_inicial')
            ->add('precio_unitario')
            ->add('stock_actualizado', fn(MedicamentoStock $model) => number_format($model->stock_actualizado, 0, ',', '.'))
            ->add('nro_lote')
            ->add('id_medicamento')
            ->add('codigo_medicamento')
            ->add('codigo')
            ->add('codigo_ingreso', fn(MedicamentoStock $model) => $model->codigo_ingreso ?? 'Cierre gestión')
            ->add('nombre_medicamento')
            ->add('forma_farmaceutica_medicamento')
            ->add('concentracion_medicamento')
            ->add('estado', function ($medicamento) {
                $calcular_meses = Carbon::now()->diffInMonths(
                    Carbon::parse($medicamento->fecha_vencimiento),
                    false
                );
                if ($calcular_meses <= 3) {
                    return '<p class="p-2" style="display: inline-block; background-color: white; color:#C4380E;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                    MENOR A 3 MESES </p>';
                } else {
                    return '<p class="p-2" style="display: inline-block; background-color: white; color:#e1af00;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                    MENOR A 6 MESES </p>';
                }
            })
            ->add('id_unidad')
            ->add('cierre_gestion')
            ->add('id_user_created')
            ->add('id_user_updated')
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

            Column::make('Nro ingreso', 'codigo_ingreso')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Medicamento', 'codigo_medicamento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


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

            Column::make('Stock actual', 'stock_actualizado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('Fecha vencimiento', 'fecha_vencimiento_formatted', 'fecha_vencimiento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('Estado', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

        ];
    }

    public function actionRules($row): array
    {
        $meses_para_vencimiento = Carbon::now()->diffInMonths(Carbon::parse($row->fecha_vencimiento), false);

        return [
            PGRule::rows()->when(fn($r) => $meses_para_vencimiento <= 3)
                ->setAttribute('class', 'table-danger'),


            PGRule::rows()->when(fn($r) => $meses_para_vencimiento > 3 && $meses_para_vencimiento <= 6)
                ->setAttribute('class', 'table-warning'),

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
