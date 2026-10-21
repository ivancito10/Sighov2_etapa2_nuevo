<?php

namespace App\Livewire\AtencionMedica\OtrasConsultas;

use App\Models\Administracion\LinkServicio;
use App\Models\ConsultaExterna\HistoriaClinica;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;



final class OtrasConsultasTable extends PowerGridComponent
{
    // public function datasource(): ?Collection
    // {   $link_servicio = LinkServicio::where('nombre_servicio','AGENDAS TOTALES ESPECIALISTA')
    //             ->pluck('link_servicio')
    //             ->first();
    //     $token = session('access_token', '');
    //     $response = Http::withToken($token)
    //         ->post($link_servicio, [
    //             'id_persona'   => 3597,
    //         ]);
    //     $datos = $response->json('data');
    //     //dd($datos);
    //     return collect($datos['agendas']);
    // }

    /*     public function datasource(): Builder
    {
        $link = LinkServicio::where('nombre_servicio', 'OBTENER ESPECIALISTA POR ID USER')->pluck('link_servicio')->first();
        $response = Http::withToken(session('access_token'))->post($link, [
            'id_usuario' => auth()->user()->id,
        ]);
        //dd($link,$response,$response->json());
        $id_especialista = $response->json('data')['id'];

        return HistoriaClinica::with('historiaClinicaPaciente', 'historiaClinicaTipoConsulta')
            ->where('estado_consulta', true)
            // ->where('id_user_created',auth()->user()->id);
            ->where('id_especialista', $id_especialista)
            ->orderBy('id', 'desc');
    } */
    public function datasource(): ?Collection
    {
        $link = LinkServicio::where('nombre_servicio', 'OBTENER ESPECIALISTA POR ID USER')->pluck('link_servicio')->first();
        $response = Http::withToken(session('access_token'))->post($link, [
            'id_usuario' => auth()->user()->id,
        ]);
        $id_especialista = $response->json('data')['id'];
        if (!$id_especialista) {
            return collect([]);
        }

        return HistoriaClinica::with('historiaClinicaPaciente', 'historiaClinicaTipoConsulta')
            ->where('estado_consulta', true)
            ->where('id_especialista', $id_especialista)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($row) {

                $persona = $row->historiaClinicaPaciente;

                $row->paciente = $persona
                    ? trim($persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres)
                    : '';

                $row->matricula_seguro = $persona->matricula_seguro ?? '';
                $row->sexo = $persona->sexo ?? '';

                $row->tipo_consulta_nombre = $row->historiaClinicaTipoConsulta->nombre ?? '';

                return $row;
            });
    }
    public function setUp(): array
    {
        return [
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
            ->add('fecha_hora')
            ->add('fecha_agenda', function ($item) {
                return '<span >
                            <span>' . \Carbon\Carbon::parse($item->fecha_hora)->format('d/m/Y') . '</span>
                        </span>';
            })

            ->add('hora_agenda', function ($item) {
                return "<span style='background-color: #f4d03f; padding: 2px 5px; border-radius:8px;'>
                            <strong>" . \Carbon\Carbon::parse($item->fecha_hora)->format('H:i') . '</strong>
                        </span>';
            })
            ->add('paciente')


            /* ->add('paciente', function ($row) {
                $persona = $row->historiaClinicaPaciente;

                if (!$persona) {
                    return 'Sin datos';
                }

                return trim(
                    $persona->p_apellido . ' ' .
                        $persona->s_apellido . ' ' .
                        $persona->nombres
                );
            }) */
            /*   ->add('edad', function ($row) {
                return Carbon::parse($row->historiaClinicaPaciente->fecha_nacimiento)->age ?? '';
            }) */

            ->add('edad', function ($row) {
                $persona = $row->historiaClinicaPaciente;

                return $persona && $persona->fecha_nacimiento
                    ? Carbon::parse($persona->fecha_nacimiento)->age
                    : '';
            })

            ->add('genero', function ($row) {
                return optional($row)->historiaClinicaPaciente->sexo ?? '';
            })

            ->add('matricula_seguro', function ($row) {
                return $row->historiaClinicaPaciente->matricula_seguro ?? '';
            })
            ->add('tipo_consulta', function ($row) {
                return $row->historiaClinicaTipoConsulta->nombre ?? '';
            })
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
            });
        // ->add('created_at_formatted', function ($entry) {
        //     return Carbon::parse($entry->created_at)->format('d/m/Y');
        // });
    }
    public function relationSearch(): array
    {
        return [
            'historiaClinicaPaciente' => [
                'nombres',
                'p_apellido',
                's_apellido',
                'matricula_seguro',
                'sexo',
            ],
            'historiaClinicaTipoConsulta' => [
                'nombre',
            ],
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index()
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('FECHA ', 'fecha_agenda')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('HORA', 'hora_agenda')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center'),

            Column::make('MATRÍCULA', 'matricula_seguro')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('NOMBRE DEL PACIENTE', 'paciente')
                ->headerAttribute('class:text-center', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('class:text-center')
                ->searchable()
                /*  ->searchableRaw(
                    ($row->historiaClinicaPaciente->nombres ?? '') . ' ' .
                        ($row->historiaClinicaPaciente->p_apellido ?? '') . ' ' .
                        ($row->historiaClinicaPaciente->s_apellido ?? '')
                ) */
                /*   ->searchableRaw("
                        historia_clinica_pacientes.nombres || ' ' ||
                        historia_clinica_pacientes.p_apellido || ' ' ||
                        historia_clinica_pacientes.s_apellido
                    ") */
                ->sortable(),

            Column::make('EDAD', 'edad')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),


            Column::make('SEXO', 'genero')
                ->headerAttribute('text-center', 'font-size:15px; background-color:#2471a3 ; color:white;')
                ->bodyAttribute('text-center')
                ->sortable(),

            Column::make('TIPO CONSULTA', 'tipo_consulta')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center')
                ->searchable()
                ->sortable(),

            Column::make('ASISTENCIA', 'asistencia')
                ->headerAttribute('text-center ', 'font-size:15px; background-color: #2471a3   ; color:white;')
                ->bodyAttribute('text-center'),
            Column::action('OPCIONES')
                ->headerAttribute('text-center tablita-cabecera-sin-mover', 'font-size:15px; background-color:#2471a3; color:white;')
                ->bodyAttribute('text-center tablita-cuerpo-sin-mover'),
        ];
    }

    public function actions(HistoriaClinica $row): array
    {
        return [
            Button::add('verHistoriaClinica')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill"
                    viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                </svg>')
                ->class('btn btn-blue')
                ->dispatch('ver_historia_clinica', ['rowId' => $row->id])
                ->tooltip('VER HISTORIA CLINICA')
                ->can(allowed: auth()->user()->can('ver.otras_consultas')),

        ];
    }
}
