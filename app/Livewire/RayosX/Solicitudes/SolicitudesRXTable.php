<?php

namespace App\Livewire\RayosX\Solicitudes;

use App\Models\ConsultaExterna\Imagenologia;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class SolicitudesRXTable extends PowerGridComponent
{
    use WithExport;

    public string $sortField = 'nro_solicitud';

    public string $sortDirection = 'desc';

    public string $tableName = 'RayosXSolicitudesTable';

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

    public function datasource(): ?Collection
    {
        $imagenologia = Imagenologia::query()->with(['tipoImagenologia', 'datosPacientePersona'])
            ->orderBy('id', 'desc')->get();
        $datos = $imagenologia->map(function ($dato) {

            $dato->nombre_asegurado = implode(' ', [$dato->datosPacientePersona->p_apellido ?? '', $dato->datosPacientePersona->s_apellido ?? '', $dato->datosPacientePersona->nombres ?? '']);
            $dato->matricula_asegurado = $dato->datosPacientePersona->matricula_seguro ?? '';
            $dato->ci_asegurado = $dato->datosPacientePersona->ci ?? '';
            $dato->informe_estado = $dato->informe->estado ?? false;

            return $dato;
        });

        return $datos;
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nro_solicitud')
            ->add('indicaciones')
            ->add('id_tipo')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('updated_at')
            ->add('ci_asegurado')
            ->add('matricula_asegurado')
            ->add('nombre_asegurado')
            ->add('tipoImagenologia.nombre')
            ->add('informe_estado', function ($row) {
                if ($row->informe_estado) {
                    return '<span style="display: inline-block; background-color: #28a745; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                                EMITIDO
                            </span>';
                } else {
                    return '<span style="display: inline-block; background-color: #6c757d; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                               PENDIENTE
                            </span>';
                }
            })
            ->add('created_at_formatted', function ($entry) {
                return Carbon::parse($entry->created_at)->format('d/m/Y');
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('NRO SOLICITUD', 'nro_solicitud')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('MATRICULA', 'matricula_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('CI', 'ci_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('ASEGURADO', 'nombre_asegurado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3  ; color:white')
                ->sortable()
                ->searchable(),

            Column::make('INDICACIONES', 'indicaciones')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('TIPO IMAGENOLOGIA', 'tipoImagenologia.nombre')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA SOLICITUD', 'created_at_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('INFORME', 'informe_estado')
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
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Imagenologia $row): array
    {
        if ($row->resultado) {
            return [
                Button::add('ver')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>')
                    ->class('btn btn-blue')
                    ->dispatchTo('rayos-x.solicitudes.solicitud-r-x-main', 'verDatosRX', ['data' => $row])
                    ->tooltip('Ver Agenda General')
                    ->can(allowed: auth()->user()->can('ver.resultados_imagenologia')),
                Button::add('registrar_informe')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-prescription" viewBox="0 0 16 16">
                            <path d="M5.5 6a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 1 0V9h.293l2 2-1.147 1.146a.5.5 0 0 0 .708.708L9 11.707l1.146 1.147a.5.5 0 0 0 .708-.708L9.707 11l1.147-1.146a.5.5 0 0 0-.708-.708L9 10.293 7.695 8.987A1.5 1.5 0 0 0 7.5 6zM6 7h1.5a.5.5 0 0 1 0 1H6z"/>
                            <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z"/>
                        </svg>')
                    ->class('btn btn-yellow')
                    ->dispatchTo('rayos-x.solicitudes.solicitud-r-x-main', 'verDatosRX', ['data' => $row])
                    ->tooltip('REGISTRAR INFORME')
                    ->can(allowed: auth()->user()->can('ver.resultados_imagenologia')),

                Button::add('edit')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                        fill="currentColor" class="bi bi-file-earmark-plus-fill" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 0.293A1 1 0 0 0 9.293 0zM8.5 5a.5.5 0 0 1 1 0v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0V8h-2a.5.5 0 0 1 0-1h2V5z"/>
                    </svg>
                    ')
                    ->id()
                    ->class('btn btn-red disabled')
                    ->tooltip('REGISTRAR ESTUDIOS')
                    ->can(allowed: auth()->user()->can('registrar.resultados_imagenologia')),

            ];
        } else {
            return [
                Button::add('ver')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        </svg>')
                    ->class('btn btn-blue')
                    ->dispatchTo('rayos-x.solicitudes.solicitud-r-x-main', 'verDatosRX', ['data' => $row])
                    ->tooltip('Ver detalles RX')
                    ->can(allowed: auth()->user()->can('ver.resultados_imagenologia')),

                Button::add('edit')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                        fill="currentColor" class="bi bi-file-earmark-plus-fill" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 0.293A1 1 0 0 0 9.293 0zM8.5 5a.5.5 0 0 1 1 0v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0V8h-2a.5.5 0 0 1 0-1h2V5z"/>
                    </svg>
                    ')
                    ->id()
                    ->class('btn btn-green')
                    ->tooltip('REGISTRAR ESTUDIOS')
                    ->dispatchTo('rayos-x.solicitudes.solicitud-r-x-main', 'abrirModalRegistroPlacasRX', ['data' => $row])
                    ->can(allowed: auth()->user()->can('registrar.resultados_imagenologia')),
            ];
        }
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
