<?php

namespace App\Livewire\Agendas\AgendaGeneral;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use App\Models\Administracion\Persona;
use App\Services\ServicioAgendaGeneral;
use PowerComponents\LivewirePowerGrid\Button;
use Livewire\Attributes\On;

final class AgendaGeneralTable extends PowerGridComponent
{
    public string $sortField = 'hora_agenda';
    public string $sortDirection = 'asc';
    public string $tableName = 'AgendaGeneralTable';
    public $fecha_busqueda;

    #[On('mandandoFechaATable')]
    public function actualizarFechaAgenda($fecha)
    {
        $this->fecha_busqueda = $fecha;
    }

    public function datasource(): ?Collection
    {
        $fecha_busqueda = $this->fecha_busqueda ?? now()->format('Y-m-d');
        //dd($fecha_busqueda);
        //$fecha_busqueda = '2025-06-25';
        $servicioAgenda = new ServicioAgendaGeneral();
        $respuesta = $servicioAgenda->obtenerAgendaGeneral($fecha_busqueda);
        // dd($respuesta);
        $success = $respuesta['success'];
        if ($success == true) {
            $this->dispatch('agendasEncontradas');
        } else {
            $this->dispatch('agendasNoEncontradas');
        }
        if ($respuesta->successful()) {
            $datos = collect($respuesta->json('data'));
            // dd($respuesta->json('data'));
            //dd($datos);
            $datos =  $datos->map(
                function ($item) {

                    $persona = null;
                    $persona = Persona::find($item['id_persona']);
                    if ($persona) {
                        $item['nombre_asegurado'] = (string)($persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres);
                        $item['matricula'] = (string)($persona->matricula_seguro);
                        $item['edad'] =  (string) Carbon::parse($persona->fecha_nacimiento)->age;
                        $item['genero'] =  (string) ($persona->sexo);
                    } else {
                        $item['nombre_asegurado'] = 'SIN DATO';
                        $item['matricula'] =  'SIN DATO';
                        $item['edad'] =  'SIN DATO';
                        $item['genero'] =  'SIN DATO';
                    }
                    $item['hora_agenda'] = Carbon::parse($item['hora_agenda'])->format('H:i');
                    $item['fecha_agenda'] = (string) Carbon::parse($item['fecha_agenda'])->format('d/m/Y');
                    $item['tipo_ficha'] = (string) (isset($item['servicio_plataforma_agenda']['nombre_servicio'])
                        ? $item['servicio_plataforma_agenda']['nombre_servicio']
                        : 'sin datos');
                    $item['especialidad'] = (string) (isset($item['asignacion_horario_agenda']['especialista_detalles']['especialidad_especialista']['especialidad'])
                        ? $item['asignacion_horario_agenda']['especialista_detalles']['especialidad_especialista']['especialidad']
                        : 'SIN DATOS');
                    $item['especialista'] = (string) (isset($item['asignacion_horario_agenda']['especialista_detalles']['persona'])
                        ? $item['asignacion_horario_agenda']['especialista_detalles']['persona']['p_apellido'] . ' ' . $item['asignacion_horario_agenda']['especialista_detalles']['persona']['s_apellido'] . ' ' . $item['asignacion_horario_agenda']['especialista_detalles']['persona']['nombres']
                        : 'SIN DATOS');
                    return $item;
                }

            );
            // dd($datos);
            return $datos;
        }
        return collect([]);
    }

    public function setUp(): array
    {
        //  $this->showCheckBox();

        return [
            //Exportable::make('export')
            //    ->striped()
            //    ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nombre_asegurado')
            ->add('matricula')
            ->add('especialidad')
            ->add('especialista')
            ->add('hora_agenda')
            ->add('fecha_agenda')
            ->add('tipo_ficha')
            ->add('created_at_formatted', function ($entry) {
                return Carbon::parse($entry->created_at)->format('d/m/Y');
            });
    }

    public function columns(): array
    {
        $columns = [
            Column::make('#', 'id')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->index()
                ->searchable(),
                
            Column::make('Matricula', 'matricula')
                ->bodyAttribute('text-center')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->sortable(),
            Column::make('Asegurado', 'nombre_asegurado')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->sortable(),
            Column::make('Especialidad', 'especialidad')
                ->bodyAttribute('text-center')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->sortable(),
            Column::make('Especialista', 'especialista')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->sortable(),
            Column::make('Hora Agenda', 'hora_agenda')
                ->bodyAttribute('text-center')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->sortable(),
            Column::make('Fecha registro', 'fecha_agenda')
                ->bodyAttribute('text-center')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->sortable(),
            Column::make('Tipo Ficha', 'tipo_ficha')
                ->bodyAttribute('text-center')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->searchable()
                ->sortable(),
        ];
        if (auth()->user()->hasRole('Super Admin')) {
            $columns[] = Column::action('Opciones')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3; color:white;');
        }
        return $columns;
    }

    public function actions($row)
    {
        $buttons = [];
        $buttons[] = Button::add('ver')
            ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
</svg>')
            ->class('btn btn-blue')
            ->dispatchTo('agendas.agenda-general.agenda-general-main', 'verAgendaGeneral', ['data' => $row])
            ->tooltip('Ver Agenda General');
        return $buttons;
    }
}
