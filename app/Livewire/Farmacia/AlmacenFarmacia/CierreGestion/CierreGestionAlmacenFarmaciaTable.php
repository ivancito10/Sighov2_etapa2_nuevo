<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\CierreGestion;

use App\Models\Farmacia\CierreGestion;
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
use App\Services\ListaUnidades;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Farmacia\Movimiento;

final class CierreGestionAlmacenFarmaciaTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'CierreAlmacenFarmaciaTable';
    public string $sortField = 'id';
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

    /*public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }*/

    public function datasource(): Builder
    {
        $id_usuario = auth()->user()->id;
        $rolAdmin = Role::where('name', 'Super Admin')->first();
        $rolEncargadoAlmacen = Role::where('name', 'Encargado de Almacen de Farmacia')->first();
        $usuario_rol = RolUsuario::where('model_id', $id_usuario)->get();
        $esSuperAdmin = $usuario_rol->contains('role_id', $rolAdmin->id);
        $esEncargadoAlmacen = $usuario_rol->contains('role_id', $rolEncargadoAlmacen->id);


        $query = CierreGestion::query()
            ->with('medicamentoCierreGestion')
            ->join('farmacia.medicamento as med', function ($med) {
                $med->on('cierre_gestion.id_medicamento', '=', 'med.id');
            })
            ->where('med.estado', true)
            ->where('cierre_gestion.id_unidad', 89)
            ->select(
                'cierre_gestion.id',
                'med.id as idMedicamento',
                'med.nombre as nombreMedicamento',
                'med.codigo_medicamento as codigo_medicamento',
                'cierre_gestion.cantidad_sistema',
                'cierre_gestion.cantidad_fisica',
                'cierre_gestion.diferencia',
                'cierre_gestion.fecha_registro',
            )
            ->orderBy('med.codigo_medicamento', 'asc');

        /*if (!$esSuperAdmin || !$esEncargadoAlmacen) {
            $query->where('cierre_gestion.id_user_created', '=', $id_usuario);
        }*/

        return $query;
    }

    public function relationSearch(): array
    {
        return [
            'medicamentoCierreGestion' => ['codigo_medicamento', 'nombre']
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('cantidad_fisica', function ($cierre) {
                return number_format($cierre->cantidad_fisica, 0, ',', '.');
            })
            ->add('cantidad_sistema', function ($cierre) {
                return number_format($cierre->cantidad_sistema, 0, ',', '.');
            })
            ->add('fecha_registro', fn(CierreGestion $model) => Carbon::parse($model->fecha_registro)->format('d/m/Y'))
            ->add('diferencia')

            ->add('diferencia_positiva', function ($cierre) {
                return number_format(abs($cierre->diferencia), 0, ',', '.');
            })

            ->add('cantidad_sistema', function ($cierre) {
                return number_format($cierre->cantidad_sistema, 0, ',', '.');
            })
            ->add('id_unidad')
            ->add('id_medicamento')
            ->add('medicamento', function ($med) {
                return $med->nombreMedicamento;
            })
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->index()
                ->searchable(),
            Column::make('CÓDIGO', 'codigo_medicamento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MEDICAMENTO', 'medicamento', 'nombreMedicamento')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center', 'max-width:250px; white-space:normal; word-break:break-word;')
                ->sortable()
                ->searchable(),
            Column::make('CANTIDAD EN SISTEMA', 'cantidad_sistema')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CANTIDAD FISÍCA', 'cantidad_fisica')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('DIFERENCIA', 'diferencia_positiva')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA REGISTRO', 'fecha_registro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchableRaw("TO_CHAR(fecha_registro, 'DD/MM/YYYY')"),


            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function actions(CierreGestion $row): array
    {
        //dd($this->obtenerIdAlmacen(), $row);
        /*$ultimoMovimiento = Movimiento::where('id_medicamento', $row->idMedicamento)
            ->where('id_unidad', $this->obtenerIdAlmacen())
            ->orderBy('id', 'desc')
            ->first();*/

        $ultimoMovimiento = Movimiento::where('id_medicamento', $row->idMedicamento)
            ->where('id_unidad', 89)
            ->orderBy('id', 'desc')
            ->first();

        //dd($ultimoMovimiento);    

        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER CIERRE')
            ->dispatchTo('farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-main', 'verCierreAlmacen', ['data' => $row->id]);

        if ($ultimoMovimiento) {
            if ($ultimoMovimiento->cierre_gestion) {
                $buttons[] = Button::add('edit')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>')
                    ->class('btn btn-orange')
                    ->tooltip('EDITAR CIERRE')
                    ->dispatchTo('farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-edit', 'editarCierreAlmacen', ['data' => $row->id]);
            }
        }


        $buttons[] = Button::add('precio')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-coin" viewBox="0 0 16 16">
                        <path
                            d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z" />
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12" />
                    </svg>')
            ->class('btn btn-green')
            ->tooltip('AGREGAR PRECIO')
            ->dispatchTo('farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-main', 'agregarPrecioAlm', ['data' => $row->id]);


        /*$buttons[] = Button::add('Cierre_pdf')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                </svg>')
            ->class('btn btn-red')
            ->tooltip('VER PDF')
            ->dispatchTo('farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-main', 'verPdfCierreAlmacen', ['rowId' => $row->id]);*/

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
