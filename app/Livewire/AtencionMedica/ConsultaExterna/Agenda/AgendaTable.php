<?php

namespace App\Livewire\AtencionMedica\ConsultaExterna\Agenda;

use App\Models\Administracion\LinkServicio;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\HistoriaClinica;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\Rule as PGRule;

final class AgendaTable extends PowerGridComponent
{
    public $asignaciones_horario;

    public int $id_persona;

    public string $fecha_agenda;

    public string $sortField = 'hora_agenda';

    public string $sortDirection = 'asc';

    public string $tableName = 'AgendaTable';

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage(50)
                ->showRecordCount(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        $this->resetPage(); // te manda a la pagina 1
    }

    #[On('fecha-agenda-actualizada')]
    public function actualizarFechaAgenda($fecha)
    {
        $this->fecha_agenda = $fecha;
    }

    public function datasource(): ?Collection
    {
        $token = session('access_token', '');
        $fecha_filtro = $this->fecha_agenda ?? now()->format('Y-m-d');
        $link = LinkServicio::where('nombre_servicio', 'AGENDA TRABAJO')->pluck('link_servicio')->first();
        $response = Http::withToken($token)
            ->post($link, [
                'id_persona' => auth()->user()->id_persona,
                'fecha_agenda' => $fecha_filtro,
            ]);

        if ($response->successful()) {
            $datos_servicio = $response->json('data');
            session(['id_especialista' => $datos_servicio[0]['id_especialista'] ?? null]);
            $data = collect($datos_servicio);
            $this->asignaciones_horario = $data->pluck('id_asignacion_horario')->filter()->unique()->values();

            //las agendas que existen
            $agendas = $data->flatMap(function ($turno) {
                return $turno['agendas'] ?? [];
            });

            // Procesar cada agenda para mostrar información adicional
            $agendas = $agendas->map(function ($agenda) {
                $persona = null;
                if (! empty($agenda['id_persona'])) {
                    $persona = Persona::find($agenda['id_persona']);
                }

                return (object) [
                    'id' => $agenda['id'],
                    'id_asignacion_horario' => $agenda['id_asignacion_horario'],
                    'nombre_persona' => $persona ? $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres : 'sin datos',
                    'edad' => $persona ? Carbon::parse($persona->fecha_nacimiento)->age : '',
                    'genero' => optional($persona)->sexo ?? '',
                    'tipo_asegurado' => optional(optional($persona)->tipoAseguradoPersona)->tipo_asegurado ?? '',
                    'matricula_seguro' => optional($persona)->matricula_seguro ?? '',
                    'anulacion_ficha' => $agenda['anulacion_ficha'] ? 'ANULADO' : 'ACTIVO',
                    'ficha_extra' => $agenda['ficha_extra'] ? 'EXTRA' : 'NO ES EXTRA',
                    'servicio_plataforma_agenda' => $agenda['servicio_plataforma_agenda']['nombre_servicio'] ?? 'sin datos',
                    'hora_agenda' => $agenda['hora_agenda'] ?? null,
                    'fecha_agenda' => $agenda['fecha_agenda'],
                    'id_suspension_horario' => $agenda['id_suspension_horario'] ?? null,
                    'id_persona' => $agenda['id_persona'] ?? null,
                    'asistencia' => $agenda['asistencia'] ?? null,
                    'asignacion_horario_agenda' => $agenda['asignacion_horario_agenda'] ?? null,
                    'suspension_horario_agenda' => $agenda['suspension_horario_agenda'] ?? null,
                ];
            });

            return $agendas;
        }

        return collect([]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('hora_agenda', function ($item) {
                return "<span style='background-color: #f4d03f; padding: 2px 5px; border-radius:8px;'>
                            <strong>" . \Carbon\Carbon::parse($item->hora_agenda)->format('H:i') . '</strong>
                        </span>';
            })

            ->add('nombre_persona', function ($item) {
                if ($item->id_suspension_horario != null) {
                    if ($item->id_persona != null) {
                        return $item->nombre_persona . ' - <span style="color: #e26600ff; font-weight: bold;">' . $item->suspension_horario_agenda['motivo_suspension_horario']['nombre_motivo'] . '</span>';
                    } else {
                        return '<span style="color: #e26600ff; font-weight: bold;">' . $item->suspension_horario_agenda['motivo_suspension_horario']['nombre_motivo'] . '</span>';
                    }
                }

                return $item->nombre_persona;
            })
            ->add('edad')
            ->add('genero')
            ->add('tipo_asegurado')
            ->add('matricula_seguro')
            ->add('id_suspension_horario')
            ->add('asistencia', function ($row) {
                if ($row->id_persona == null) {
                    return '';
                } elseif ($row->asistencia === true) {
                    return '<span style="display: inline-block; background-color: #28a745; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                                ASISTIÓ
                            </span>';
                } elseif ($row->asistencia === false) {
                    return '<span style="display: inline-block; background-color: #dc3545; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                               NO ASISTIÓ
                            </span>';
                } else {
                    return '<span style="display: inline-block; background-color: #6c757d; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                               PENDIENTE
                            </span>';
                }
            })
            ->add('servicio_plataforma_agenda')
            ->add('anulacion_ficha')
            ->add('ficha_extra_date', function ($date) {
                if ($date->ficha_extra == 'NO ES EXTRA') {
                    return '<span style="color: #b71c1c;"><b>SI </b></span>';
                } elseif ($date->ficha_extra == 'ES EXTRA') {
                    return '<span style="color: #1e8449;"><b> NO</b></span>';
                }
            })
            ->add('ficha_extra');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('HORA', 'hora_agenda')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('MATRÍCULA', 'matricula_seguro')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('NOMBRE DEL PACIENTE', 'nombre_persona')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->searchable('nombre_persona')
                ->sortable(),

            Column::make('EDAD', 'edad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('SEXO', 'genero')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('TIPO ASEGURADO', 'tipo_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->searchable()
                ->sortable(),

            Column::make('TIPO AGENDA', 'servicio_plataforma_agenda')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('ASISTENCIA', 'asistencia')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center tablita-cuerpo-sin-mover'),
        ];
    }

    #[On('recuperar_pdf')]
    public function recuperar_pdf($rowId): void
    {
        $historiaClinica = HistoriaClinica::where('id_agenda', $rowId)->first();
        $id_historia = $historiaClinica->id;
        //dd($historiaClinica);
        $this->js('window.open("/consulta_externa/pdf/copia_recuperada/' . $id_historia . '", "_blank")');
    }

    public function actions($row): array
    {
        $fechaSeleccionada = Carbon::parse($this->fecha_agenda ?? now());

        $hoy = now();
        if (! $fechaSeleccionada->isSameDay($hoy) || $row->id_persona === null) {
            if ($row->asistencia !== null) {
                return [
                    Button::add('ver_historia_clinica')
                        ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                       <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z"/>
                     </svg>')
                        ->class('btn btn-red')
                        ->dispatch('recuperar_pdf', ['rowId' => $row->id])
                        ->tooltip('VER HISTORIA CLINICA')
                      //  ->can(allowed: auth()->user()->can('super-admin-only'))
                ];
            } else {
                return [];
            }
        } else {
            $buttons = [];
            $buttons[] = Button::add('ver')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>')
                ->class('btn btn-green')
                ->dispatch('atenderAgenda', ['data' => $row])
                ->tooltip('ATENDER')
                ->can(allowed: auth()->user()->can('atender.consulta_externa'));

            //  if ($row->asistencia !== false) {
            if ($row->asistencia == false || $row->asistencia == null) {
                $buttons[] = Button::add('anular')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-diamond-fill" viewBox="0 0 16 16">
                        <path d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>')
                    ->class('btn btn-red')
                    ->dispatch('confirmarFalta', ['data' => $row])
                    ->tooltip('REGISTRAR FALTA')
                    ->can(allowed: auth()->user()->can('falta.consulta_externa'));
            }
            // Solo mostrar botón si asistencia es true
            if ($row->asistencia == true) {
                $buttons[] = Button::add('agenda anticipada')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-heart" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm2 .5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V4a.5.5 0 0 0-.5-.5zm5 4.493c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                        </svg>')
                    ->class('btn btn-blue')
                    ->dispatch('registrarAgendaAnticipada', ['data' => $row])
                    ->tooltip('REGISTRAR AGENDA ANTICIPADA')
                    ->can(allowed: auth()->user()->can('agenda_anticipada.consulta_externa'));

                $buttons[] = Button::add('Llenar formulotrasconsultasario')
                    ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z" />
                    </svg>')
                    ->class('btn btn-orange')
                    ->dispatch('llenarFormulario', ['data' => $row])
                    ->tooltip('LLENAR FORMULARIO')
                    ->can(allowed: auth()->user()->can('cuadernoss.consulta_externa'));
            }
            return $buttons;
        }
    }

    public function actionRules($row): array
    {

        return [
            PGRule::rows()
                ->when(fn($r) => $r->asistencia === true)
                ->setAttribute('class', 'table-success'),


            PGRule::rows()
                ->when(fn($r) =>  $r->asistencia === false)
                ->setAttribute('class', 'table-danger'),

        ];
    }



    public function rowAttributes($item): array
    {
        // Verificar si hay suspensión de horario
        if ($item->id_suspencion_horario !== null) {
            // Si hay suspensión, retornar un estilo de fondo rojo
            return [
                'style' => 'background-color: #f8d7da; color: #721c24; font-weight: bold;', // rojo claro con texto oscuro
            ];
        }

        // Si no hay suspensión, devolver un array vacío para mantener el estilo normal
        return [];
    }
}
