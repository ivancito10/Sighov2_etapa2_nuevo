<?php

namespace App\Livewire\Farmacia\Farmacia\SolicitudesAlmacen;

use App\Models\Farmacia\Solicitud;
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
use Illuminate\Database\Eloquent\Collection;

final class SolicitudesAlmacenTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'SolFarmaciaAlmacenFarmaciaTable';
    public string $sortField = 'id';
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
    public function datasource(): Collection
    {
        $solicitudes = Solicitud::query()
            //->where('solicitud.id_user_created', '=', auth()->user()->id)
            ->where('farmacia', true)
            ->orderBy('id', 'desc')
            ->get();

        $solicitudes->map(function ($solicitud) {
            //PREPARANDO LOS DATOS PARA MOSTRARLOS EN LA TABLA
            $solicitud->solicitante_name = $solicitud->usuarioCreador->name ?? 'SIN NOMBRE';
            $solicitud->fecha_solicitud_formatted = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
            $solicitud->cod_solicitud_formatted = 'SOL/FAR/' . $solicitud->cod_solicitud . '/' . Carbon::parse($solicitud->created_at)->year;
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
            //->add('cod_solicitud', fn(Solicitud $model)  => 'SOL/ALM/' . $model->cod_solicitud . '/' . Carbon::now()->format('Y'))
            ->add('fecha_solicitud')
            ->add('motivo')
            ->add('descripcion')
            ->add('estado')
            ->add('farmacia')
            ->add('motivo_anulacion')
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
            ->add('fecha_aprobacion')
            ->add('fecha_anulacion')
            //->add('solicitante', fn(Solicitud $sol) => $sol->usuarioCreador->name)
            ->add('fecha_entregado')
            ->add('id_usuario_aprueba')
            ->add('id_usuario_anula')
            ->add('id_unidad_solicita')
            ->add('id_unidad_recepciona')
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

            Column::make('COD. SOLICITUD', 'cod_solicitud_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('SOLICITANTE', 'solicitante_name')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('FECHA SOLICITUD', 'fecha_solicitud_formatted')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('ESTADO VALIDACIÓN', 'estado_validacion')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

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

    public function actions(Solicitud $row): array
    {
        $buttons = [];
        if ($row->estado_validacion == 'PENDIENTE') {
            $buttons[] = Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
                ->class('btn btn-blue')
                ->tooltip('VER SOLICITUD')
                ->dispatchTo('farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-main', 'verSolicitudAlmacen', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.solicitud_almacen'));

            $buttons[] = Button::add('edit')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>')
                ->class('btn btn-orange')
                ->route('farmacia.solicitudes-almacen.solicitudes-almacen-edit', ['id' => $row->id])
                ->tooltip('EDITAR SOLICITUD')
                ->can(allowed: auth()->user()->can('editar.solicitud_almacen'));

            $buttons[] = Button::add('deshabilitar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>')
                ->id()
                ->class('btn btn-red')
                ->dispatchTo('farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-main', 'anular_solicitud', ['data' => $row->id])
                ->tooltip('ANULAR SOLICITUD')
                ->can(allowed: auth()->user()->can('anular.solicitud_almacen'));
        }
        if ($row->estado_validacion == 'RECHAZADO' || $row->estado_validacion == 'ENTREGADO' || $row->estado_validacion == 'APROBADO') {

            $buttons[] = Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
                ->class('btn btn-blue')
                ->tooltip('VER SOLICITUD')
                ->dispatchTo('farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-main', 'verSolicitudAlmacen', ['data' => $row->id])
                ->can(allowed: auth()->user()->can('ver.solicitud_almacen'));
        }

        $buttons[] = Button::add('verPdfSolicitud')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                        <path d="M4.603 14.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.7 11.7 0 0 0-1.997.406 11.3 11.3 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.245.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 7.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                        </svg>')
            ->class('btn btn-red')
            ->dispatchTo('farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-main', 'verPdfSolicitud', ['data' => $row->id])
            ->tooltip('VER PDF')
            ->can(allowed: auth()->user()->can('pdf.solicitud_almacen'));
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
