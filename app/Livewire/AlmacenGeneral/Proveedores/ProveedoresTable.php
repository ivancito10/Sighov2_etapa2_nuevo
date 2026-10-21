<?php

namespace App\Livewire\AlmacenGeneral\Proveedores;

use App\Models\Farmacia\InstitucionExterna;
use DB;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ProveedoresTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'proveedoresAlmacenGeneralTable';

    public string $sortField = 'institucion_externa.id';

    public string $sortDirection = 'desc';

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

    public function datasource(): Builder
    {
        return InstitucionExterna::query()
            ->with('residenciaInstitucionExterna', 'residenciaInstitucionExterna.zonaResidencia')
            ->where('proveedor_almacen_general', true);
        //  ->connection('pgsql_bd1')
        //  ->join('administracion.residencia as residencia', function ($residencia) {
        //      $residencia->on('residencia.id', '=', 'institucion_externa.id_residencia');
        //  })
        //   ->join(' administracion.zona as zona', function ($zona) {
        //       $zona->on('zona.id', '=', 'residencia.id_zona');
        //  //   })
        //  ->select(
        //      'residencia.direccion',
        //      'residencia.nro_vivienda',
        //      // 'zona.nombre as zonaNombre',
        //  );

        //    return DB::connection('pgsql_bd1')->table('farmacia.institucion_externa')
        //        ->join('pgsql.administracion.residencia as residencia', function ($join) {
        //            $join->on('residencia.id', '=', 'institucion_externa.id_residencia');
        //        })
        //        ->join('pgsql.administracion.zona as zona', function ($join) {
        //            $join->on('zona.id', '=', 'residencia.id_zona');
        //        })
        //        ->select(
        //            'residencia.direccion',
        //            'residencia.nro_vivienda',
        //            'zona.nombre as zonaNombre'
        //        );
    }

    public function relationSearch(): array
    {
        return [
            //  'residenciaInstitucionExterna' => ['direccion', 'nro_vivienda'],
            //  'residenciaInstitucionExterna.zonaResidencia' => ['nombre']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('id')
            ->add('razon_social')
            ->add('nit')
            ->add('registro_sanitario')
            ->add('telefono')
            ->add('correo')
            ->add('residenciaProveedor', fn (InstitucionExterna $proveedor) => $proveedor->residenciaInstitucionExterna->direccion.' NRO. '.$proveedor->residenciaInstitucionExterna->nro_vivienda.', '.$proveedor->residenciaInstitucionExterna->zonaResidencia->nombre)
            ->add('estado', function ($estadoProveedores) {
                if ($estadoProveedores->estado) {
                    return '<span style="color: green" title="ACTIVO"> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    </span>';
                } else {
                    return '<span style="color: #cd0404" title="INNACTIVO">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                            </svg> 
                    </span>';
                }
            })
            ->add('id_residencia')
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

            Column::make('RAZÓN SOCIAL', 'razon_social')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('NIT ', 'nit')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            /*Column::make('UBICACIÓN ', 'residenciaProveedor')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-left')
                ->searchable(),*/

            Column::make('TELÉFONO ', 'telefono')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('CORREO ', 'correo')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->bodyAttribute('text-center'),
        ];
    }

    public function actions(InstitucionExterna $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
        </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER PROVEEDOR')
            ->dispatchTo('almacen-general.proveedores.proveedores-main', 'verProveedor', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.proveedores_almacen_general'));

        if ($row->estado == false) {
            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                            </svg>')
                ->id()
                ->class('btn btn-green')
                ->dispatchTo('almacen-general.proveedores.proveedores-main', 'deshabilitarProveedor', ['data' => $row->id])
                ->tooltip('HABILITAR PROVEEDOR')
                ->can(allowed: auth()->user()->can('deshabilitar.proveedores_almacen_general'));

        } else {

            $buttons[] = Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>')
                ->class('btn btn-orange')
                ->dispatchTo('almacen-general.proveedores.proveedores-edit', 'editarProveedor', ['data' => $row->id])
                ->tooltip('EDITAR PROVEEDOR')
                ->can(allowed: auth()->user()->can('editar.proveedores_almacen_general'));

            $buttons[] = Button::add('habilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>')
                ->id()
                ->class('btn btn-red')
                ->dispatchTo('almacen-general.proveedores.proveedores-main', 'habilitarProveedor', ['data' => $row->id])
                ->tooltip('DESHABILITAR PROVEEDOR')
                ->can(allowed: auth()->user()->can('habilitar.proveedores_almacen_general'));
        }

        return $buttons;
    }
}
