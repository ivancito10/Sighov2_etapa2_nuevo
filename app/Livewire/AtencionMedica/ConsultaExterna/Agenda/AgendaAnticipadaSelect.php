<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Agenda;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Button;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;

final class AgendaAnticipadaSelect extends PowerGridComponent
{   
    public string $fecha_agenda;
    public $id_asignacionHorario = null;
    public $horarios;
    public function setUp(): array
    {
        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make() 
                ->showRecordCount(),
        ];
    }

    #[On('fechaAnticipadaSeleccionada')]
    public function fechaAnticipadaSeleccionada($horario)
    {   //dd($fecha,$id_asignacionHorario);
        $this->horarios = $horario ?? [];
        //dd($this->horarios);
        // $this->fecha_agenda = $fecha;
        // $this->id_asignacionHorario = $id_especialista;
    }

    // #[On('modalAgendaAnticipada')]
    // public function modalAgendaAnticipada($fecha , $id_asignacionHorario){
    //     //dd($fecha,$id_asignacionHorario);
    //     $this->fecha_agenda = $fecha;
    //     $this->id_asignacionHorario = $id_asignacionHorario;
        
    // }

    public function datasource(): ?Collection
    {
        return collect($this->horarios)->map(function ($item, $index) {
            $item['id'] = $index + 1;
            return $item;
        });
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id') 
            ->add('hora', function ($item) {
                return "<span style='background-color: #f4d03f; padding: 2px 5px; border-radius:8px;'>
                            <strong>" . \Carbon\Carbon::parse($item->hora)->format('H:i') . "</strong>
                        </span>";
            }) 
            ->add('estado', function ($row) {
                if ($row->estado === 'LIBRE') {
                    return '<span style="color: green; font-weight: bold;">LIBRE</span>';
                }

                if ($row->estado === 'OCUPADO') {
                    return '<span style="color: #C0392B; font-weight: bold;">OCUPADO</span>';
                }
                if ($row->estado === 'SUSPENDIDO') {
                    return '<span style="color: #F39C12; font-weight: bold;">SUSPENDIDO</span>';
                }

                return $row->estado;
            })
            ->add('asegurado')
            ->add('matricula');
             
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ASEGURADO', 'asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
            
            Column::make('MATRICULA', 'matricula')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
                  
            Column::make('HORA', 'hora')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),
        ];
    }
    public function actions($row): array
    {
        $buttons = [];

        if (($row['estado'] ?? null) === 'LIBRE') {
            $buttons[] =
                Button::add('ver')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                    </svg>')
                    ->class('btn btn-green')
                    ->dispatch('seleccionarAgenda', ['data' => $row]) // Use GET and pass data in query string
                    //->route('atencion_medica.historia_clinica.anticipada')
                    ->tooltip('SELECCIONAR') ;
        }

        return $buttons;
    }
}
