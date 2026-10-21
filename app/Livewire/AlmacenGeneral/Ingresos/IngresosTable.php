<?php

namespace App\Livewire\AlmacenGeneral\Ingresos;

use App\Models\AlmacenGeneral\Ingreso;
use App\Models\Farmacia\TipoProceso;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class IngresosTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'IngresosAlmacenGeneralTable';

    public string $sortField = 'ingreso.id';

    public string $sortDirection = 'desc';

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
        return Ingreso::query()
            ->with(['primeraFacturaIngreso'])
            ->where('ingreso.estado', true)
            /*->join('farmacia.factura as fac', function ($fac) {
                $fac->on('fac.id_ingreso', '=', 'ingreso.id');
            })*/
            ->join('farmacia.institucion_externa as instExt', function ($instExt) {
                $instExt->on('ingreso.id_institucion_externa', '=', 'instExt.id');
            })
            ->join('farmacia.tipo_proceso as tipo_proceso', function ($proceso) {
                $proceso->on('ingreso.id_tipo_proceso', '=', 'tipo_proceso.id');
            })
            /*  ->join('farmacia.tipo_proceso as tipo_proceso', function ($tipo) {
                $tipo->on('proceso.id_tipo_proceso', '=', 'tipo_proceso.id');
            }) */

            ->select(
                'ingreso.*',
                //'fac.nro_factura as factura',
                //'fac.fecha as fecha_factura',
                'instExt.razon_social as razon_social',
                'tipo_proceso.sigla as sigla',
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
            ->add('estado')
            ->add('estado_validacion', function ($ingreso) {
                if ($ingreso->estado_validacion == 'CONTABILIZADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #2471A3; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     CONTABILIZADO </p>';
                }

                if ($ingreso->estado_validacion == 'VALIDADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #008000; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     VALIDADO </p>';
                }

                if ($ingreso->estado_validacion == 'REGISTRADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #F0710F; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     REGISTRADO </p>';
                }
            })
            ->add('codigo_ingreso')
            ->add('codigo_ingreso_formatted', function ($tipoProcesoSigla) {
                $fechaCreacion = Carbon::parse($tipoProcesoSigla->created_at)->format('Y');
                $siglaProceso = TipoProceso::where('id', $tipoProcesoSigla->id_tipo_proceso)->first();

                return $siglaProceso->sigla.'/'.$tipoProcesoSigla->codigo_ingreso.'/'.$fechaCreacion;
            })
            ->add('fecha_ingreso', fn (Ingreso $model) => Carbon::parse($model->fecha_ingreso)->format('d/m/Y'))
            ->add('nro_lote')
            ->add('codigo_asmi')
            ->add('monto_total', fn (Ingreso $model) => number_format($model->monto_total, 2, ',', '.'))
            ->add('id_institucion_externa')
            ->add('id_proceso')
            ->add('factura', fn (Ingreso $model) => $model->primeraFacturaIngreso?->nro_factura)
            ->add('fecha_factura', fn (Ingreso $model) => $model->primeraFacturaIngreso ? Carbon::parse($model->primeraFacturaIngreso->fecha)->format('d/m/Y') : null)
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('CÓDIGO INGRESO', 'codigo_ingreso_formatted', 'codigo_ingreso')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchableRaw("
                    TRIM(CONCAT(
                        COALESCE(tipo_proceso.sigla, ''), 
                        '/', 
                        COALESCE(CAST(ingreso.codigo_ingreso AS TEXT), ''), 
                        '/',
                        EXTRACT(YEAR FROM ingreso.created_at)
                    ))
                "),

            Column::make('FECHA INGRESO', 'fecha_ingreso')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw("
                    TRIM(CONCAT(
                        LPAD(EXTRACT(DAY FROM ingreso.fecha_ingreso)::text, 2, '0'), 
                        '/', 
                        LPAD(EXTRACT(MONTH FROM ingreso.fecha_ingreso)::text, 2, '0'), 
                        '/',
                        EXTRACT(YEAR FROM ingreso.fecha_ingreso)
                    ))
                "),

            Column::make('PROVEEDOR', 'razon_social')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('# FACTURA', 'factura')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA FACTURA', 'fecha_factura')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            /*->searchableRaw("
                    TRIM(CONCAT(
                        LPAD(EXTRACT(DAY FROM fac.fecha)::text, 2, '0'),
                        '/',
                        LPAD(EXTRACT(MONTH FROM fac.fecha)::text, 2, '0'),
                        '/',
                        EXTRACT(YEAR FROM fac.fecha)
                    ))
                "),*/

            Column::make('MONTO TOTAL', 'monto_total')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-right')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado_validacion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_contrato'),
            Filter::datepicker('fecha_recepcion'),
        ];
    }

    public function actions(Ingreso $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg> ')
            ->class('btn btn-blue')
            ->tooltip('VER INGRESO')
            ->dispatchTo('almacen-general.ingresos.ingresos-main', 'verIngresoMateriales', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.ingresos_almacen_general'));

        if ($row->estado_validacion === 'REGISTRADO') {
            $buttons[] = Button::add('editar')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>'
                )
                ->class('btn btn-orange')
                ->route('almacen_general.ingresos.edit', ['id' => $row->id])
                ->tooltip('EDITAR INGRESO')
                ->can(allowed: auth()->user()->can('editar.ingresos_almacen_general'));

            $buttons[] = Button::add('validar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                </svg>')
                ->class('btn btn-green')
                ->tooltip('VALIDAR INGRESO')
                ->dispatchTo('almacen-general.ingresos.ingresos-main', 'validarIngresoMateriales', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('validar.ingresos_almacen_general'));
        }

        if ($row->estado_validacion !== 'REGISTRADO') {
            $buttons[] = Button::add('facturas')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0"/>
                    <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z"/>
                    <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z"/>
                    <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567"/>
                    </svg>'
                )
                ->class('btn btn-lila')
                // ->route('almacen_farmacia.ingresos.edit', ['id' => $row->id])
                ->tooltip('FACTURAS')
                ->dispatchTo('almacen-general.ingresos.agregar-facturas', 'detalleFacturas', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('facturas.ingresos_almacen_general'));
        }

        $buttons[] = Button::add('pdf')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                </svg>')
            ->class('btn btn-red')
            ->tooltip('PDF')
            ->dispatchTo('almacen-general.ingresos.ingresos-main', 'verPDF', ['rowId' => $row->id])
            ->can(allowed: auth()->user()->can('pdf.ingresos_almacen_general'));

        /*if ($row->estado_validacion === 'VALIDADO') {
            $buttons[] = Button::add('edit_fechas')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-week" viewBox="0 0 16 16">
                          <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
                          <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5zM11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                        </svg>')
                ->class('btn btn-orange')
                ->tooltip('MODIFICAR FECHAS DE VENCIMIENTO')
                ->dispatchTo('almacen-general.ingresos.ingresos-main', 'editarFechasVencimieno', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('super-admin-only'));
        }*/

        return $buttons;
    }
}
