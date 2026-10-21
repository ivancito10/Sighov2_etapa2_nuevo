<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Receta;

use App\Models\ConsultaExterna\Receta;
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
use App\Services\DatosEspecialista;

final class RecetaPersonaMainTable extends PowerGridComponent
{
    use WithExport;

    public int $id_persona;
    public string $tableName = 'RecetasPersonaTable';
    public string $sortField = 'receta.id';
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

    public function datasource(): Builder
    {
        return Receta::query()
        ->where('id_persona', $this->id_persona);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nro_receta_gestion', function ($dato) {
                if ($dato->nro_receta != null) { 
                    return $dato->nro_receta . '/' . Carbon::parse($dato->created_at)->year;
                }
            })
            ->add('fecha_entrega_formatted', fn (Receta $model) => Carbon::parse($model->fecha_entrega)->format('d/m/Y'))
            ->add('fecha_transcripcion_formatted', fn (Receta $model) => Carbon::parse($model->fecha_transcripcion)->format('d/m/Y'))
            ->add('manual')
            ->add('estado', function ($estado) {
                if ($estado->estado) {
                    return '<span> <span style="color: #196F3D;"><b>ENTREGADO</b></span></span>';
                } else {
                    return '<span> <span style="color: FF6600;"><b>PENDIENTE</b></span></span>';
                }
            })
            ->add('id_historia_clinica')
            ->add('id_tipo_consulta')
            ->add('id_especialista')
            ->add('nombre_especialista', function ($row) {
                $dato_esp = new DatosEspecialista();
                $datos = $dato_esp->datosEspecialista($row->id_especialista);

                // Verifica si los datos existen antes de acceder
                if (isset($datos['persona_especialista'])) {
                    return $datos['persona_especialista']['p_apellido'].' '.$datos['persona_especialista']['s_apellido'].' '.$datos['persona_especialista']['nombres'];
                }

                return 'Sin nombre';
            })
            ->add('especialidad', function ($row) {
                $dato_esp = new DatosEspecialista();
                $datos = $dato_esp->datosEspecialista($row->id_especialista);

                return $datos['especialidad_especialista']['especialidad'] ?? 'No definida';
            })
            ->add('created_at')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(), 

            Column::make('NRO. RECETA', 'nro_receta_gestion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
 

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('NOMBRE ESPECIALISTA', 'nombre_especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(), 

            Column::make('FECHA TRANSCRIPCIÓN',  'fecha_transcripcion_formatted')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
            ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_entrega'),
            Filter::datepicker('fecha_transcripcion'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Receta $row): array
    {
        return [
            Button::add('pdf_receta')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
            <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
            <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
            </svg>')
            ->class('btn btn-red')
            ->dispatchTo('atencion-medica.consulta-externa.historia-clinica.historia-clinica-main', 'abrir_modal_pdf_hc', ['data' => $row->id])
            ->tooltip('VER PDF')
        ];
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
