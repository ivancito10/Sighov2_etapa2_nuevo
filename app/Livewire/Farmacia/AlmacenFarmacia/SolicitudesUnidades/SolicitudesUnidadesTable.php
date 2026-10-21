<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\SolicitudesUnidades;

use App\Models\Farmacia\Solicitud;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosUnidadPorId;

use Illuminate\Database\Eloquent\Collection;
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

final class SolicitudesUnidadesTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'AlmacenFarmaciaTable';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        //$this->showCheckBox();
        return [
            Exportable::make('export')
                //->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function obtenerDatosUnidad($id_unidad)
    {
        $obtenerDatosUnidad = new ObtenerDatosUnidadPorId();
        return $obtenerDatosUnidad->obtenerDatosUnidadPorId($id_unidad);
    }

    public function obtenerIdAlmacen()
    {
        //  $datosUnidades = new ListaUnidades();
        //  return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
        return 89;
    }

    public function datasource(): Collection
    {
        $solicitudes = Solicitud::where('id_unidad_recepciona', $this->obtenerIdAlmacen())
            ->whereIn('estado_validacion', ['APROBADO', 'ENTREGADO', 'RECHAZADO'])
            ->whereNot('fecha_aprobacion', null)
            ->get();
        $solicitudes->map(function ($solicitud) {
            /*$personaData = $this->obtenerDatosUnidad($solicitud->id_unidad_solicita);
            $solicitud->unidad_solicita = $personaData['nombre'] ?? 'SIN DATO';*/

            //fechas listas para buscar
            $solicitud->fecha_solicitud_formatted = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
            $solicitud->fecha_aprobacion_formatted = $solicitud->fecha_aprobacion
                ? Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y')
                : 'SIN APROBAR';
            $solicitud->cod_solicitud_formatted = 'SOL/ALM/' . $solicitud->cod_solicitud . '/' . Carbon::parse($solicitud->created_at)->year;
            return $solicitud;
        });
        return $solicitudes;
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
            //->add('fecha_solicitud', fn(Solicitud $model) => Carbon::parse($model->fecha_factura)->format('d/m/Y'))
            ->add('motivo')
            ->add('descripcion')
            ->add('nombre_unidad_servicio')
            /*
            ->add('fecha_aprobacion', function ($solicitud) {
                if ($solicitud->fecha_aprobacion) {
                    return Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y');
                } else {
                    return 'SIN APROBAR';
                }
            }) */

            ->add('estado', function ($estado) {
                if ($estado->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                    </svg></span>';
                }
            })
            ->add('estado_validacion', function ($dish) {
                if ($dish->estado_validacion == 'APROBADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #44BB60; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     APROBADO </p>';
                }
                if ($dish->estado_validacion == 'PENDIENTE') {
                    return '<p class="p-2" style="display: inline-block; background-color: #e1af00; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     PENDIENTE </p>';
                }
                if ($dish->estado_validacion == 'ENTREGADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #2471A3; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     ENTREGADO </p>';
                }
                if ($dish->estado_validacion == 'RECHAZADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #C4380E;   color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     RECHAZADO </p>';
                }
            })
            ->add('motivo_anulacion')
            ->add('id_usuario_aprueba')
            ->add('unidad_solicita', function ($solicitud) {
                return $solicitud->unidad_solicita;
            })
            ->add('id_unidad_recepciona')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('COD. SOLICITUD', 'cod_solicitud_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA SOLICITUD', 'fecha_solicitud_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA APROBACIÓN', 'fecha_aprobacion_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make(' UNIDAD SOLICITA', 'nombre_unidad_servicio')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::make('ESTADO VALIDACIÓN', 'estado_validacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),



            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
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

    public function actions(Solicitud $row): array
    {
        $buttons = [];

        if ($row->estado_validacion == 'ENTREGADO') {
            $buttons[] = Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>')
                ->class('btn btn-blue')
                ->dispatchTo('farmacia.almacen-farmacia.solicitudes-unidades.solicitudes-unidades-main', 'ver_solicitud', ['data' => $row->id])
                ->tooltip('VER SOLICITUD');

            $buttons[] = Button::add('pdf_solicitud')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                        <path d="M4.603 14.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.7 11.7 0 0 0-1.997.406 11.3 11.3 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.245.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 7.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                        </svg>')
                ->class('btn btn-red')
                ->dispatchTo('farmacia.almacen-farmacia.solicitudes-unidades.solicitudes-unidades-main', 'verPdf', ['data' => $row->id])
                ->tooltip('VER PDF');
        }

        if ($row->estado_validacion == 'APROBADO') {
            $buttons[] = Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>')
                ->class('btn btn-blue')
                ->dispatchTo('farmacia.almacen-farmacia.solicitudes-unidades.solicitudes-unidades-main', 'ver_solicitud', ['data' => $row->id])
                ->tooltip('VER SOLICITUD');

            $buttons[] = Button::add('entregar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>')
                ->class('btn btn-green')
                ->route('farmacia.almacen-farmacia.solicitudes-unidades-entregar', ['id' => $row->id])
                ->tooltip('ENTREGAR SOLICITUD');

            $buttons[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>')
                ->class('btn btn-red')
                ->dispatchTo('farmacia.almacen-farmacia.solicitudes-unidades.solicitudes-unidades-main', 'anularSolicitud', ['data' => $row->id])
                ->tooltip('ANULAR');
        }
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
