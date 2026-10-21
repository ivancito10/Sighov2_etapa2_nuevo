<?php

namespace App\Livewire\Farmacia\Farmacia\Stock;

use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Movimiento;
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
use Illuminate\Support\Collection;
use App\Services\ListaUnidades;

final class StockFarmaciaTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'StockFarmaciaTable';
    public string $sortField = 'codigo_medicamento';
    public string $sortDirection = 'asc';

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

    public function calcularSaldo($id_medicamento, $tipo_stock)
    {
        $query = Movimiento::where('id_medicamento', $id_medicamento)
            ->where('id_unidad', 76);

        if (is_array($tipo_stock)) {
            $query->whereIn('stock_real', $tipo_stock);
        } else {
            $query->where('stock_real', $tipo_stock);
        }

        $suma_ingresos = (clone $query)->sum('cantidad_ingreso');
        $suma_salidas = (clone $query)->sum('cantidad_salida');

        return $suma_ingresos - $suma_salidas;
    }

    public function datasource(): Collection
    {
        // Obtener el ID de la farmacia una sola vez
        $idFarmacia = 76;

        // Cargar datos desde Medicamento
        return Medicamento::query() // Agregar relaciones si existen
            ->get()
            ->each(function ($medicamento) {

                // stock real
                /* $medicamento->stock_real = $this->calcularSaldo($medicamento->id, true);
                // stock reserva
                $medicamento->stock_reserva = $this->calcularSaldo($medicamento->id, [true, false]); */

                $medicamento->stock_real = 0;
                // stock reserva
                $medicamento->stock_reserva = 0;
            });
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('codigo_medicamento')
            ->add('nombre')
            ->add('forma_farmaceutica')
            ->add('concentracion')
            ->add('stock_real', function ($medicamento) {
                return number_format($medicamento->stock_real, 0, ',', '.');
            })
            ->add('stock_reserva', function ($medicamento) {
                return number_format($medicamento->stock_reserva, 0, ',', '.');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->index(),

            Column::make('COD. MEDICAMENTO', 'codigo_medicamento')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MEDICAMENTO', 'nombre')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-left', 'width: 300px; max-width: 300px; white-space: normal; word-wrap: break-word; overflow: hidden;')
                ->sortable()
                ->searchable(),

            Column::make('FORMA FARMACÉUTICA', 'forma_farmaceutica')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('CONCENTRACIÓN', 'concentracion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->sortable()
                ->searchable(),

            /* Column::make('STOCK REAL', 'stock_real')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('STOCK CON RESERVA', 'stock_reserva')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(), */

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
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

    public function actions(Medicamento $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('bin_card')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
        </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER')
            ->dispatchTo('farmacia.farmacia.stock.stock-farmacia-main', 'verStockFarmacia', ['data' => $row->id]);

        /*  $buttons[] = Button::add('stock')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-medical" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v.634l.549-.317a.5.5 0 1 1 .5.866L9 6l.549.317a.5.5 0 1 1-.5.866L8.5 6.866V7.5a.5.5 0 0 1-1 0v-.634l-.549.317a.5.5 0 1 1-.5-.866L7 6l-.549-.317a.5.5 0 0 1 .5-.866l.549.317V4.5A.5.5 0 0 1 8 4M5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
  <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
  <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
</svg>')
            ->class('btn btn-green')
            ->tooltip('BIN CARD')
            ->dispatchTo('farmacia.farmacia.stock.stock-farmacia-main', 'verBinCard', ['data' => $row->id]); */

        $buttons[] = Button::add('stock')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-medical" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v.634l.549-.317a.5.5 0 1 1 .5.866L9 6l.549.317a.5.5 0 1 1-.5.866L8.5 6.866V7.5a.5.5 0 0 1-1 0v-.634l-.549.317a.5.5 0 1 1-.5-.866L7 6l-.549-.317a.5.5 0 0 1 .5-.866l.549.317V4.5A.5.5 0 0 1 8 4M5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
  <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
  <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
</svg>')
            ->class('btn btn-green')
            ->tooltip('BIN CARD')
            ->dispatchTo('farmacia.farmacia.bincard.bincard-farmacia', 'verBinCard', ['data' => $row->id]);

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
