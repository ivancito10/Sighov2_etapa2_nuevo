<?php

namespace App\Livewire\AtencionMedica\Enfermeria\SignosVitales;

use App\Models\Enfermeria\SignoVital;
use App\Models\Plataforma\Agenda;
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


final class AgendaGeneralTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'AgendaGeneralTable';
    public array $agendasConSignos = [];

    public string $sortField = 'hora_agenda';
    //public string $sortDirection = 'asc';

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

    public function datasource(): Builder
    {
        $this->agendasConSignos = SignoVital::pluck('id_agenda')->toArray();

        return Agenda::query()
            //->with('signosVitales')
            ->with([
                'personaAgenda',
                'asignacionHorarioAgenda.especialistaDetalles.persona',
                'asignacionHorarioAgenda.especialistaDetalles.especialidadEspecialista'
            ])
            ->whereDate('fecha_agenda', now()->toDateString())
            //->whereDate('fecha_agenda', now()->subDay(20))
            ->where('anulacion_ficha', false)
            ->whereNull('id_suspension_horario');
    }

    public function relationSearch(): array
    {
        return [
            'personaAgenda' => [
                'nombres',
                'p_apellido',
                's_apellido',
                'ci',
                'matricula_seguro'
            ],

            'asignacionHorarioAgenda.especialistaDetalles.persona' => [
                'nombres',
                'p_apellido',
                's_apellido',
            ],

            'asignacionHorarioAgenda.especialistaDetalles.especialidadEspecialista' => [
                'especialidad'
            ],

        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre_completo_paciente', function (Agenda $model) {
                $persona = $model->personaAgenda;
                if (!$persona) {
                    return 'SIN DATOS';
                }
                return trim(
                    $persona->p_apellido . ' ' .
                        $persona->s_apellido . ' ' .
                        $persona->nombres
                );
            })

            ->add('matricula_seguro', function (Agenda $model) {
                return $model->personaAgenda->matricula_seguro ?? '';
            })

            ->add('ci', function (Agenda $model) {
                return $model->personaAgenda->ci ?? '';
            })

            ->add('especialista', function (Agenda $model) {
                $personaEspecialista =
                    $model->asignacionHorarioAgenda
                    ?->especialistaDetalles
                    ?->persona;

                if (!$personaEspecialista) {
                    return 'SIN DATOS';
                }

                return trim(
                    $personaEspecialista->p_apellido . ' ' .
                        $personaEspecialista->s_apellido . ' ' .
                        $personaEspecialista->nombres
                );
            })

            ->add('especialidad', function (Agenda $model) {

                return $model->asignacionHorarioAgenda
                    ?->especialistaDetalles
                    ?->especialidadEspecialista
                    ?->especialidad
                    ?? 'SIN DATOS';
            })

            ->add('fecha_agenda_formatted', fn(Agenda $model) => Carbon::parse($model->fecha_agenda)->format('d/m/Y'))
            ->add('hora_agenda')
            ->add('confirmado')
            ->add('id_servicio_plataforma')
            ->add('id_persona')
            ->add('id_asignacion_horario')
            ->add('anulacion_ficha')
            ->add('fecha_anulacion')
            ->add('motivo_anulacion')
            ->add('id_suspension_horario')
            ->add('clave_unica')
            ->add('ficha_extra')
            ->add('ip')
            ->add('observacion')
            ->add('nombre_equipo')
            ->add('ficha_anticipada')
            ->add('id_usuario_anula')
            ->add('replicado')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('created_at')
            ->add('estado', function (Agenda $agenda) {
                $existe = in_array($agenda->id, $this->agendasConSignos);
                return $existe
                    ? '<p class="p-2" style="display: inline-block; background-color: #008000; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     REGISTRADO </p>'
                    : '<p class="p-2" style="display: inline-block; background-color: #F0710F; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.8rem;">
                     PENDIENTE </p>';
            });
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('NOMBRE DEL PACIENTE', 'nombre_completo_paciente')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable(),

            Column::make('MATRÍCULA', 'matricula_seguro')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('HORA', 'hora_agenda')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('CI', 'ci')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::make('ESPECIALISTA', 'especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable(),

            Column::make('ESPECIALIDAD', 'especialidad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable(),

            Column::make('ESTADO', 'estado')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datepicker('fecha_agenda'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Agenda $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('registrar')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-pulse" viewBox="0 0 16 16">
                <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>
                <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>
                <path d="M9.979 5.356a.5.5 0 0 0-.968.04L7.92 10.49l-.94-3.135a.5.5 0 0 0-.926-.08L4.69 10H4.5a.5.5 0 0 0 0 1H5a.5.5 0 0 0 .447-.276l.936-1.873 1.138 3.793a.5.5 0 0 0 .968-.04L9.58 7.51l.94 3.135A.5.5 0 0 0 11 11h.5a.5.5 0 0 0 0-1h-.128z"/>
                </svg>')
            ->class('btn btn-blue')
            ->dispatchTo('atencion-medica.enfermeria.signos-vitales.signos-vitales-register', 'abrirModalRegister', ['data' => $row->id])
            ->tooltip('REGISTRAR')
            ->can(allowed: auth()->user()->can('registrar.enfermeria'));
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
    
    #[On('refrescarTablaAgenda')]
    public function refreshTable()
    {
        // no hace falta código
    }*/
}
