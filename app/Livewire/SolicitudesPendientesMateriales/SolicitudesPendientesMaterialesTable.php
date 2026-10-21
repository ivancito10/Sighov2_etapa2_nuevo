<?php

namespace App\Livewire\SolicitudesPendientesMateriales;

use App\Models\AlmacenGeneral\Solicitud;
use App\Models\User;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

final class SolicitudesPendientesMaterialesTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'solicitudesPendientesMaterialesTable';
    public string $sortField = 'solicitud.id';
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

    public function obtenerDatosPersona($id_persona)
    {
        $obtenerDatosPers = new ObtenerDatosPersonalSsu();
        return $obtenerDatosPers->obtenerDatosPersonalSsu($id_persona);
    }
    public function obtenerDatosUnidad($id_unidad)
    {
        $obtenerDatosUnidad = new ObtenerDatosUnidadPorId();
        return $obtenerDatosUnidad->obtenerDatosUnidadPorId($id_unidad);
    }

    public function datasource(): Collection
    {
        // Usar query para aplicar un orderBy
        $solicitudes = Solicitud::query()
            ->whereIn('estado_validacion', ['REVISADO', 'APROBADO', 'ENTREGADO', 'RECHAZADO'])
            ->orderBy('id', 'desc')
            ->get();
        //dd($solicitudes);
        $solicitudes->map(function ($solicitud) {
            /*$idPersona = User::where('id', $solicitud->id_user_created)
                ->with('personaUser')
                ->first();

            $personaData = $this->obtenerDatosPersona($idPersona->personaUser->id);
            $personaData2 = $this->obtenerDatosUnidad($solicitud->id_unidad_recepciona);

            $solicitud->unidad_solicita = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
            $solicitud->unidad_recepciona = $personaData2['nombre'] ?? 'SIN DATO';*/

            //fechas listas para buscar
            $solicitud->fecha_solicitud_formatted = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
            $solicitud->fecha_aprobacion_formatted = $solicitud->fecha_aprobacion
                ? Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y')
                : 'SIN APROBAR';

            $solicitud->cod_solicitud_formatted = 'SOL/MAT/' . $solicitud->cod_solicitud . '/' . Carbon::parse($solicitud->created_at)->year;
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
            ->add('fecha_solicitud')
            //->add('fecha_solicitud_formatted', fn(Solicitud $model) => Carbon::parse($model->fecha_solicitud)->format('d/m/Y'))
            ->add('motivo')
            ->add('unidad_solicita', function ($solicitud) {
                return $solicitud->nombre_unidad_servicio;
            })
            ->add('unidad_recepciona', function ($solicitud) {
                $nombreUnidadRecepciona = 'SIN DATO';
                if ($solicitud->id_unidad_recepciona === 19){
                    $nombreUnidadRecepciona = 'ALMACEN GENERAL';
                }
                //return $solicitud->unidad_recepciona;
                return $nombreUnidadRecepciona;
            })
            ->add('descripcion')
            ->add('estado')
            ->add('motivo_anulacion')
            ->add('estado_validacion')
            ->add('estado_validacion', function ($dish) {
                if ($dish->estado_validacion == 'APROBADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #44BB60; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     APROBADO </p>';
                }
                if ($dish->estado_validacion == 'PENDIENTE') {
                    return '<p class="p-2" style="display: inline-block; background-color: #e1af00; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     PENDIENTE </p>';
                }
                if ($dish->estado_validacion == 'REVISADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #FF6D00; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     REVISADO </p>';
                }
                if ($dish->estado_validacion == 'ENTREGADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #2471A3; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     ENTREGADO </p>';
                }
                if ($dish->estado_validacion == 'RECHAZADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #C4380E; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     RECHAZADO </p>';
                }
            })
            /*
            ->add('fecha_aprobacion_formatted', function ($solicitud) {
                if ($solicitud->fecha_aprobacion) {
                    return Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y');
                } else {
                    return 'SIN APROBAR';
                }
            }) */

            ->add('id_usuario_aprueba')
            //  ->add('id_unidad_solicita', fn(Solicitud $solicitud) => $solicitud->unidadSolicitaSolicitud->sigla)
            //  ->add('id_unidad_recepciona', fn(Solicitud $solicitud) => $solicitud->unidadRecepcionaSolicitud->sigla)
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->index(),

            Column::make('COD. SOLICITUD', 'cod_solicitud_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('UNIDAD SOLICITANTE', 'unidad_solicita')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white; width: 350px;')
                ->bodyAttribute('text-wrap text-break', 'max-width: 350px;')
                ->sortable()
                ->searchable(),

            Column::make('UNIDAD RECEPCIONA', 'unidad_recepciona')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('FECHA SOLICITUD', 'fecha_solicitud_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('FECHA APROBACIÓN', 'fecha_aprobacion_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado_validacion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
        ];
    }

    public function actions(Solicitud $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
        </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER SOLICITUD')
            ->dispatchTo('solicitudes-pendientes-materiales.solicitudes-pendientes-materiales-main', 'verSolicitud', ['data' => $row->id])
            ->can(allowed: auth()->user()->can('ver.solicitudes_pendientes_almacen_general'));

        if ($row->estado_validacion == 'REVISADO') {
            /*$buttons[] = Button::add('aprobar')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>'
                )
                ->class('btn btn-orange')
                ->route('almacen-general.solicitudes-unidades-aprobar', ['id' => $row->id])
                ->tooltip('APROBAR SOLICITUD');*/

            $buttons[] = Button::add('aprobar_sin_revisar')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                            </svg>')
                ->id()
                ->class('btn btn-green')
                ->dispatchTo('solicitudes-pendientes-materiales.solicitudes-pendientes-materiales-main', 'aprobarSolicitud', ['data' => $row->id])
                ->tooltip('APROBAR SOLICITUD')
                ->can(allowed: auth()->user()->can('aprobar.solicitudes_pendientes_almacen_general'));

            $buttons[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>')
                ->class('btn btn-red')
                ->dispatchTo('solicitudes-pendientes-materiales.solicitudes-pendientes-materiales-main', 'anularSolicitud', ['data' => $row->id])
                ->tooltip('RECHAZAR SOLICITUD')
                ->can(allowed: auth()->user()->can('anular.solicitudes_pendientes_almacen_general'));
        }

        return $buttons;
    }
}
