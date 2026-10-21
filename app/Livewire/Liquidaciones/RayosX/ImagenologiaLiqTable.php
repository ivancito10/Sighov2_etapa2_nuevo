<?php

namespace App\Livewire\Liquidaciones\RayosX;

use App\Models\ConsultaExterna\Imagenologia;
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
use Illuminate\Support\Collection;

final class ImagenologiaLiqTable extends PowerGridComponent
{
    use WithExport;

    public function setUp(): array
    {
        //$this->showCheckBox();

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
    // imagenologiaEstudio ,datosPacientePersona ,tipoImagenologia
    public function datasource(): ?Collection
    {
        if (empty($this->search) || strlen($this->search) < 3) {
            return collect();
        }
        $busqueda = trim($this->search);
        $ids_personas = \App\Models\Administracion\Persona::on('pgsql_bd1')
            ->where(function ($q) use ($busqueda) {
                $q->where('p_apellido', 'ilike', "%{$busqueda}%")
                    ->orWhere('s_apellido', 'ilike', "%{$busqueda}%")
                    ->orWhere('nombres', 'ilike', "%{$busqueda}%")
                    ->orWhere('matricula_seguro', 'ilike', "%{$busqueda}%")
                    ->orWhereRaw("CONCAT(p_apellido, ' ', s_apellido, ' ', nombres) ilike ?", ["%{$busqueda}%"]);
            })
            ->pluck('id')
            ->toArray();

        $imagenologia = Imagenologia::query()
            ->with([
                'datosPacientePersona:id,p_apellido,s_apellido,nombres,matricula_seguro',
                'tipoImagenologia:id,nombre',
                'imagenologiaEstudio',
            ])
            ->where(function ($q) use ($busqueda, $ids_personas) {
                $q->where('nro_solicitud', 'ilike', "%{$busqueda}%")
                    ->orWhereHas('tipoImagenologia', function ($queryRel) use ($busqueda) {
                        $queryRel->where('nombre', 'ilike', "%{$busqueda}%");
                    });
                if (!empty($ids_personas)) {
                    $q->orWhereIn('id_persona', $ids_personas);
                }
            })
            ->orderByDesc('id')
            ->limit(100)
            ->get();
        // Imagenologia::query();
        return $imagenologia->map(function ($dato) {
            $dato->nombre_asegurado = trim(implode(' ', [
                $dato->datosPacientePersona->p_apellido ?? '',
                $dato->datosPacientePersona->s_apellido ?? '',
                $dato->datosPacientePersona->nombres ?? '',
            ]));
            $dato->matricula_asegurado = $dato->datosPacientePersona->matricula_seguro ?? '';

            $dato->tipo = $dato->tipoImagenologia->nombre ?? '';

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
            ->add('nro_solicitud')
            ->add('indicaciones')
            ->add('id_tipo')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('fecha_sol', fn(Imagenologia $model) => Carbon::parse($model->created_at)->format('d/m/Y'))

            ->add('updated_at')
            ->add('id_persona')
            ->add('nombre_asegurado', function (Imagenologia $model) {
                $p = $model->datosPacientePersona;

                return $p ? trim("{$p->p_apellido} {$p->s_apellido} {$p->nombres}") : 'Sin nombre';
            })
            ->add('matricula_asegurado', function (Imagenologia $model) {
                $p = $model->datosPacientePersona;

                return $p ? trim("{$p->matricula_seguro}") : '';
            })
            ->add('resultado')
            ->add('created_at');
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

            Column::make('Nº SOLICITUD', 'nro_solicitud')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA SOLICITUD', 'fecha_sol')
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

            Column::make('TIPO EXAMEN', 'tipo')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),


            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
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

    #[\Livewire\Attributes\On('pdf_soli_imagenologia')]
    public function pdf_soli_imagenologia($rowId): void
    {
        $this->js('window.open("/consulta_externa/pdf/imagenologia/' . $rowId . '", "_blank")');
    }

    public function actions(Imagenologia $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>')
            ->class('btn btn-blue')
            ->tooltip('VER DETALLE')
            ->dispatchTo('liquidaciones.rayos-x.rayos-x-main', 'detalleImagenologia', ['data' => $row['id']])
            ->can(allowed: auth()->user()->can('ver.rayosx-liquidacion'));

        $buttons[] = Button::add('pdf_soli_imagenologia')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
            <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
            <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
            </svg>')
            ->class('btn btn-red')
            ->tooltip('VER SOLICITUD DE IMAGENOLOGIA')
            ->dispatch('pdf_soli_imagenologia', ['rowId' => $row->id]);


        // ->can(allowed: auth()->user()->can('ver.recetas_medicas'));
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
