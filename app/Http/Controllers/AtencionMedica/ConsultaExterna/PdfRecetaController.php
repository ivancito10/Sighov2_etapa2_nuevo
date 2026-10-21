<?php

namespace App\Http\Controllers\AtencionMedica\ConsultaExterna;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use App\Models\ConsultaExterna\TipoConsulta;
use App\Services\DatosEspecialista;
use App\Services\ServicioPersona;
use App\Services\ServicioPersonaInstitucion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class PdfRecetaController extends Controller
{
    //
    public function mostrarPdf($id_receta)
    {
        //dd('asds');
        try {
            $receta = Receta::findOrFail($id_receta);
            $id_historia = $receta->id_historia_clinica;
            $id_persona = $receta->id_persona;
            $nro_receta = $receta->nro_receta;

            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersona($id_persona);

            $matricula = $datos_persona['matricula_seguro'];
            $apellidos = $datos_persona['p_apellido'] . ' ' . $datos_persona['s_apellido'];

            $path = 'ConsultaExterna/Planes/Receta/'
                . $nro_receta . '_'
                . $id_historia . '_hc_'
                . $matricula . '_'
                . $apellidos . '.pdf';
            //dd($path);
            $file = Storage::disk('ftp')->get($path);

            return response($file, 200)->header('Content-Type', 'application/pdf');
        } catch (\Throwable $th) {
            abort(404, 'PDF no encontrado');
        }
    }

    public function pdfRecetaPreview($rowId)
    {
        //dd('sdasd');
        $ids_array = array_filter(array_map('trim', explode(',', $rowId)));
        $recetas_data = [];
        $descriptivo = null;

        foreach ($ids_array as $id_receta) {
            $receta = Receta::with('usuarioCreador')
                ->find($id_receta);
            //  dd($receta);
            if (! $receta) {
                continue;
            }
            // dd($receta->fecha_proxima_consulta);

            $prescrito_por = $receta->usuarioCreador->name ?? '';
            $created_at = $receta->created_at;
            $id_historia_clinica = $receta->id_historia_clinica;

            $historia_clinica = HistoriaClinica::find($id_historia_clinica);
            // si vienen varias recetas, nos quedamos con el descriptivo del primero que exista
            if ($descriptivo === null) {
                $descriptivo = $historia_clinica->descriptivo ?? null;
            }

            if ($receta->fecha_proxima_consulta != null) {
                $fechaProximaConsulta = Carbon::parse($receta->fecha_proxima_consulta)->format('d/m/Y');
            } else {
                $fechaProximaConsulta = null;
            }

            $id_persona = $receta->id_persona;
            $id_especialista = $receta->id_especialista;

            // Servicio de Especialista
            $dato_esp = new DatosEspecialista();
            $datos_esp = $dato_esp->datosEspecialista($id_especialista);
            $nombres_especialista =
                ($datos_esp['persona_especialista']['p_apellido'] ?? '') . ' ' .
                ($datos_esp['persona_especialista']['s_apellido'] ?? '') . ' ' .
                ($datos_esp['persona_especialista']['nombres'] ?? '');
            $especialidad = $datos_esp['especialidad_especialista']['especialidad'] ?? '';

            // Datos del paciente (según tu servicio)
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);

            $tipo_asegurado = $datos_persona['tipo_asegurado'] ?? null;
            $institucion = $datos_persona['institucion'] ?? null;
            $apellidos = ($datos_persona['p_apellido'] ?? '') . ' ' . ($datos_persona['s_apellido'] ?? '');
            $ci_completo = ($datos_persona['ci'] ?? '') . ($datos_persona['complemento'] ?? '');

            // Datos desde tabla Persona (como tu código)
            $asegurado = Persona::find($id_persona);
            $nombres = trim(($asegurado->p_apellido ?? '') . ' ' . ($asegurado->s_apellido ?? '') . ' ' . ($asegurado->nombres ?? ''));
            $matricula = $asegurado->matricula_seguro ?? null;

            // Instituciones
            $dato_inst = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_inst->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'] ?? [];
            $codigo_institucional = null;

            if (! empty($instituciones) && $institucion) {
                foreach ($instituciones as $institucion_individual) {
                    if (($institucion_individual['nombre_institucion'] ?? null) == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            }

            // Tipo de consulta
            $tipo_consulta = TipoConsulta::find($receta->id_tipo_consulta);
            $nombre_consulta = $tipo_consulta->nombre ?? '';

            // Medicamentos
            $medicamentos = RecetaMedicamento::where('id_receta', $id_receta)->get();

            // QR receta
            $mensajeQR = $receta->clave_unica;
            $qrCode = base64_encode(
                QrCode::format('svg')
                    ->size(300)
                    ->color(0, 0, 0)
                    ->backgroundColor(255, 255, 255)
                    ->errorCorrection('H')
                    ->generate($mensajeQR)
            );

            $recetas_data[] = [
                'nombres' => $nombres,
                'matricula' => $matricula,
                'nro_receta' => $receta->nro_receta,
                'observacion_quirofano' => $receta->observacion_quirofano,
                'nombre_consulta' => $nombre_consulta,
                'fecha_prescripcion' => $receta->fecha_transcripcion,
                'fecha_proxima_consulta' => $receta->fecha_proxima_consulta
                    ? \Carbon\Carbon::parse($receta->fecha_proxima_consulta)->format('d/m/Y')
                    : null,
                'datos' => $medicamentos,
                'nombres_especialista' => $nombres_especialista,
                'especialidad' => $especialidad,
                'prescrito_por' => $prescrito_por,
                'tipo_asegurado' => $tipo_asegurado,
                'institucion' => $institucion,
                'codigo_institucional' => $codigo_institucional,
                'created_at' => $created_at,
                'qrCode' => $qrCode,
                'manual' => $receta->manual,
                'fecha_caducidad' => $receta->fecha_caducidad,
                'ci_completo' => $ci_completo,
                'id_historia_clinica' => $id_historia_clinica,
                'apellidos' => $apellidos,
            ];
        }

        if (empty($recetas_data)) {
            abort(404, 'No se encontraron recetas válidas.');
        }

        $pdf = PDF::loadView('AtencionMedica.ConsultaExterna.pdfReceta', [
            'recetas_data' => $recetas_data,
            'descriptivo' => $descriptivo,
            'fechaProximaConsulta' => 'fechaProximaConsulta'
        ])->setPaper('letter', 'portrait');

        // Nombre de archivo sugerido (solo para el stream)
        $primera = $recetas_data[0];
        $nombre_pdf = ($primera['nro_receta'] ?? 'receta') . '_' .
            ($primera['id_historia_clinica'] ?? '') . '_hc_' .
            ($primera['matricula'] ?? '') . '_' .
            ($primera['apellidos'] ?? '') . '.pdf';

        return $pdf->stream($nombre_pdf, [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }
}
