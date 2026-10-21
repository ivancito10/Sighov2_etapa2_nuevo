<?php

namespace App\Livewire\SolicitudInsumos;

use App\Models\Farmacia\Solicitud;
use App\Models\User;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use App\Services\ObtenerDatosUnidadPorId;

final class SolicitudInsumosTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'solitudInsumosTable';

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

    // SERVICES 1. *
    public function obtenerDatosPersona($id_persona)
    {
        $obtenerDatos = new ObtenerDatosPersonalSsu();

        return $obtenerDatos->obtenerDatosPersonalSsu($id_persona);
    }

    public function obtenerIdUnidadUsuario()
    {

        // $idUnidadUsuario = $this->obtenerDatosPersona(auth()->user()->id_persona)['cargo_personal_ssu']['unidad_organizacional']['id'] ;
        $persona = $this->obtenerDatosPersona(auth()->user()->id_persona);

        $idUnidadUsuario = isset($persona['cargo_personal_ssu']['unidad_organizacional']['id'])
            ? $persona['cargo_personal_ssu']['unidad_organizacional']['id']
            : 'SIN DATO';

        $datosUnidades = new ListaUnidades();
        // return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidadUsuario)['id'];

        if ($unidad = collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidadUsuario)) {
            return $unidad['id'];
        } else {
            return 0; // 27/07/2025 TEMPORAL jiji
        }
    }
    
    public function obtenerDatosUnidad($id_unidad)
    {
        $obtenerDatosUnidad = new ObtenerDatosUnidadPorId();
        return $obtenerDatosUnidad->obtenerDatosUnidadPorId($id_unidad);
    }

    // SERVICES 2. *
    public function datasource(): Collection
    {
        $solicitudes = Solicitud::where('id_unidad_solicita', $this->obtenerIdUnidadUsuario())
            ->where('farmacia', false)
            ->orderBy('id', 'desc')
            ->get();
        //$solicitudes = Solicitud::all(); Y DESCOMENTEN ESTA

        $solicitudes->map(function ($solicitud) {
            //$personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->personaUser->id ?? null);

            //$solicitud->cargo = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
            //$solicitud->unidad = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';

            $solicitud->solicitante_name = $solicitud->usuarioCreador->name ?? 'SIN NOMBRE'; // Crea un atributo adicional
            $personaData2 = $this->obtenerDatosUnidad($solicitud->id_unidad_recepciona);
            $solicitud->unidad_recepciona = $personaData2['nombre'] ?? 'SIN DATO';
            //fechas listas para buscar
            $solicitud->fecha_solicitud_formatted = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
            $solicitud->fecha_aprobacion_formatted = $solicitud->fecha_aprobacion
                ? Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y')
                : 'SIN APROBAR';

            $solicitud->cod_solicitud_formatted = 'SOL/MED-INS/' . $solicitud->cod_solicitud . '/' . Carbon::parse($solicitud->created_at)->year;

            return $solicitud;
        });

        return $solicitudes;
    }

    public function relationSearch(): array
    {
        return [
            'usuarioCreador' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('cod_solicitud')
            //->add('fecha_solicitud', fn(Solicitud $model) => Carbon::parse($model->fecha_solicitud)->format('d/m/Y'))
            ->add('motivo')
            ->add('descripcion')
            ->add('estado')
            ->add('motivo_anulacion')
            ->add('cargo')
            ->add('nombre_unidad_servicio')
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
                    return '<p class="p-2" style="display: inline-block; background-color: #F05222;   color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     RECHAZADO </p>';
                }
                if ($dish->estado_validacion == 'ANULADO') {
                    return '<p class="p-2" style="display: inline-block; background-color: #F05222;   color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     ANULADO </p>';
                }
            })
            /*->add('fecha_aprobacion', function ($solicitud) {
                if ($solicitud->fecha_aprobacion) {
                    return Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y');
                } else {
                    return 'SIN APROBAR';
                }
            }) */
            ->add('id_usuario_aprueba')
            ->add('id_unidad_solicita')
            ->add('id_unidad_servicio')
            ->add('id_unidad_recepciona')
            ->add('unidad_recepciona', function ($solicitud) {
                return $solicitud->unidad_recepciona;
            })
            //->add('id_user_created', fn(Solicitud $solicitud) => $solicitud->usuarioCreador->name)
            ->add('id_user_updated')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')

                ->index(),

            Column::make('COD. SOLICITUD', 'cod_solicitud_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('UNIDAD SOLICITANTE', 'nombre_unidad_servicio')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left', 'width: 200px; max-width: 200px; white-space: normal; word-wrap: break-word; overflow: hidden;')
                ->sortable()
                ->searchable(),

            Column::make('SOLICITANTE', 'solicitante_name')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),

            Column::make('UNIDAD RECEPCIONA', 'unidad_recepciona')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->searchable(),


            Column::make('FECHA SOLICITUD', 'fecha_solicitud_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')

                ->sortable()
                ->searchable(),

            Column::make('FECHA APROBACIÓN', 'fecha_aprobacion_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')

                ->sortable()
                ->searchable(),

            Column::make('ESTADO VALIDACIÓN', 'estado_validacion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->sortable()
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;'),
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
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg> ')
            ->id()
            ->class('btn btn-blue')
            ->tooltip('VER SOLICITUD')
            ->dispatchTo('solicitud-insumos.solicitud-insumos-main', 'verSolicitudInsumos', ['data' => $row->id]);

        if ($row->estado_validacion == 'PENDIENTE') {
            $buttons[] = Button::add('editar')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>'
                )
                ->class('btn btn-orange')
                ->route('solicitudInsumos.edit', ['id' => $row->id])
                ->tooltip('EDITAR Y ENTREGAR SOLICITUD');

            $buttons[] = Button::add('anular')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>')
                ->class('btn btn-red')
                ->dispatchTo('solicitud-insumos.solicitud-insumos-main', 'anularSolicitud', ['data' => $row->id])
                ->tooltip('ANULAR');
        }

        $buttons[] = Button::add('pdf')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
        </svg>')
            ->class('btn btn-red')
            ->tooltip('PDF DE SOLICITUD')
            ->dispatchTo('solicitud-insumos.solicitud-insumos-main', 'verPDF', ['rowId' => $row->id]);

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
