<?php

namespace App\Livewire\AtencionMedica\OtrasConsultas;

use App\Models\Laboratorio\Solicitudes;
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
use App\Livewire\AtencionMedica\ConsultaExterna\Laboratorio\ListLaboPersona;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\TipoConsulta;
use Livewire\Attributes\On;

final class LaboratorioMainTable extends PowerGridComponent
{
    use WithExport;

    public int $id_persona;
    public string $tableName = 'LaboPersonaTable';
    public string $sortField = 'solicitudes.id';
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
        return Solicitudes::query()
        ->where('id_persona_asegurado', $this->id_persona);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id') 
            ->add('nro_solicitud')
            ->add('id_historia_clinica')
            ->add('id_persona_asegurado')
            ->add('id_persona_solicitante')
            ->add('nombre_especialista', function ($row) {
                $dato_esp = Persona::find($row->id_persona_solicitante);
                if (isset($dato_esp)) {
                    return $dato_esp->nombres. ' '.$dato_esp->p_apellido.' '.$dato_esp->s_apellido;
                }else{
                  return 'Sin nombre';  
                } 
            })
            ->add('observacion_solicitud')
            ->add('id_tipo_atencion')
            ->add('tipo_atencion', function ($row) {
                $tipo = TipoConsulta::find($row->id_tipo_atencion);
                if (isset($tipo)) {
                    return $tipo->nombre;
                }else{
                  return 'Sin nombre';  
                } 
            })
             
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('fecha_solicitud', function($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d/m/y');
            })
            ->add('updated_at')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(), 

            Column::make('NRO. SOLICITUD', 'nro_solicitud')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),   
 
            Column::make('ESPECIALISTA', 'nombre_especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),
            Column::make('FECHA SOLICITUD', 'fecha_solicitud')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(), 
            Column::make('TIPO ATENCIÓN', 'tipo_atencion')
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
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(Solicitudes $row): array
    {
        return [
            Button::add('pdf_laboratorio')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
            <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
            <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
            </svg>')
            ->class('btn btn-red')
            //->dispatch('ver_pdf_laboratorio', ['rowId' => $row->id])
            ->dispatchTo('atencion-medica.otras-consultas.otras-consultas-register', 'ver_laboratorio_pdf_register', ['data' => $row['id']])
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
