<?php

namespace App\Livewire\AtencionMedica\AdmMedReceta;

use App\Models\ConsultaExterna\TipoConsulta;
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

final class AdmMedRecetaTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'AdmMedRecetaTable';

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
        return TipoConsulta::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre')
            ->add('estado', function ($estado) {
                if ($estado->estado) {
                    return '<span> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg></span>';
                } else {
                    return '<span ><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                </svg></span>';
                }
            })
            ->add('max_med_receta');
            // ->add('max_med_receta_colored', function ($row) {
            //     $bg = $row->max_med_receta > 4
            //         ? 'rgba(139, 69, 19, 0.35)' // café opaco
            //         : 'rgba(0, 128, 0, 0.30)';  // verde opaco

            //     $style = "background-color: {$bg}; padding:2px 6px; border-radius:4px; display:inline-block;";
            //     return "<span style=\"{$style}\">{$row->max_med_receta}</span>";
            // });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->index(),

            Column::make('TIPO DE CONSULTA', 'nombre')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('N° MÁXIMO POR RECETA','max_med_receta')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center') // aquí ya solo clase/estilo fijo (string)
                ->sortable()
                ->searchable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable()
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center ', 'font-size:15px; background-color:#2471a3 ; color:white;')
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

    public function actions(TipoConsulta $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('editar')
            ->slot(
                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>'
            )
            ->class('btn btn-orange')
            //>route('almacen_farmacia.ingresos.edit', ['id' => $row->id])
            ->tooltip('EDITAR N° MEDICAMENTOS')
            ->dispatch('abrirModalEditarMedReceta',['data' => $row->id]);

        if ($row->estado === true) {
            $buttons[] = Button::add('deshabilitar')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>'
                )
                ->class('btn btn-red') 
                ->tooltip('DESHABILITAR')
                ->dispatchTo('atencion-medica.adm-med-receta.adm-med-receta-main', 'cambiar_estado_receta', ['data' => $row->id]);
        }
        if ($row->estado === false) {
            $buttons[] = Button::add('habilitar')
                ->slot(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                    </svg>'
                )
                ->class('btn btn-green') 
                ->tooltip('HABILITAR') 
                ->dispatchTo('atencion-medica.adm-med-receta.adm-med-receta-main', 'cambiar_estado_receta', ['data' => $row->id]);
        }

        
        
        


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
