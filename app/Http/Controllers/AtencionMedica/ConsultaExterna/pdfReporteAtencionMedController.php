<?php

namespace App\Http\Controllers\AtencionMedica\ConsultaExterna;

use App\Http\Controllers\Controller;
use App\Models\ConsultaExterna\EspecialidadVariable;
use App\Models\ConsultaExterna\Formulario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class pdfReporteAtencionMedController extends Controller
{
    public function pdfOdontologia($id_asig, $fecha)
    {
        $formulario = Formulario::with([
            'formularioDetalleFormulario.DetalleFormularioConsultaExterna.historiaClinicaPaciente'
            ])
            ->where('id_asignacion_horario', $id_asig)
            ->whereDate('created_at', $fecha)
            ->first();

        if (!$formulario) {
            abort(404, 'No se encontró el formulario para esos datos');
        }

        // 1. TRAER TODAS LAS VARIABLES DEFINIDAS (con nombre)
        $variables = EspecialidadVariable::where('id_especialidad', 5)->get();

        // 2. ARMAR LA MATRIZ (agrupa por paciente / consulta)
        $matriz = [];

        foreach ($formulario->formularioDetalleFormulario as $detalle) {

            $consulta   = $detalle->id_consulta_externa;
            $paciente   = $detalle->DetalleFormularioConsultaExterna->historiaClinicaPaciente->s_apellido.' '. 
                            $detalle->DetalleFormularioConsultaExterna->historiaClinicaPaciente->p_apellido.' '.
                            $detalle->DetalleFormularioConsultaExterna->historiaClinicaPaciente->nombres;
            $matricula = $detalle->DetalleFormularioConsultaExterna->historiaClinicaPaciente->matricula_seguro;
            $sexo = $detalle->DetalleFormularioConsultaExterna->historiaClinicaPaciente->sexo;
            $fecha = $detalle->DetalleFormularioConsultaExterna->historiaClinicaPaciente->fecha_nacimiento;
            $edad = Carbon::parse($fecha)->age;

            $varId      = $detalle->id_especialidad_variable;
            $valor      = $detalle->valor;

            // Si aún no existe esta fila en la matriz, la creamos
            if (!isset($matriz[$consulta])) {
                $matriz[$consulta] = [
                    'paciente' => $paciente,
                    'consulta' => $consulta,
                    'matricula' => $matricula,
                    'sexo' => $sexo,
                    'edad' => $edad
                ];
            }

            // Guardamos el valor asignado a la variable
            $matriz[$consulta]['var_'.$varId] = $valor;
        }

        // 3. Enviar la matriz y las variables al PDF
        $pdf = Pdf::loadView('AtencionMedica.FormAtencionMedica.pdfReporteAtencionMed', [
            'formulario' => $formulario,
            'variables'  => $variables,
            'matriz'     => $matriz,
        ])->setPaper('legal', 'landscape');

        return $pdf->stream("reporte-odontologia-{$fecha}.pdf");
    }
}
