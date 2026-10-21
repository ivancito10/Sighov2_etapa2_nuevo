<?php

namespace App\Livewire\Imagenologia\AgendaEcografia;

use App\Models\Plataforma\AgendaEcografia;
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
use App\Models\Administracion\LinkServicio;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class AgendaEcografiaTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'agenda_ecografia_table';
    public string $sortDirection = 'asc';
    public string $sortField = 'hora_agenda';

    public string $fecha_agenda;
    public Collection $asignaciones_horario;
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
    public function mount(): void
    {
        parent::mount();

        $this->resetPage(); // te manda a la pagina 1
    }
    #[On('actualizar_FechaAgendaEcografia')]
    public function actualizarFechaAgendaEcografia($fecha)
    {
        //dd($fecha);
        if ($fecha) {
            $this->fecha_agenda = $fecha;
        } else {
            $this->fecha_agenda = now()->format('Y-m-d');
        }
    }

    public function datasource(): ?Collection
    {
        $fecha_filtro = $this->fecha_agenda ?? now()->format('Y-m-d');
        $token = session('access_token', '');
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
            //dd($data);
            $this->asignaciones_horario = $data->pluck('id_asignacion_horario')->filter()->unique()->values();
            //dd($this->asignaciones_horario);
        } else {
            $this->asignaciones_horario = collect();
        }

        $busqueda =  trim($this->search);
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
        // dd($ids_personas);

        $agendas_ecografia = AgendaEcografia::query()
            ->with([
                'personaAgenda:id,p_apellido,s_apellido,nombres,matricula_seguro,sexo,id_tipo_asegurado,fecha_nacimiento',
                'personaAgenda.tipoAseguradoPersona:id,tipo_asegurado',
                'servicioPlataformaAgenda:id,nombre_servicio'
            ])
            ->whereIn('id_asignacion_horario', $this->asignaciones_horario)
            ->where(function ($q) use ($busqueda, $ids_personas) {
                $q->where('hora_agenda', 'like', "%{$busqueda}%");
                if (!empty($ids_personas)) {
                    $q->orWhereIn('id_persona', $ids_personas);
                }
            })
            ->where('fecha_agenda', $fecha_filtro)
            ->orderByDesc('id')
            ->get();

        // dd($agendas_ecografia->first()->personaAgenda->id_tipo_asegurado);
        return $agendas_ecografia->map(function ($dato) {
            $dato->nombre_asegurado = trim(implode(' ', [
                $dato->personaAgenda->p_apellido ?? '',
                $dato->personaAgenda->s_apellido ?? '',
                $dato->personaAgenda->nombres ?? ''
            ]));
            $dato->matricula_asegurado = $dato->personaAgenda->matricula_seguro ?? '';
            $dato->sexo_asegurado = $dato->personaAgenda->sexo ?? '';
            $dato->fecha_nacimiento = $dato->personaAgenda->fecha_nacimiento ?? '';
            $dato->tipo_asegurado = $dato->personaAgenda->tipoAseguradoPersona->tipo_asegurado ?? 'NO DEFINIDO';
            $dato->tipo_agenda = $dato->servicioPlataformaAgenda->nombre_servicio ?? 'NO DEFINIDO';
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
            ->add('fecha_agenda_formatted', fn(AgendaEcografia $model) => Carbon::parse($model->fecha_agenda)->format('d/m/Y'))
            ->add('hora_agenda')
            ->add('confirmado')
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
            ->add('anulacion_ficha')
            ->add('fecha_anulacion')
            ->add('motivo_anulacion')
            ->add('observacion')
            ->add('clave_unica')
            ->add('ip')
            ->add('nombre_equipo')
            ->add('fecha_nacimiento')
            ->add('id_persona')
            ->add('nombre_asegurado')
            ->add('matricula_asegurado')
            ->add('edad', function ($persona) {
                return Carbon::parse($persona->personaAgenda->fecha_nacimiento)->age;
            })
            ->add('sexo_asegurado')
            ->add('tipo_asegurado')
            ->add('id_servicio_plataforma')
            ->add('tipo_agenda')
            ->add('id_asignacion_horario')
            ->add('id_user_created')
            ->add('id_user_updated')
            ->add('id_usuario_anula')
            ->add('id_suspension_horario_ecografia')
            ->add('id_sol_imagenologia')
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
                ->sortable(),

            Column::make('HORA', 'hora_agenda')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('MATRÍCULA', 'matricula_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('NOMBRE DEL PACIENTE', 'nombre_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->sortable()
                ->searchable(),

            Column::make('EDAD', 'edad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('SEXO', 'sexo_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('TIPO ASEGURADO', 'tipo_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-left')
                ->searchable()
                ->sortable(),

            Column::make('TIPO AGENDA', 'tipo_agenda')
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

    /*  public function filters(): array
    {
        return [
            Filter::datepicker('fecha_agenda'),
        ];
    } */



    public function actions(AgendaEcografia $row): array
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
</svg>')
            ->class('btn btn-blue')
            ->tooltip('VER DETALLE DE AGENDA')
            ->dispatchTo('imagenologia.agenda-ecografia.agenda-ecografia-main', 'verAgendaEcografia', ['data' => $row['id']])
            ->can(allowed: auth()->user()->can('ver.agenda_ecografia_etapa2'));
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
