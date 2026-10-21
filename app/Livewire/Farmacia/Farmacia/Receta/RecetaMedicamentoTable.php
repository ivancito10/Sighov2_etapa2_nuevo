<?php

namespace App\Livewire\Farmacia\Farmacia\Receta;

use App\Models\ConsultaExterna\RecetaMedicamento;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
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
use App\Services\EspecialidadService;

final class RecetaMedicamentoTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'RecetaMedicamentoTable';
    public int $id_persona;
    public $listEspecialidades, $listEspecialistas;
    
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
        $especialidadService = new EspecialidadService();
        $this->listEspecialidades = collect($especialidadService->obtenerEspecialistas())->pluck('especialidad_especialista', 'id');
        $this->listEspecialistas = collect($especialidadService->obtenerEspecialistas())->pluck('persona_especialista', 'id');
        //dd($this->listEspecialistas,$this->listEspecialidades);
        return RecetaMedicamento::query()
            ->where('estado_validez',true)
            ->where('fecha_fin','>',now())
            ->whereHas('recetaRecetaMedicamento', function ($query) {
                $query->where('id_persona', $this->id_persona);
            })
            ->with('recetaRecetaMedicamento');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {   
        $listEspecialidades = $this->listEspecialidades;
        $listEspecialistas  = $this->listEspecialistas;
        //dd($listEspecialidades,$listEspecialistas );
        return PowerGrid::fields()
            ->add('id') 
            ->add('nro_receta', function($row){
                return $row->recetaRecetaMedicamento->nro_receta.'/'.$row->recetaRecetaMedicamento->created_at->format('Y');
            })
            ->add('cod_medicamento', function($row){
                return $row->medicamentoRecetaMedicamento->codigo_medicamento;
            })
            ->add('medicamento', function($row){
                return $row->medicamentoRecetaMedicamento->nombre;
            })
            ->add('especialidad', function ($row) use ($listEspecialidades) {
                // asumiendo que $listEspecialidades es tipo [id => nombre]
                //dd($listEspecialidades[$row->recetaRecetaMedicamento->id_especialista]['especialidad']);
                
                $dato = $listEspecialidades[$row->recetaRecetaMedicamento->id_especialista]['especialidad'] ?? null;
                return $dato;
            })
            ->add('especialista', function ($row) use ($listEspecialistas) {
                // asumiendo que persona_especialista tiene estos campos
                $esp = $listEspecialistas[$row->recetaRecetaMedicamento->id_especialista] ?? null;

                if (!$esp) {
                    return '';
                }

                return trim(
                    ($esp['nombres'] ?? '') . ' ' .
                    ($esp['p_apellido'] ?? '') . ' ' .
                    ($esp['s_apellido'] ?? '')
                );
            })
            ->add('cantidad')
            ->add('indicaciones')
            ->add('forma_adm')
            ->add('recomendacion')
            ->add('fecha_transcripcion', fn ($model) => Carbon::parse($model->recetaRecetaMedicamento->fecha_transcripcion)->format('d/m/Y'))
            ->add('tipo_atencion', function($row){
                return $row->recetaRecetaMedicamento->tipoConsultaReceta->nombre ?? '';
            })
            ->add('dias_validez')
            ->add('fecha_fin', fn ($model) =>
                    $model->fecha_fin
                        ? Carbon::parse($model->fecha_fin)->format('d/m/Y')
                        : 'Sin fecha'
                )
            ->add('estado_validez')
            ->add('id_user_habilita')
            ->add('motivo_habilita')
            ->add('id_user_created')
            ->add('id_user_updated')
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

            Column::make('NRO. RECETA', 'nro_receta')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('COD. MEDICAMENTO', 'cod_medicamento')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('MEDICAMENTO', 'medicamento')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),
            
            Column::make('CANTIDAD', 'cantidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA TRANSCRIPCIÓN', 'fecha_transcripcion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('DIAS VALIDEZ', 'dias_validez')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('FECHA FIN', 'fecha_fin')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),
 
            Column::make('ESPECIALISTA', 'especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),
            
            Column::make('TIPO DE ATENCIÓN', 'tipo_atencion')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(), 

            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center tablita-cuerpo-sin-mover')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_ini'),
            //Filter::datepicker('fecha_fin'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(RecetaMedicamento $row): array
    {
        $buttons = [];

        $buttons[] = Button::add('registrar')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
            </svg>')
            ->class('btn btn-blue')
            ->dispatchTo('farmacia.farmacia.receta.habilitar-med-register', 'registroHabilitar', ['data' => $row['id']])
            ->tooltip('HABILITAR');

        return $buttons;
    }

     
}
