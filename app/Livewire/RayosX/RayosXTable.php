<?php

namespace App\Livewire\RayosX;

use App\Models\RayosX\PlacasRayosX;
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
use App\Services\ServicioPersona;
use Illuminate\Database\Eloquent\Collection;

final class RayosXTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'RayosXTable';
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
        $datosPersonales =  new ServicioPersona();
        return $datosPersonales->datosPersona($id_persona);
    }

    public function datasource(): Collection
    {
        $rayos_x = PlacasRayosX::with(['personaAsegurado', 'personaSolicitante'])->get();

        $datos = $rayos_x->map(function ($dato) {
            // Datos del asegurado
            $asegurado = $dato->personaAsegurado;
            if ($asegurado) {
                $dato->nombre_asegurado = trim(
                    ($asegurado->p_apellido ?? '') . ' ' .
                        ($asegurado->s_apellido ?? '') . ' ' .
                        ($asegurado->nombres ?? '')
                ) ?: 'SIN DATO';
                $dato->matricula_asegurado = $asegurado->matricula_seguro ?? 'SIN DATO';
            } else {
                $dato->nombre_asegurado = 'SIN DATO';
                $dato->matricula_asegurado = 'SIN DATO';
            }

            // Datos del solicitante usando la relación
            $solicitante = $dato->personaSolicitante;
            if ($solicitante) {
                $dato->nombre_solicitante = trim(
                    ($solicitante->p_apellido ?? '') . ' ' .
                        ($solicitante->s_apellido ?? '') . ' ' .
                        ($solicitante->nombres ?? '')
                ) ?: 'SIN DATO';
            } else {
                $dato->nombre_solicitante = 'SIN DATO';
            }

            return $dato;
        });

        return $datos;
    }



    //   public function datasource(): Builder
    //   {
    //       return PlacasRayosX::query();
    //   }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('estado', function ($dato) {
                return $this->estadoFormatted((bool)$dato->estado);
            })
            ->add('id_persona_asegurado')
            ->add('id_persona_solicitante')
            ->add('codigo')
            ->add('nro_placas')
            ->add('observacion')
            ->add('fecha_entrega')
            ->add('fecha_entrega_formatted', fn(PlacasRayosX $model) => Carbon::parse($model->fecha_entrega)->format('d/m/Y'))
            ->add('fecha_estudio')
            ->add('estudios')
            ->add('estudio_origen')
            ->add('tipo', function ($dato) {
                return $this->tipoFormatted($dato->tipo);
            })
            ->add('nombre_asegurado')
            ->add('matricula_asegurado')
            ->add('nombre_solicitante')
            ->add('created_at');
    }
    public function tipoFormatted($tipo)
    {
        if ($tipo === true) {
            return '<p class="p-2" style="display: inline-block; background-color:rgb(235, 201, 13);  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                      ARCHIVO </p>';
        } else {
            return '<p class="p-2" style="display: inline-block; background-color:rgb(87, 94, 97);color: white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                      AFUERA </p>';
        }
    }
    public function estadoFormatted($estado)
    {
        if ($estado === true) {
            return '<span style="color: #27ae60" title="ACTIVO">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    </span>';
        } else {
            return '<span style="color: #e74c3c" title="INACTIVO"> 
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                </svg> </span>';
        }
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

            Column::make('CÓDIGO', 'codigo')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MATRÍCULA', 'matricula_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE ASEGURADO', 'nombre_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('SOLICITANTE', 'nombre_solicitante')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),


            Column::make('NRO. PLACAS', 'nro_placas')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('TIPO', 'tipo')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('OBSERVACIÓN', 'observacion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA ENTREGA', 'fecha_entrega_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center tablita-cuerpo-sin-mover')

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

    public function actions(PlacasRayosX $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER DETALLES')
            ->dispatchTo('rayos-x.rayos-x-main', 'abrirModalVer', ['data' => $row])
                ->can(allowed: auth()->user()->can('ver.placas_imagenologia'));


        if ($row['tipo'] === true) {
            $buttons[] = Button::add('afuera')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                  <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                </svg>')
                ->class('btn btn-green')
                ->tooltip('AFUERA')
                ->dispatchTo('rayos-x.rayos-x-main', 'cambiarEstadoSwitch', ['dato' => $row['id']])
                ->can(allowed: auth()->user()->can('archivo.placas_imagenologia'));

        } else {
            $buttons[] = Button::add('archivo')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                  <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>')
                ->class('btn btn-yellow')
                ->tooltip('ARCHIVO')
                ->dispatchTo('rayos-x.rayos-x-main', 'cambiarEstadoSwitch', ['dato' => $row['id']])
                ->can(allowed: auth()->user()->can('archivo.placas_imagenologia'));

        }

        return $buttons;
    }
}
