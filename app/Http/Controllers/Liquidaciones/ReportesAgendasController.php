<?php

namespace App\Http\Controllers\Liquidaciones;

use App\Exports\AgendasExport;
use App\Http\Controllers\Controller;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Aportes\Institucion;
use App\Models\Plataforma\Agenda;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\SeguroInterior;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportesAgendasController extends Controller
{
    public function reportesAgendasLiquidacion($tipo_asegurado, $id_institucion, $fecha_inicio, $fecha_fin)
    {
        // $tipo_asegurado = 'ESTUDIANTES';
        // dd('este es el controller de agengas');
        if ($tipo_asegurado == 'ESTUDIANTES') {
            $id_personas = $this->obtenerEstudiantes();
            $nombre_institucion = 'UNIVERSIDAD MAYOR DE SAN ANDRES ESTUDIANTES';
            $acronimo = 'UMSA-EST';
        } elseif ($tipo_asegurado == 'BENEFICIARIO') {
            $id_personas = $this->obtenerBenefiarios($id_institucion);
            $institucion = Institucion::find($id_institucion);
            $nombre_institucion = $institucion->nombre;
            $acronimo = $institucion->acronimo;
            // dd($nombre_institucion);
        } elseif ($tipo_asegurado == 'TITULAR') {
            $resultado = $this->obtenerAgendasTitulares($id_institucion);
            $id_personas = $resultado['id_persona_titular'];
            $institucion = Institucion::find($id_institucion);
            $nombre_institucion = $institucion->nombre;
            $acronimo = $institucion->acronimo;
        } elseif ($tipo_asegurado == 'CONVENIO') {
            $id_personas = $this->obtenerConveniosInterior($id_institucion);
            $institucion = SeguroInterior::find($id_institucion);
            $nombre_institucion = $institucion->nombre_seguro;
            $acronimo = $institucion->acronimo;
        } else {
            $nombre_institucion = '';
        }

        $agendas = $this->obtenerAgendas($id_personas, $fecha_inicio, $fecha_fin);
        // dd($agendas, $nombre_institucion);
        $fechaInicioFormatted = Carbon::parse($fecha_inicio)->format('d/m/Y');
        $fechaFinFormatted = Carbon::parse($fecha_fin)->format('d/m/Y');

        $name = 'ReporteAgendas'.'-'.$acronimo.'-'.$fecha_inicio.'-'.$fecha_fin;

        return Excel::download(
            new AgendasExport($agendas, $nombre_institucion, $tipo_asegurado, $fechaInicioFormatted, $fechaFinFormatted),
            $name.'.xlsx'
        );
    }

    public function obtenerAgendas($idsPersona, $fecha_inicio, $fecha_fin)
    {
        $agendas = Agenda::with([
            'servicioPlataformaAgenda',
            'personaAgenda.tipoAseguradoPersona',
            'asignacionHorarioAgenda.especialistaAsignacionHorario.persona',
            'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
        ])
            ->whereIn('id_persona', $idsPersona)
            ->whereBetween('fecha_agenda', [$fecha_inicio, $fecha_fin])
            ->get();
        $detalle_agenda = $agendas->map(function ($agenda, $index) {
            $especialista = $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->persona;
            if ($agenda->anulacion_ficha == true) {
                $estado_agenda = 'ANULADO';
            } elseif ($agenda->id_suspension_horario != null) {
                $estado_agenda = 'SUSPENDIDO';
            } else {
                $estado_agenda = 'AGENDADO';
            }

            return [
                // 'id_agenda' => $agenda->id,
                'nro' => $index + 1,
                'asegurado' => $agenda->personaAgenda
                    ? trim("{$agenda->personaAgenda->nombres} {$agenda->personaAgenda->p_apellido} {$agenda->personaAgenda->s_apellido}")
                    : ' ',
                'matricula_asegurado' => $agenda->personaAgenda->matricula_seguro,
                'tipo_asegurado' => $agenda->personaAgenda->tipoAseguradoPersona->tipo_asegurado,
                'fecha_agenda' => Carbon::parse($agenda->fecha_agenda)->format('d/m/Y'),
                'hora_agenda' => Carbon::parse($agenda->hora_agenda)->format('H:m'),
                'especialista' => $especialista
                    ? trim("{$especialista->nombres} {$especialista->p_apellido} {$especialista->s_apellido}")
                    : ' ',
                'especialidad' => $agenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad,
                'tipo_agenda' => $agenda->servicioPlataformaAgenda->nombre_servicio,
                'estado_agenda' => $estado_agenda,
            ];
        });

        return $detalle_agenda;
        // dd($agendas);
    }

    public function obtenerEstudiantes()
    {
        $ids_persona_estudiantes = Estudiante::where('estado', true)
            ->where('estado_vigencia', true)
            ->pluck('id_persona');

        return $ids_persona_estudiantes;
    }

    public function obtenerAgendasTitulares($id_institucion)
    {
        $ids_titulares = TitularInstitucion::where('id_institucion', $id_institucion)
            ->where('estado', true)
            ->pluck('id_titular');
        //dd($ids_titulares);
        $idsPersonaTitular = Titular::with('titularConTitularInstitucion.titularInstitucionConInstitucion')
            ->whereIn('id', $ids_titulares)
            ->where('estado_vigencia', '!=', 'NO VIGENTE')
            ->pluck('id_persona');

        // dd($idsPersonaTitular);
        return [
            'id_titular' => $ids_titulares,
            'id_persona_titular' => $idsPersonaTitular,
        ];
    }

    public function obtenerBenefiarios($id_institucion)
    {
        //dd('sdas');
        $resultado = $this->obtenerAgendasTitulares($id_institucion);
        $id_titulares = $resultado['id_titular'];
        $id_persona_beneficiario = Beneficiario::whereIn('id_titular', $id_titulares)
            ->where('estado', true)
            ->where('vigencia', '!=', 'NO VIGENTE')
            ->pluck('id_persona');

        //    ->get();
        return $id_persona_beneficiario;
        //dd($id_persona_beneficiario);
        //$beneficiarios =
    }

    public function obtenerConveniosInterior($id_institucion)
    {
        $id_persona_convenio = AutorizacionInterior::where('id_seguro_interior', $id_institucion)
            ->where('estado', true)
            ->pluck('id_persona_interior');

        //->get();
        return $id_persona_convenio;
        // dd($id_persona_convenio);
    }
}
