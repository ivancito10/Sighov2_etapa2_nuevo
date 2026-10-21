<?php

namespace App\Livewire\Liquidaciones\Farmacia;

use App\Models\ConsultaExterna\Receta;
use App\Services\DatosEspecialista;
use App\Services\ServicioPersona;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

final class RecetasLiqTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'RecetasFarmaciaLiqTable';

    public string $sortDirection = 'desc';

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersonales = new ServicioPersona();

        return $datosPersonales->datosPersona($id_persona);
    }

    public function obtenerDatosEspecialista($idEspecialista)
    {
        $datosEspecialista = new DatosEspecialista();

        return $datosEspecialista->datosEspecialista($idEspecialista);
    }

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



    public function datasource(): ?Collection
    {
        if (empty($this->search) || strlen($this->search) < 3) {
            return collect();
        }
        $busqueda = trim($this->search);
        $ids_personas = \App\Models\Administracion\Persona::on('pgsql_bd1')
            //->table('administracion.persona')
            ->where(function ($q) use ($busqueda) {
                $q->where('p_apellido', 'ilike', "%{$busqueda}%")
                    ->orWhere('s_apellido', 'ilike', "%{$busqueda}%")
                    ->orWhere('nombres', 'ilike', "%{$busqueda}%")
                    ->orWhere('matricula_seguro', 'ilike', "%{$busqueda}%")
                    ->orWhereRaw("CONCAT(p_apellido, ' ', s_apellido, ' ', nombres) ilike ?", ["%{$busqueda}%"]);
            })
            ->pluck('id')
            ->toArray();


        $recetas = Receta::query()
            ->with([
                'personaReceta:id,p_apellido,s_apellido,nombres,matricula_seguro',
                'personaEspecialistaReceta:id,p_apellido,s_apellido,nombres'
            ])
            ->where(function ($q) use ($busqueda, $ids_personas) {

                $q->where('nro_receta', 'like', "%{$busqueda}%")
                    ->orWhere('clave_unica', 'like', "%{$busqueda}%")
                    ->orWhereRaw("CAST(fecha_entrega AS TEXT) like ?", ["%{$busqueda}%"])
                    ->orWhereRaw("CAST(fecha_transcripcion AS TEXT) like ?", ["%{$busqueda}%"]);


                if (!empty($ids_personas)) {
                    $q->orWhereIn('id_persona', $ids_personas)
                        ->orWhereIn('id_persona_especialista', $ids_personas);
                }
            })
            ->orderByDesc('id')
            //->limit(100)
            ->get();

        return $recetas->map(function ($dato) {
            $dato->nombre_asegurado = trim(implode(' ', [
                $dato->personaReceta->p_apellido ?? '',
                $dato->personaReceta->s_apellido ?? '',
                $dato->personaReceta->nombres ?? ''
            ]));
            $dato->matricula_asegurado = $dato->personaReceta->matricula_seguro ?? '';
            $dato->especialista = trim(implode(' ', [
                $dato->personaEspecialistaReceta->p_apellido ?? '',
                $dato->personaEspecialistaReceta->s_apellido ?? '',
                $dato->personaEspecialistaReceta->nombres ?? ''
            ]));
            return $dato;
        });
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
            ->add('nro_receta')
            // ->add('fecha_entrega_formatted', fn(Receta $model) => Carbon::parse($model->fecha_entrega)->format('d/m/Y'))
            ->add('fecha_entrega_formatted', function (Receta $model) {
                if ($model->fecha_entrega == null) {
                    return 'Sin entregar';
                } else {
                    return Carbon::parse($model->fecha_entrega)->format('d/m/Y');
                }
            })
            ->add('fecha_transcripcion_formatted', fn(Receta $model) => Carbon::parse($model->fecha_transcripcion)->format('d/m/Y'))
            ->add('manual')
            ->add('estado')
            ->add('id_historia_clinica')
            ->add('id_tipo_consulta')
            ->add('id_especialista')
            ->add('id_persona_especialista')
            ->add('id_persona')
            ->add('nombre_asegurado', function (Receta $model) {
                $p = $model->personaReceta;

                return $p ? trim("{$p->p_apellido} {$p->s_apellido} {$p->nombres}") : 'Sin nombre';
            })
            ->add('matricula_asegurado', function (Receta $model) {
                $p = $model->personaReceta;

                return $p ? trim("{$p->matricula_seguro}") : '';
            })
            ->add('especialista', function (Receta $model) {
                $p = $model->personaEspecialistaReceta;

                return $p ? trim("{$p->p_apellido} {$p->s_apellido} {$p->nombres}") : 'Sin nombre';
            })
            ->add('id_usuario_dispensa')
            ->add('motivo_anulacion')
            ->add('fecha_dispensado')
            ->add('id_usuario_anula')
            ->add('fecha_anulado')
            ->add('clave_unica')
            ->add('id_estado_validacion')
            ->add('id_persona_transcribe')
            ->add('nro_receta_manual')
            ->add('observacion_quirofano')
            ->add('fecha_caducidad')
            ->add('fecha_proxima_consulta_formatted', fn(Receta $model) => Carbon::parse($model->fecha_proxima_consulta)->format('d/m/Y'))
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('estado_validacion', function ($receta) {
                return $this->estadoFormatted($receta->id_estado_validacion);
            })
            ->add('created_at')
            ->add('created_at');
    }

    public function estadoFormatted($estadoValidacion)
    {
        if ($estadoValidacion === 1) {
            return '<p style="display: inline-block; background-color: #6c757d; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                        PRESCRITO
                    </p>';
        }

        if ($estadoValidacion === 2) {

            return '<p class="p-2" style="display: inline-block; background-color: #2471A3; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     ENTREGADO </p>';
        }

        if ($estadoValidacion === 3) {
            return '<p class="p-2" style="display: inline-block; background-color:rgba(218, 77, 59, 1); color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     ANULADO </p>';
        }

        if ($estadoValidacion === 4) {
            return '<p class="p-2" style="display: inline-block; background-color:rgb(212, 103, 30);   color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     CADUCADO </p>';
        }
    }

    public function columns(): array
    {
        return [
            /*Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),*/

            Column::make('Nº RECETA', 'nro_receta')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('CLAVE', 'clave_unica')
                ->searchable()
                ->hidden(isHidden: true, isForceHidden: false)
                ->bodyAttribute(),

            Column::make('FECHA ENTREGA', 'fecha_entrega_formatted', 'fecha_entrega')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA TRANSCRIPCIÓN', 'fecha_transcripcion_formatted', 'fecha_transcripcion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ASEGURADO', 'nombre_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('MATRÍCULA', 'matricula_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALISTA', 'especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado_validacion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center tablita-cuerpo-sin-mover'),
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::datepicker('fecha_entrega'),
            // Filter::datepicker('fecha_transcripcion'),
            // Filter::datepicker('fecha_proxima_consulta'),
        ];
    }

    #[\Livewire\Attributes\On('pdf_receta')]
    public function pdf_receta($rowId): void
    {
        $this->js('window.open("/pdf-receta-preview/' . $rowId . '", "_blank")');
    }

    public function actions(Receta $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER RECETA MÉDICA')
            ->dispatchTo('liquidaciones.farmacia.farmacia-main', 'detalleReceta', ['data' => $row['id']])
            ->can(allowed: auth()->user()->can('ver.recetas-liquidacion'));

        $buttons[] = Button::add('pdf_receta')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
            <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
            <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
            </svg>')
            ->class('btn btn-red')
            ->tooltip('VER PDF')
            ->dispatch('pdf_receta', ['rowId' => $row->id]);


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
