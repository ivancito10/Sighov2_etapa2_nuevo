<?php

namespace App\Http\Controllers\AtencionMedica\ConsultaExterna;

use App\Http\Controllers\Controller;
use App\Jobs\FtpSaveJob;
use App\Models\Administracion\Persona;
use App\Models\ConsultaExterna\BajaMedica;
use App\Models\ConsultaExterna\Fisioterapia;
use App\Models\ConsultaExterna\HCCie10;
use App\Models\ConsultaExterna\HCPlanes;
use App\Models\ConsultaExterna\HistoriaClinica;
use App\Models\ConsultaExterna\Imagenologia;
use App\Models\ConsultaExterna\ImagenologiaEstudio;
use App\Models\ConsultaExterna\Interconsulta;
use App\Models\ConsultaExterna\Internacion;
use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use App\Models\ConsultaExterna\Referencia;
use App\Models\ConsultaExterna\TipoConsulta;
use App\Models\Laboratorio\Solicitudes;
use App\Models\Laboratorio\SolicitudExamen;
use App\Services\ConsultaExterna\ObtencionQrDocumentos;
use App\Services\DatosEspecialista;
use App\Services\ObtenerDatosEspecialidad;
use App\Services\QRCodeService;
use App\Services\ServicioPersona;
use App\Services\ServicioPersonaInstitucion;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class pdfConsultaExternaController extends Controller
{
    public function pdfReceta($rowId)
    {
        //dd('ESTA ES LA RUTA DE RECETAS DESDE CONSULTA del medico y desde registro manual');
        // este es el pdf que viene de create receta, en farmacia jiji
        $ids_array = explode(',', $rowId);
        $recetas_data = [];

        foreach ($ids_array as $id_receta) {
            $receta = Receta::with('usuarioCreador')
                ->find($id_receta);
            // dd($receta);
            $created_at = $receta->created_at;
            $id_historia_clinica = $receta->id_historia_clinica;
            $estado_manual = $receta->manual;
            // $fecha_prescripcion = $receta->fecha_transcripcion;

            // dd($estado_manual);
            $historia_clinica = HistoriaClinica::find($id_historia_clinica);
           // dd($historia_clinica);
            $descriptivo = $historia_clinica->descriptivo ?? null;

            if (! $receta) {
                continue;
            }

            if ($receta->fecha_proxima_consulta != null) {
                $fechaProximaConsulta = Carbon::parse($receta->fecha_proxima_consulta)->format('d/m/Y');
            } else {
                $fechaProximaConsulta = null;
            }

            $id_persona = $receta->id_persona;
            $id_especialista = $receta->id_especialista;
            $prescrito_por = $receta->usuarioCreador->name ?? '';
            // dd($prescrito_por);
            // Servicio de Especialista
            $dato_esp = new DatosEspecialista();
            $datos_esp = $dato_esp->datosEspecialista($id_especialista);
            $nombres_especialista = $datos_esp['persona_especialista']['p_apellido'] . ' ' . $datos_esp['persona_especialista']['s_apellido'] . ' ' . $datos_esp['persona_especialista']['nombres'];
            $especialidad = $datos_esp['especialidad_especialista']['especialidad'];

            // Datos del paciente
            $dato_per = new ServicioPersona();
            // dd($id_persona);
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);

            if ($datos_persona !== null) {
                $tipo_asegurado = $datos_persona['tipo_asegurado'];
                $institucion = $datos_persona['institucion'];
                $matricula = $datos_persona['matricula_seguro'];
                $apellidos = $datos_persona['p_apellido'] . ' ' . $datos_persona['s_apellido'];
                $ci_completo = $datos_persona['ci'] . ' - ' . $datos_persona['complemento'];

                $asegurado = Persona::find($id_persona);
                $nombres = $asegurado->p_apellido . ' ' . $asegurado->s_apellido . ' ' . $asegurado->nombres;
                $matricula = $asegurado->matricula_seguro;

                // Instituciones
                $dato_inst = new ServicioPersonaInstitucion();
                $persona_institucion = $dato_inst->personaInstitucion($id_persona);
                $instituciones = $persona_institucion['instituciones'];
                $codigo_institucional = null;
                if (! empty($instituciones)) {
                    foreach ($instituciones as $institucion_individual) {
                        if ($institucion_individual['nombre_institucion'] == $institucion) {
                            $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                            break;
                        }
                    }
                }
            } else {
                //'entra por aqui';
                $asegurado = Persona::with(['tipoAseguradoPersona'])->find($id_persona);

                $tipo_asegurado = $asegurado->tipoAseguradoPersona->tipo_asegurado;
                $institucion = '-';
                $matricula = $asegurado->matricula_seguro;
                $apellidos = implode(' ', array_filter([$asegurado->p_apellido, $asegurado->s_apellido]));
                $nombres = implode(' ', array_filter([$asegurado->p_apellido, $asegurado->s_apellido, $asegurado->nombres]));
                $ci_completo = implode(' - ', array_filter([$asegurado->ci, $asegurado->complemento]));
                $codigo_institucional = '';
            }

            //dd($codigo_institucional);
            // Tipo de consulta
            $tipo_consulta = TipoConsulta::find($receta->id_tipo_consulta);
            $nombre_consulta = $tipo_consulta->nombre ?? '';

            // Medicamentos
            $medicamentos = RecetaMedicamento::where('id_receta', $id_receta)->get();

            //qr receta
            $mensajeQR = $receta->clave_unica;
            $qrCode = base64_encode(QrCode::format('svg')
                ->size(300)
                ->color(0, 0, 0)
                //->margin(1) // Margen adicional
                ->backgroundColor(255, 255, 255)
                ->errorCorrection('H')
                ->generate($mensajeQR));

            $recetas_data[] = [
                'nombres' => $nombres,
                'matricula' => $matricula,
                'nro_receta' => $receta->nro_receta,
                'observacion_quirofano' => $receta->observacion_quirofano,
                'nombre_consulta' => $nombre_consulta,
                'fecha_prescripcion' => $receta->fecha_transcripcion,
                'fecha_proxima_consulta' => $receta->fecha_proxima_consulta
                    ? Carbon::parse($receta->fecha_proxima_consulta)->format('d/m/Y')
                    : null,
                'datos' => $medicamentos,
                'prescrito_por' => $prescrito_por,
                'nombres_especialista' => $nombres_especialista,
                'especialidad' => $especialidad,
                'tipo_asegurado' => $tipo_asegurado,
                'institucion' => $institucion,
                'codigo_institucional' => $codigo_institucional,
                'created_at' => $created_at,
                'qrCode' => $qrCode,
                'manual' => $receta->manual,
                'fecha_caducidad' => $receta->fecha_caducidad,
                'ci_completo' => $ci_completo,
            ];
        }

        if (empty($recetas_data)) {
            abort(404, 'No se encontraron recetas válidas.');
        }

        $pdf = PDF::loadView('AtencionMedica.ConsultaExterna.pdfReceta', [
            'recetas_data' => $recetas_data,
            'descriptivo' => $descriptivo,
            'fechaProximaConsulta' => $fechaProximaConsulta,
        ])->setPaper('letter', 'portrait');

        //if($estado_manual){

        // Guardar en el FTP, Obtener el contenido binario del PDF
        $pdfContent = $pdf->output();
        $fileName = $receta->nro_receta . '_' . $id_historia_clinica . '_hc_' . $matricula . '_' . $apellidos . '.pdf';
        $filePath = 'ConsultaExterna/Planes/Receta/' . $fileName;
        // try {
        //     //dd('dentra',$filePath);
        //     $success = Storage::disk('ftp')->put($filePath, $pdfContent);
        //     if (! $success) {
        //         throw new \Exception('No se pudo guardar el archivo en el FTP.');
        //     }
        // } catch (\Exception $e) {
        //     dd($e);
        //     Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

        //     return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
        // }
        //}

        $nombre_pdf = $receta->nro_receta . '_' . $id_historia_clinica . '_hc_' . $matricula . '_' . $apellidos . '.pdf';
        // * AQUI GUARDAMOS EL PDF EN EL FTP EN SEGUNDO PLANO*
        try {
            // Intentamos verificar la conexión a Redis
            Redis::connection()->ping();

            // Si hay conexión, usamos el Job (Asíncrono)
            FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
        } catch (\Exception $e) {
            // Si Redis está caído, lo hacemos de la manera tradicional (Sincrónico)
            Log::warning('Redis está fuera de servicio. Procesando subida FTP sincrónicamente.');

            Storage::disk('ftp')->put($filePath, $pdfContent);
        }

        return $pdf->stream($nombre_pdf, [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }

    public function pdfRecetaFtp($rowId)
    {
        //dd('llega');
        $ids_array = explode(',', $rowId);
        $recetas_data = [];
        //$id_nro_recetas = [];

        foreach ($ids_array as $id_receta) {
            $receta = Receta::find($id_receta);
            $created_at = $receta->created_at;
            $id_historia_clinica = $receta->id_historia_clinica;
            // $fecha_prescripcion = $receta->fecha_transcripcion;
            // dd($fecha_prescripcion);

            $historia_clinica = HistoriaClinica::find($id_historia_clinica);
            $descriptivo = $historia_clinica->descriptivo;

            if (! $receta) {
                continue;
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
            $nombres_especialista = $datos_esp['persona_especialista']['p_apellido'] . ' ' . $datos_esp['persona_especialista']['s_apellido'] . ' ' . $datos_esp['persona_especialista']['nombres'];
            $especialidad = $datos_esp['especialidad_especialista']['especialidad'];

            // Datos del paciente
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'];
            $institucion = $datos_persona['institucion'];
            $matricula = $datos_persona['matricula_seguro'];
            $apellidos = $datos_persona['p_apellido'] . '_' . $datos_persona['s_apellido'];
            $ci_completo = $datos_persona['ci'] . '' . $datos_persona['complemento'];

            $asegurado = Persona::find($id_persona);
            $nombres = $asegurado->p_apellido . ' ' . $asegurado->s_apellido . ' ' . $asegurado->nombres;
            $matricula = $asegurado->matricula_seguro;

            // Instituciones
            $dato_inst = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_inst->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'];

            $codigo_institucional = null;
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $codigo_institucional = null;
            }

            // Tipo de consulta
            $tipo_consulta = TipoConsulta::find($receta->id_tipo_consulta);
            $nombre_consulta = $tipo_consulta->nombre ?? '';

            // Medicamentos
            $medicamentos = RecetaMedicamento::where('id_receta', $id_receta)->get();

            //$id_nro_recetas[] = $receta->nro_receta;
            //qr receta
            $mensajeQR = $receta->clave_unica;
            $qrCode = base64_encode(QrCode::format('svg')
                ->size(300)
                ->color(0, 0, 0)
                //->margin(1) // Margen adicional
                ->backgroundColor(255, 255, 255)
                ->errorCorrection('H')
                ->generate($mensajeQR));

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
                'tipo_asegurado' => $tipo_asegurado,
                'institucion' => $institucion,
                'codigo_institucional' => $codigo_institucional,
                'created_at' => $created_at,
                'qrCode' => $qrCode,
                'manual' => $receta->manual,
                'fecha_caducidad' => $receta->fecha_caducidad,
                'ci_completo' => $ci_completo,
            ];
        }
        //dd($recetas_data);
        if (empty($recetas_data)) {
            abort(404, 'No se encontraron recetas válidas.');
        }

        $pdf = PDF::loadView('AtencionMedica.ConsultaExterna.pdfReceta', [
            'recetas_data' => $recetas_data,
            'descriptivo' => $descriptivo,
            'fechaProximaConsulta' => $fechaProximaConsulta,
        ])->setPaper('letter', 'portrait');

        //$nro_recetas_string = implode('-', $id_nro_recetas);

        // Guardar en el FTP, Obtener el contenido binario del PDF
        $pdfContent = $pdf->output();
        $fileName = $receta->nro_receta . '_' . $id_historia_clinica . '_hc_' . $matricula . '_' . $apellidos . '.pdf';
        $filePath = 'ConsultaExterna/Planes/Receta/' . $fileName;
        // try {
        //     $success = Storage::disk('ftp')->put($filePath, $pdfContent);
        //     if (! $success) {
        //         throw new \Exception('No se pudo guardar el archivo en el FTP.');
        //     }
        // } catch (\Exception $e) {
        //     Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

        //     return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
        // }

        $nombre_pdf = $receta->nro_receta . '_' . $id_historia_clinica . '_hc_' . $matricula . '_' . $apellidos . '.pdf';
        // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
        // * AQUI GUARDAMOS EL PDF EN EL FTP EN SEGUNDO PLANO*
        try {
            // Intentamos verificar la conexión a Redis
            Redis::connection()->ping();

            // Si hay conexión, usamos el Job (Asíncrono)
            FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
        } catch (\Exception $e) {
            // Si Redis está caído, lo hacemos de la manera tradicional (Sincrónico)
            Log::warning('Redis está fuera de servicio. Procesando subida FTP sincrónicamente.');

            Storage::disk('ftp')->put($filePath, $pdfContent);
        }

        return $pdf->download($nombre_pdf, [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }

    public function pdfImagenologia($rowId)
    {

        $nombres = '';
        $imagenologia = Imagenologia::find($rowId);
        if ($imagenologia) {
            $nro_solicitud = $imagenologia->nro_solicitud;
            $id = $rowId;

            $hc_planes = HCPlanes::where('id_plan', 3)
                ->where('id_corresponde', $id)
                ->first();

            $historia_clinica = HistoriaClinica::find($hc_planes->id_historia_clinica);
            $id_especialista = $historia_clinica->id_especialista;
            $id_persona = $historia_clinica->id_persona;

            // Servicio para mostrar datos del especialista
            $dato_esp = new DatosEspecialista();
            $datos = $dato_esp->datosEspecialista($id_especialista);
            $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
            $especialidad = $datos['especialidad_especialista']['especialidad'];

            // Servicio para mostrar datos tipo asegurado e institucion del asegurado
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'];
            $institucion = $datos_persona['institucion'];

            // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'];
            $nro_instituciones = count($instituciones);
            $codigo_institucional = null;
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $codigo_institucional = null;
            }

            $imagenologia_estudio = ImagenologiaEstudio::where('id_sol_imagenologia', $id)->get();

            $hc_cie10 = HCCie10::where('id_historia_clinica', $historia_clinica->id)->get();

            // Para abrir el blade vista del pdf con variables ya definidas
            $pdf = PDF::loadView(
                'AtencionMedica.ConsultaExterna.pdfImagenologia',
                compact(
                    'id',
                    'imagenologia',
                    'hc_planes',
                    'historia_clinica',
                    'nombre_especialista',
                    'especialidad',
                    'imagenologia_estudio',
                    'hc_cie10',
                    'tipo_asegurado',
                    'institucion',
                    'codigo_institucional'
                )
            )
                ->setPaper('letter', 'portrait');

            // Guardar en el FTP, Obtener el contenido binario del PDF
            $pdfContent = $pdf->output();
            $fileName = 'orden_imagenologia_' . $rowId . '.pdf';
            $filePath = 'ConsultaExterna/Planes/Imagenologia/' . $fileName;
            try {
                $success = Storage::disk('ftp')->put($filePath, $pdfContent);

                if (! $success) {
                    throw new \Exception('No se pudo guardar el archivo en el FTP.');
                }
            } catch (\Exception $e) {
                Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

                return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
            }

            $nombre_pdf = 'orden_imagenologia_' . $rowId;

            // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
            // Para descargar pdf
            return $pdf->stream($nombre_pdf . '.pdf', [
                'Attachment' => false,
                'Content-Disposition' => 'inline',
            ]);
        }
    }

    public function pdfBajaMedica($rowId)
    {
        // este pdf se genera en el menu de bajas medicas y cuando genera el medico
        $año = Carbon::now()->format('Y');
        $id = $rowId;
        $baja_medica = BajaMedica::find($id);
        if ($baja_medica) {
            $fecha_creacion = $baja_medica->created_at;
            //dd($fecha_creacion);
            $hc_planes = HCPlanes::where('id_plan', 4)
                ->where('id_corresponde', $id)
                ->first();

            $id_historia_clinica = $hc_planes->id_historia_clinica;

            $historia_clinica = HistoriaClinica::find($id_historia_clinica);
            $id_persona = $historia_clinica->id_persona;
            $id_especialista = $historia_clinica->id_especialista;

            // Servicio para mostrar datos del especialista
            $dato_esp = new DatosEspecialista();
            $datos = $dato_esp->datosEspecialista($id_especialista);
            $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
            $especialidad = $datos['especialidad_especialista']['especialidad'];

            // Servicio para mostrar datos tipo asegurado e institucion del asegurado
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
            //dd($datos_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'];
            $institucion = $datos_persona['institucion'];
            $ci = $datos_persona['ci'] . ' ' . $datos_persona['complemento'];
            $foto_asegurado = $datos_persona['url_foto'];

            // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'];
            $nro_instituciones = count($instituciones);
            //dd($persona_institucion);

            $codigo_institucional = null;
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $codigo_institucional = null;
            }

            $hc_cie10 = HCCie10::where('id_historia_clinica', $historia_clinica->id)->get();

            // Para generar QR
            $fechaQR = $baja_medica->created_at;
            $codigos = QRCodeService::generarQR($fechaQR);
            $cod_seguridad = $codigos['cod_seguridad'];
            $qrCode = $codigos['qrCode'];

            // Para abrir el blade vista del pdf con variables ya definidas
            $pdf = PDF::loadView(
                'AtencionMedica.ConsultaExterna.pdfBajaMedica',
                compact(
                    'id',
                    'fecha_creacion',
                    'baja_medica',
                    'historia_clinica',
                    'nombre_especialista',
                    'especialidad',
                    'institucion',
                    'tipo_asegurado',
                    'hc_cie10',
                    'hc_planes',
                    'foto_asegurado',
                    'instituciones',
                    'nro_instituciones',
                    'codigo_institucional',
                    'qrCode'

                )
            )
                ->setPaper('letter', 'portrait');
            QRCodeService::registrarPDF($pdf, $cod_seguridad, $id_persona, 16, 'CONSULTA EXTERNA');

            // Guardar en el FTP, Obtener el contenido binario del PDF
            $pdfContent = $pdf->output();
            $fileName = 'orden_baja_medica_' . $rowId . '.pdf';
            $filePath = 'ConsultaExterna/Planes/BajaMedica/' . $fileName;
            try {
                $success = Storage::disk('ftp')->put($filePath, $pdfContent);
                if (! $success) {
                    throw new \Exception('No se pudo guardar el archivo en el FTP.');
                }
            } catch (\Exception $e) {
                Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

                return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
            }

            $nombre_pdf = 'BM/Nº/' . $baja_medica->nro_baja_medica . '/' . $año;

            // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
            // Para ver pdf
            return $pdf->stream($nombre_pdf . '.pdf', [
                'Attachment' => false,
                'Content-Disposition' => 'inline',
            ]);
        }
    }

    public function pdfLaboratorio($rowId)
    {

        //dd($rowId);
        $id = $rowId;
        $solicitudes = Solicitudes::find($id);
        //dd($solicitudes);

        if ($solicitudes) {
            $historia_clinica = HistoriaClinica::find($solicitudes->id_historia_clinica);
            $id_persona = $historia_clinica->id_persona;
            $id_especialista = $historia_clinica->id_especialista;

            $solicitud_examen = SolicitudExamen::where('id_solicitudes', $solicitudes->id)->get();
            //dd($solicitud_examen);
            $tipo_laboratorio = $solicitud_examen[0]->tipo_laboratorio ?? null;

            $hc_planes = HCPlanes::where('id_plan', 2)
                ->where('id_corresponde', $id)
                ->first();

            $hc_cie10 = HCCie10::where('id_historia_clinica', $historia_clinica->id)->get();

            //dd($tipo_laboratorio);
            // Servicio para mostrar datos del especialista
            $dato_esp = new DatosEspecialista();
            $datos = $dato_esp->datosEspecialista($id_especialista);
            // dd($datos ,$id_especialista);
            // $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
            // $especialidad = $datos['especialidad_especialista']['especialidad'];
            $persona = $datos['persona_especialista'] ?? [];
            $especialidadData = $datos['especialidad_especialista'] ?? [];

            $nombre_especialista =
                ($persona['p_apellido'] ?? '') . ' ' .
                ($persona['s_apellido'] ?? '') . ' ' .
                ($persona['nombres'] ?? '');

            $especialidad = $especialidadData['especialidad'] ?? '';
            // Servicio para mostrar datos tipo asegurado e institucion del asegurado
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
            //dd($datos_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'] ?? '';
            $institucion = $datos_persona['institucion'] ?? '';

            // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'] ?? [];
            $nro_instituciones = count($instituciones);
            $codigo_institucional = null;
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $codigo_institucional = null;
            }
        }

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = PDF::loadView(
            'AtencionMedica.ConsultaExterna.pdfLaboratorio',
            compact(
                'id',
                'solicitudes',
                'nombre_especialista',
                'especialidad',
                'datos_persona',
                'codigo_institucional',
                'solicitud_examen',
                'tipo_laboratorio',
                'hc_planes',
                'hc_cie10',
                'historia_clinica'
            )
        )
            ->setPaper('letter', 'portrait');

        // Guardar en el FTP, Obtener el contenido binario del PDF
        $pdfContent = $pdf->output();
        $fileName = 'orden_laboratorio_' . $rowId . '.pdf';
        $filePath = 'ConsultaExterna/Planes/Laboratorio/' . $fileName;
        try {
            $success = Storage::disk('ftp')->put($filePath, $pdfContent);

            if (! $success) {
                throw new \Exception('No se pudo guardar el archivo en el FTP.');
            }
        } catch (\Exception $e) {
            Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

            return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
        }

        $nombre_pdf = 'orden_laboratorio_' . $rowId;

        // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
        // Para descargar pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }

    public function pdfFisioterapia($rowId)
    {

        $id = $rowId;
        $fisioterapia = Fisioterapia::find($id);
        if ($fisioterapia) {

            $hc_planes = HCPlanes::where('id_plan', 5)
                ->where('id_corresponde', $id)
                ->first();

            $id_historia_clinica = $hc_planes->id_historia_clinica;

            $historia_clinica = HistoriaClinica::find($id_historia_clinica);
            $id_persona = $historia_clinica->id_persona;
            $id_especialista = $historia_clinica->id_especialista;

            // Servicio para mostrar datos del especialista
            $dato_esp = new DatosEspecialista();
            $datos = $dato_esp->datosEspecialista($id_especialista);
            $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
            $especialidad = $datos['especialidad_especialista']['especialidad'];

            // Servicio para mostrar datos tipo asegurado e institucion del asegurado
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'];
            $institucion = $datos_persona['institucion'];

            // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'];
            $nro_instituciones = count($instituciones);

            $codigo_institucional = null;
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $codigo_institucional = null;
            }

            $hc_cie10 = HCCie10::where('id_historia_clinica', $historia_clinica->id)->get();

            // Para abrir el blade vista del pdf con variables ya definidas
            $pdf = PDF::loadView(
                'AtencionMedica.ConsultaExterna.pdfFisioterapia',
                compact(
                    'id',
                    'fisioterapia',
                    'hc_planes',
                    'historia_clinica',
                    'nombre_especialista',
                    'especialidad',
                    'tipo_asegurado',
                    'institucion',
                    'hc_cie10',
                    'codigo_institucional'
                )
            )
                ->setPaper('letter', 'portrait');

            // Guardar en el FTP, Obtener el contenido binario del PDF
            $pdfContent = $pdf->output();
            $fileName = 'orden_fisioterapia_' . $rowId . '.pdf';
            $filePath = 'ConsultaExterna/Planes/Fisioterapia/' . $fileName;
            try {
                $success = Storage::disk('ftp')->put($filePath, $pdfContent);

                if (! $success) {
                    throw new \Exception('No se pudo guardar el archivo en el FTP.');
                }
            } catch (\Exception $e) {
                Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

                return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
            }

            $nombre_pdf = 'orden_fisioterapia_' . $rowId;

            // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
            // Para descargar pdf
            return $pdf->stream($nombre_pdf . '.pdf', [
                'Attachment' => false,
                'Content-Disposition' => 'inline',
            ]);
        }
    }

    public function pdfInternacion($rowId)
    {

        $id = $rowId;

        $internacion = Internacion::find($id);
        if ($internacion) {

            $hc_planes = HCPlanes::where('id_plan', 6)
                ->where('id_corresponde', $id)
                ->first();

            $id_historia_clinica = $hc_planes->id_historia_clinica;

            $historia_clinica = HistoriaClinica::find($id_historia_clinica);
            $id_persona = $historia_clinica->id_persona;
            $id_especialista = $historia_clinica->id_especialista;

            // Datos de Referencia
            $hc_planes_ref = HCPlanes::where('id_historia_clinica', $id_historia_clinica)
                ->where('id_plan', 7)
                ->first();

            $especialidad_ref = '';
            $datos_ref = '';
            if ($hc_planes_ref) {
                $id_referencia = $hc_planes_ref->id_corresponde;
                $referencia = Referencia::find($id_referencia);
                // Servicio para mostrar datos de la especialidad registrada
                $dato_esp_ref = new ObtenerDatosEspecialidad();
                $datos_ref = $dato_esp_ref->datosEspecialidad($referencia->id_especialidad);
                $especialidad_ref = $datos_ref['especialidad'];
            } else {
                $referencia = null;
            }

            // Datos de Fisioterapia
            $hc_planes_fisio = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 5)
                ->first();

            if ($hc_planes_fisio) {
                $id_fisioterapia = $hc_planes_fisio->id_corresponde;
                $fisioterapia = Fisioterapia::find($id_fisioterapia);
            } else {
                $fisioterapia = null;
            }

            // Datos de Baja Medica
            $hc_planes_baja = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 4)
                ->first();

            if ($hc_planes_baja) {
                $id_baja_medica = $hc_planes_baja->id_corresponde;
                $baja_medica = BajaMedica::find($id_baja_medica);
            } else {
                $baja_medica = null;
            }

            // Datos de Imagenologia
            $hc_planes_ima = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 3)
                ->first();

            $imagenologia_estudio = [];
            if ($hc_planes_ima) {
                $id_imagenologia = $hc_planes_ima->id_corresponde;
                $imagenologia = Imagenologia::find($id_imagenologia);
                $imagenologia_estudio = ImagenologiaEstudio::where('id_sol_imagenologia', $id_imagenologia)->get();
            } else {
                $imagenologia = null;
            }

            // Datos de Receta
            $hc_planes_receta = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 1)
                ->first();

            $receta_medicamento = [];
            if ($hc_planes_receta) {
                $id_receta = $hc_planes_receta->id_corresponde;
                $receta = Receta::find($id_receta);
                $receta_medicamento = RecetaMedicamento::where('id_receta', $id_receta)->get();
            } else {
                $receta = null;
            }

            // Datos de Laboratorio

            $laboratorio = Solicitudes::where('id_historia_clinica', $id_historia_clinica)->first();

            $solicitud_examen = [];
            $tipo_laboratorio = '';
            if ($laboratorio) {
                $solicitud_examen = SolicitudExamen::where('id_solicitudes', $laboratorio->id)->get();
                $tipo_laboratorio = $solicitud_examen[0]->tipo_laboratorio ?? null;
            } else {
                $laboratorio = null;
            }

            // Servicio para mostrar datos del especialista
            $dato_esp = new DatosEspecialista();
            $datos = $dato_esp->datosEspecialista($id_especialista);
            $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
            $especialidad = $datos['especialidad_especialista']['especialidad'];

            // Servicio para mostrar datos tipo asegurado e institucion del asegurado
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'];
            $institucion = $datos_persona['institucion'];

            // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'];
            $nro_instituciones = count($instituciones);
            $codigo_institucional = null;
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $codigo_institucional = null;
            }

            $hc_cie10 = HCCie10::where('id_historia_clinica', $historia_clinica->id)->get();

            $pdf = PDF::loadView(
                'AtencionMedica.ConsultaExterna.pdfInternacion',
                compact(
                    'id',
                    'internacion',
                    'historia_clinica',
                    'hc_planes',
                    'institucion',
                    'tipo_asegurado',
                    'nombre_especialista',
                    'especialidad',
                    'hc_cie10',
                    'codigo_institucional',
                    'fisioterapia',
                    'baja_medica',
                    'imagenologia',
                    'imagenologia_estudio',
                    'receta',
                    'receta_medicamento',
                    'laboratorio',
                    'solicitud_examen',
                    'tipo_laboratorio',
                    'especialidad_ref',
                    'referencia',
                    'datos_ref'
                )
            )
                ->setPaper('letter', 'portrait');

            // Guardar en el FTP, Obtener el contenido binario del PDF
            $pdfContent = $pdf->output();
            $fileName = 'orden_internacion_' . $rowId . '.pdf';
            $filePath = 'ConsultaExterna/Planes/Internacion/' . $fileName;
            try {
                $success = Storage::disk('ftp')->put($filePath, $pdfContent);

                if (! $success) {
                    throw new \Exception('No se pudo guardar el archivo en el FTP.');
                }
            } catch (\Exception $e) {
                Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

                return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
            }

            $nombre_pdf = 'orden_internacion_' . $rowId;

            // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
            // Para descargar pdf
            return $pdf->stream($nombre_pdf . '.pdf', [
                'Attachment' => false,
                'Content-Disposition' => 'inline',
            ]);
        }
    }

    public function pdfReferencia($rowId)
    {

        $id = $rowId;
        $referencia = Referencia::find($id);
        if ($referencia) {

            $hc_planes = HCPlanes::where('id_plan', 7)
                ->where('id_corresponde', $id)
                ->first();

            $id_historia_clinica = $hc_planes->id_historia_clinica;

            $historia_clinica = HistoriaClinica::find($id_historia_clinica);
            $id_persona = $historia_clinica->id_persona;
            $id_especialista = $historia_clinica->id_especialista;

            $hc_cie10 = HCCie10::where('id_historia_clinica', $historia_clinica->id)->get();

            // Datos de Internación
            $hc_planes_inter = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 6)
                ->first();

            if ($hc_planes_inter) {
                $id_internacion = $hc_planes_inter->id_corresponde;
                $internacion = Internacion::find($id_internacion);
            } else {
                $internacion = null;
            }

            // Datos de Fisioterapia
            $hc_planes_fisio = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 5)
                ->first();

            if ($hc_planes_fisio) {
                $id_fisioterapia = $hc_planes_fisio->id_corresponde;
                $fisioterapia = Fisioterapia::find($id_fisioterapia);
            } else {
                $fisioterapia = null;
            }

            // Datos de Baja Medica
            $hc_planes_baja = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 4)
                ->first();

            if ($hc_planes_baja) {
                $id_baja_medica = $hc_planes_baja->id_corresponde;
                $baja_medica = BajaMedica::find($id_baja_medica);
            } else {
                $baja_medica = null;
            }

            // Datos de Imagenologia
            $hc_planes_ima = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 3)
                ->first();

            $imagenologia_estudio = [];
            if ($hc_planes_ima) {
                $id_imagenologia = $hc_planes_ima->id_corresponde;
                $imagenologia = Imagenologia::find($id_imagenologia);
                $imagenologia_estudio = ImagenologiaEstudio::where('id_sol_imagenologia', $id_imagenologia)->get();
            } else {
                $imagenologia = null;
            }

            // Datos de Receta
            $hc_planes_receta = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 1)
                ->first();

            $receta_medicamento = [];
            if ($hc_planes_receta) {
                $id_receta = $hc_planes_receta->id_corresponde;
                $receta = Receta::find($id_receta);
                $receta_medicamento = RecetaMedicamento::where('id_receta', $id_receta)->get();
            } else {
                $receta = null;
            }

            // Datos de Laboratorio

            $laboratorio = Solicitudes::where('id_historia_clinica', $id_historia_clinica)->first();

            $solicitud_examen = [];
            $tipo_laboratorio = '';
            if ($laboratorio) {
                $solicitud_examen = SolicitudExamen::where('id_solicitudes', $laboratorio->id)->get();
                $tipo_laboratorio = $solicitud_examen[0]->tipo_laboratorio ?? null;
            } else {
                $laboratorio = null;
            }

            // Servicio para mostrar datos de la especialidad registrada
            $dato_esp_ref = new ObtenerDatosEspecialidad();
            $datos_ref = $dato_esp_ref->datosEspecialidad($referencia->id_especialidad);
            $especialidad_ref = $datos_ref['especialidad'];

            // Servicio para mostrar datos del especialista
            $dato_esp = new DatosEspecialista();
            $datos = $dato_esp->datosEspecialista($id_especialista);
            $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
            $especialidad = $datos['especialidad_especialista']['especialidad'];

            // Servicio para mostrar datos tipo asegurado e institucion del asegurado
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'];
            $institucion = $datos_persona['institucion'];

            // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'];
            $nro_instituciones = count($instituciones);
            $codigo_institucional = null;
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $codigo_institucional = null;
            }
        }

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = PDF::loadView(
            'AtencionMedica.ConsultaExterna.pdfReferencia',
            compact(
                'id',
                'nombre_especialista',
                'especialidad',
                'historia_clinica',
                'institucion',
                'tipo_asegurado',
                'hc_cie10',
                'referencia',
                'hc_planes',
                'codigo_institucional',
                'especialidad_ref',
                'internacion',
                'fisioterapia',
                'baja_medica',
                'imagenologia',
                'imagenologia_estudio',
                'receta',
                'receta_medicamento',
                'laboratorio',
                'solicitud_examen',
                'tipo_laboratorio'
            )
        )
            ->setPaper('letter', 'portrait');

        // Guardar en el FTP, Obtener el contenido binario del PDF
        $pdfContent = $pdf->output();
        $fileName = 'orden_referencia_' . $rowId . '.pdf';
        $filePath = 'ConsultaExterna/Planes/Referencia/' . $fileName;
        try {
            $success = Storage::disk('ftp')->put($filePath, $pdfContent);

            if (! $success) {
                throw new \Exception('No se pudo guardar el archivo en el FTP.');
            }
        } catch (\Exception $e) {
            Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

            return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
        }

        $nombre_pdf = 'orden_referencia_' . $rowId;

        // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
        // Para descargar pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }

    public function pdfInterconsulta($rowId)
    {

        $id = $rowId;
        $interconsulta = Interconsulta::find($id);
        if ($interconsulta) {

            $hc_planes = HCPlanes::where('id_plan', 8)
                ->where('id_corresponde', $id)
                ->first();

            $id_historia_clinica = $hc_planes->id_historia_clinica;

            $historia_clinica = HistoriaClinica::find($id_historia_clinica);
            $id_persona = $historia_clinica->id_persona;
            $id_especialista = $historia_clinica->id_especialista;

            $hc_cie10 = HCCie10::where('id_historia_clinica', $historia_clinica->id)->get();

            // Datos de Internación
            $hc_planes_inter = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 6)
                ->first();

            if ($hc_planes_inter) {
                $id_internacion = $hc_planes_inter->id_corresponde;
                $internacion = Internacion::find($id_internacion);
            } else {
                $internacion = null;
            }

            // Datos de Fisioterapia
            $hc_planes_fisio = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 5)
                ->first();

            if ($hc_planes_fisio) {
                $id_fisioterapia = $hc_planes_fisio->id_corresponde;
                $fisioterapia = Fisioterapia::find($id_fisioterapia);
            } else {
                $fisioterapia = null;
            }

            // Datos de Baja Medica
            $hc_planes_baja = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 4)
                ->first();

            if ($hc_planes_baja) {
                $id_baja_medica = $hc_planes_baja->id_corresponde;
                $baja_medica = BajaMedica::find($id_baja_medica);
            } else {
                $baja_medica = null;
            }

            // Datos de Imagenologia
            $hc_planes_ima = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 3)
                ->first();

            $imagenologia_estudio = [];
            if ($hc_planes_ima) {
                $id_imagenologia = $hc_planes_ima->id_corresponde;
                $imagenologia = Imagenologia::find($id_imagenologia);
                $imagenologia_estudio = ImagenologiaEstudio::where('id_sol_imagenologia', $id_imagenologia)->get();
            } else {
                $imagenologia = null;
            }

            // Datos de Receta
            $hc_planes_receta = HCPlanes::where('id_historia_clinica', $historia_clinica->id)
                ->where('id_plan', 1)
                ->first();

            $receta_medicamento = [];
            if ($hc_planes_receta) {
                $id_receta = $hc_planes_receta->id_corresponde;
                $receta = Receta::find($id_receta);
                $receta_medicamento = RecetaMedicamento::where('id_receta', $id_receta)->get();
            } else {
                $receta = null;
            }

            // Datos de Laboratorio

            $laboratorio = Solicitudes::where('id_historia_clinica', $id_historia_clinica)->first();

            $solicitud_examen = [];
            $tipo_laboratorio = '';
            if ($laboratorio) {
                $solicitud_examen = SolicitudExamen::where('id_solicitudes', $laboratorio->id)->get();
                $tipo_laboratorio = $solicitud_examen[0]->tipo_laboratorio ?? null;
            } else {
                $laboratorio = null;
            }

            // Servicio para mostrar datos de la especialidad registrada
            $dato_esp_ref = new ObtenerDatosEspecialidad();
            $datos_ref = $dato_esp_ref->datosEspecialidad($interconsulta->id_especialidad);
            $especialidad_ref = $datos_ref['especialidad'];

            // Servicio para mostrar datos del especialista
            $dato_esp = new DatosEspecialista();
            $datos = $dato_esp->datosEspecialista($id_especialista);
            $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
            $especialidad = $datos['especialidad_especialista']['especialidad'];

            // Servicio para mostrar datos tipo asegurado e institucion del asegurado
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'];
            $institucion = $datos_persona['institucion'];

            // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);
            $instituciones = $persona_institucion['instituciones'];
            $nro_instituciones = count($instituciones);
            $codigo_institucional = null;
            if (! empty($instituciones)) {
                foreach ($instituciones as $institucion_individual) {
                    if ($institucion_individual['nombre_institucion'] == $institucion) {
                        $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                        break;
                    }
                }
            } else {
                $codigo_institucional = null;
            }
        }

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = PDF::loadView(
            'AtencionMedica.ConsultaExterna.pdfInterconsulta',
            compact(
                'id',
                'nombre_especialista',
                'especialidad',
                'historia_clinica',
                'institucion',
                'tipo_asegurado',
                'hc_cie10',
                'interconsulta',
                'hc_planes',
                'codigo_institucional',
                'especialidad_ref',
                'internacion',
                'fisioterapia',
                'baja_medica',
                'imagenologia',
                'imagenologia_estudio',
                'receta',
                'receta_medicamento',
                'laboratorio',
                'solicitud_examen',
                'tipo_laboratorio'
            )
        )
            ->setPaper('letter', 'portrait');

        // Guardar en el FTP, Obtener el contenido binario del PDF
        $pdfContent = $pdf->output();
        $fileName = 'orden_interconsulta_' . $rowId . '.pdf';
        $filePath = 'ConsultaExterna/Planes/Interconsulta/' . $fileName;
        try {
            $success = Storage::disk('ftp')->put($filePath, $pdfContent);

            if (! $success) {
                throw new \Exception('No se pudo guardar el archivo en el FTP.');
            }
        } catch (\Exception $e) {
            Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

            return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
        }

        $nombre_pdf = 'orden_interconsulta_' . $rowId;

        // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }

    public function pdfConsultaExterna($rowId)
    {
        // dd('sasds');
        // 1. Capturar las coordenadas enviadas por la URL (o usar 15mm por defecto si no vienen)
        // $marginTop = $request->query('top', 15);
        // $marginLeft = $request->query('left', 15);
        // ------------------------------------------------------------------------------------------------------------
        //  dd('este es el original');
        $id_historia_clinica = $rowId;
        $historia_clinica = HistoriaClinica::with('signosVitales')->findOrFail($id_historia_clinica);
        $id_persona = $historia_clinica->id_persona;
        $id_especialista = $historia_clinica->id_especialista;

        $hc_cie10 = HCCie10::where('id_historia_clinica', $id_historia_clinica)->get();

        // Datos de Interconsulta
        $hc_planes_inter = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 8)
            ->first();

        $especialidad_inter = '';
        if ($hc_planes_inter) {
            $id_interconsulta = $hc_planes_inter->id_corresponde;
            $interconsulta = Interconsulta::find($id_interconsulta);

            // Servicio para mostrar datos de la especialidad registrada
            $dato_esp_ref = new ObtenerDatosEspecialidad();
            $datos_ref = $dato_esp_ref->datosEspecialidad($interconsulta->id_especialidad);
            $especialidad_inter = $datos_ref['especialidad'];
            //dd($especialidad_inter);
        } else {
            $interconsulta = null;
        }

        // Datos de Referencia
        $hc_planes_ref = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 7)
            ->first();

        $especialidad_ref = '';
        if ($hc_planes_ref) {
            $id_referencia = $hc_planes_ref->id_corresponde;
            $referencia = Referencia::find($id_referencia);
            // Servicio para mostrar datos de la especialidad registrada
            $dato_esp_ref = new ObtenerDatosEspecialidad();
            $datos_ref = $dato_esp_ref->datosEspecialidad($referencia->id_especialidad);
            $especialidad_ref = $datos_ref['especialidad'];
        } else {
            $referencia = null;
        }

        // Datos de Internación
        $hc_planes_inter = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 6)
            ->first();

        if ($hc_planes_inter) {
            $id_internacion = $hc_planes_inter->id_corresponde;
            $internacion = Internacion::find($id_internacion);
        } else {
            $internacion = null;
        }

        // Datos de Fisioterapia
        $hc_planes_fisio = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 5)
            ->first();

        if ($hc_planes_fisio) {
            $id_fisioterapia = $hc_planes_fisio->id_corresponde;
            $fisioterapia = Fisioterapia::find($id_fisioterapia);
        } else {
            $fisioterapia = null;
        }

        // Datos de Baja Medica
        $hc_planes_baja = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 4)
            ->first();

        if ($hc_planes_baja) {
            $id_baja_medica = $hc_planes_baja->id_corresponde;
            $baja_medica = BajaMedica::find($id_baja_medica);
        } else {
            $baja_medica = null;
        }

        // Datos de Imagenologia
        $hc_planes_ima = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 3)
            ->get();
        $imagenologias_con_estudios = [];

        foreach ($hc_planes_ima as $plan) {
            $imagenologia = Imagenologia::find($plan->id_corresponde);

            if ($imagenologia) {
                $estudios = ImagenologiaEstudio::where('id_sol_imagenologia', $imagenologia->id)->get();

                $imagenologias_con_estudios[] = [
                    'imagenologia' => $imagenologia,
                    'estudios' => $estudios,
                ];
            }
        }

        // Datos de Receta
        $hc_planes_receta = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 1)
            ->get();

        $receta_medicamento_por_receta = [];
        $receta_medicamento = [];

        if ($hc_planes_receta->isNotEmpty()) {
            foreach ($hc_planes_receta as $receta) {
                $id_receta = $receta->id_corresponde;

                if (! empty($id_receta)) {
                    $medicamentos = RecetaMedicamento::with('recetaRecetaMedicamento')
                        ->where('id_receta', $id_receta)->get();
                    $receta_medicamento_por_receta[$id_receta] = $medicamentos;
                    // dd($medicamentos);
                }
            }
        } else {
            $receta = null;
        }

        // Datos de Laboratorio
        $laboratorios = Solicitudes::where('id_historia_clinica', $rowId)->get();
        $solicitudes_con_examenes = [];

        foreach ($laboratorios as $lab) {
            $examenes = SolicitudExamen::where('id_solicitudes', $lab->id)->get();
            $solicitudes_con_examenes[] = [
                'laboratorio' => $lab,
                'examenes' => $examenes,
                'tipo_laboratorio' => $examenes[0]->tipo_laboratorio ?? null,
            ];
        }

        // Servicio para mostrar datos del especialista
        $dato_esp = new DatosEspecialista();
        $datos = $dato_esp->datosEspecialista($id_especialista);
        $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
        $especialidad = $datos['especialidad_especialista']['especialidad'];

        // Servicio para mostrar datos tipo asegurado e institucion del asegurado
        $dato_per = new ServicioPersona();
        $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
        //dd($datos_persona);
        $tipo_asegurado = $datos_persona['tipo_asegurado'];
        //$institucion = $datos_persona['institucion'];
        $institucion = $datos_persona['institucion'] ?? null;

        // Servicio para mostrar las instituciones de la persona
        $dato_per = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_per->personaInstitucion($id_persona);

        $instituciones = $persona_institucion['instituciones'] ?? [];
        //dd($instituciones);
        $codigo_institucional = null;
        if (! empty($instituciones)) {
            foreach ($instituciones as $institucion_individual) {
                if ($institucion_individual['nombre_institucion'] == $institucion) {
                    $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                    break;
                }
            }
        } else {
            $codigo_institucional = null;
        }

        $fechaHoraServidor = Carbon::now();
        $nombreUsuario = auth()->user()->name;

        $generarQr = new ObtencionQrDocumentos();
        $respuesta = $generarQr->generarCodigoDocumento($fechaHoraServidor);
        $codigoSeguridad = $respuesta['cod_seguridad'];
        //$imagenQr = $respuesta['imagen_qr'];
        //modificar foto http a https
        $urlImagen = $respuesta['imagen_qr'] ?? null;
        $imagenQr = '';

        if ($urlImagen) {
            $resp = Http::connectTimeout(3)->timeout(10)->get($urlImagen);

            if ($resp->ok()) {
                $contentType = $resp->header('Content-Type', 'image/png');
                $imagenQr = 'data:' . $contentType . ';base64,' . base64_encode($resp->body());
            }
        }

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = PDF::loadView(
            'AtencionMedica.ConsultaExterna.PdfConsultaExterna',
            compact(
                'id_historia_clinica',
                'nombre_especialista',
                'especialidad',
                'historia_clinica',
                'codigo_institucional',
                'hc_cie10',
                'referencia',
                'interconsulta',
                'especialidad_ref',
                'especialidad_inter',
                'internacion',
                'fisioterapia',
                'baja_medica',
                'imagenologias_con_estudios',
                'receta',
                'receta_medicamento',
                'receta_medicamento_por_receta',
                'institucion',
                'imagenQr',
                'solicitudes_con_examenes',
                // 'marginTop',   // <-- AGREGADO
                // 'marginLeft'   // <-- AGREGADO
            )
        )
            ->setPaper('letter', 'portrait');

        //Registrar en la b.d. el documento
        $datosDocumento = [
            'id_persona' => auth()->user()->id_persona,
            'id_usuario_created' => auth()->user()->id,
            'id_tipo_documento' => 17,
            'fecha_vigencia_inicio' => null,
            'fecha_vigencia_fin' => null,
            'indefinido' => true,
            'modulo' => 'CONSULTA EXTERNA',
            'cod_seguridad' => $codigoSeguridad,
            'fecha_generado' => $fechaHoraServidor,
            'cantidad_paginas' => 1,
        ];
        $generarQr->registrarDatosDocumento($datosDocumento);

        $nombre_pdf = 'historia_clinica_' . $rowId;

        // Guardar en el FTP, Obtener el contenido binario del PDF
        $pdfContent = $pdf->output();
        $fileName = 'historia_clinica_' . $rowId . '.pdf';
        $filePath = 'ConsultaExterna/HIstoriaClinica/' . $fileName;
        try {
            $success = Storage::disk('ftp')->put($filePath, $pdfContent);
            if (! $success) {
                throw new \Exception('No se pudo guardar el archivo en el FTP.');
            }
        } catch (\Exception $e) {
            Log::error('Error al subir receta PDF al FTP: ' . $e->getMessage());

            return response()->json(['error' => 'No se pudo subir el archivo al servidor FTP.'], 500);
        }

        // FtpSaveJob::dispatch($pdfContent, $filePath, $nombre_pdf);
        // Para ver pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }

    // public function pdfConsultaExternaDescarga($rowId ){

    //     $id_historia_clinica = $rowId;
    //     $historia_clinica = HistoriaClinica::find($id_historia_clinica);
    //     $id_persona = $historia_clinica->id_persona;
    //     $id_especialista = $historia_clinica->id_especialista;

    //     $hc_cie10 = HCCie10::where(  'id_historia_clinica', $id_historia_clinica)->get();

    //     // Datos de Interconsulta
    //     $hc_planes_inter = HCPlanes::where('id_historia_clinica',$rowId)
    //         ->where('id_plan', 8)
    //         ->first();

    //     $especialidad_inter = '';
    //     if ($hc_planes_inter) {
    //         $id_interconsulta = $hc_planes_inter->id_corresponde;
    //         $interconsulta = Interconsulta::find($id_interconsulta);

    //         // Servicio para mostrar datos de la especialidad registrada
    //         $dato_esp_ref = new ObtenerDatosEspecialidad();
    //         $datos_ref = $dato_esp_ref->datosEspecialidad($interconsulta->id_especialidad);
    //         $especialidad_inter = $datos_ref['especialidad'];
    //         //dd($especialidad_inter);
    //     } else {
    //         $interconsulta = null;
    //     }

    //     // Datos de Referencia
    //     $hc_planes_ref = HCPlanes::where('id_historia_clinica',$rowId)
    //         ->where('id_plan', 7)
    //         ->first();

    //     $especialidad_ref = '';
    //     if ($hc_planes_ref) {
    //         $id_referencia = $hc_planes_ref->id_corresponde;
    //         $referencia = Referencia::find($id_referencia);
    //         // Servicio para mostrar datos de la especialidad registrada
    //         $dato_esp_ref = new ObtenerDatosEspecialidad();
    //         $datos_ref = $dato_esp_ref->datosEspecialidad($referencia->id_especialidad);
    //         $especialidad_ref = $datos_ref['especialidad'];
    //     } else {
    //         $referencia = null;
    //     }

    //     // Datos de Internación
    //     $hc_planes_inter = HCPlanes::where('id_historia_clinica',$rowId)
    //         ->where('id_plan', 6)
    //         ->first();

    //     if ($hc_planes_inter) {
    //         $id_internacion = $hc_planes_inter->id_corresponde;
    //         $internacion = Internacion::find($id_internacion);
    //     } else {
    //         $internacion = null;
    //     }

    //     // Datos de Fisioterapia
    //     $hc_planes_fisio = HCPlanes::where('id_historia_clinica',$rowId)
    //         ->where('id_plan', 5)
    //         ->first();

    //     if ($hc_planes_fisio) {
    //         $id_fisioterapia = $hc_planes_fisio->id_corresponde;
    //         $fisioterapia = Fisioterapia::find($id_fisioterapia);
    //     } else {
    //         $fisioterapia = null;
    //     }

    //     // Datos de Baja Medica
    //     $hc_planes_baja = HCPlanes::where('id_historia_clinica',$rowId)
    //         ->where('id_plan', 4)
    //         ->first();

    //     if ($hc_planes_baja) {
    //         $id_baja_medica = $hc_planes_baja->id_corresponde;
    //         $baja_medica = BajaMedica::find($id_baja_medica);
    //     } else {
    //         $baja_medica = null;
    //     }

    //     // Datos de Imagenologia
    //     $hc_planes_ima = HCPlanes::where('id_historia_clinica',$rowId)
    //         ->where('id_plan', 3)
    //         ->get();
    //     $imagenologias_con_estudios = [];

    //     foreach ($hc_planes_ima as $plan) {
    //         $imagenologia = Imagenologia::find($plan->id_corresponde);

    //         if ($imagenologia) {
    //             $estudios = ImagenologiaEstudio::where('id_sol_imagenologia', $imagenologia->id)->get();

    //             $imagenologias_con_estudios[] = [
    //                 'imagenologia' => $imagenologia,
    //                 'estudios' => $estudios,
    //             ];
    //         }
    //     }

    //     // Datos de Receta
    //     $hc_planes_receta = HCPlanes::where('id_historia_clinica', $rowId)
    //         ->where('id_plan', 1)
    //         ->get();

    //     $receta_medicamento_por_receta = [];
    //     $receta_medicamento = [];

    //     if ($hc_planes_receta->isNotEmpty()) {
    //         foreach ($hc_planes_receta as $receta) {
    //             $id_receta = $receta->id_corresponde;

    //             if (!empty($id_receta)) {
    //                 $medicamentos = RecetaMedicamento::where('id_receta', $id_receta)->get();
    //                 $receta_medicamento_por_receta[$id_receta] = $medicamentos;
    //             }
    //         }
    //     } else {
    //         $receta = null;
    //     }

    //     // Datos de Laboratorio
    //     $laboratorios = Solicitudes::where('id_historia_clinica', $rowId)->get();
    //     $solicitudes_con_examenes = [];

    //     foreach ($laboratorios as $lab) {
    //         $examenes = SolicitudExamen::where('id_solicitudes', $lab->id)->get();
    //         $solicitudes_con_examenes[] = [
    //             'laboratorio' => $lab,
    //             'examenes' => $examenes,
    //             'tipo_laboratorio' => $examenes[0]->tipo_laboratorio ?? null,
    //         ];
    //     }

    //     // Servicio para mostrar datos del especialista
    //     $dato_esp = new DatosEspecialista();
    //     $datos = $dato_esp->datosEspecialista($id_especialista);
    //     $nombre_especialista = $datos['persona_especialista']['p_apellido'].' '.$datos['persona_especialista']['s_apellido'].' '.$datos['persona_especialista']['nombres'];
    //     $especialidad = $datos['especialidad_especialista']['especialidad'];

    //     // Servicio para mostrar datos tipo asegurado e institucion del asegurado
    //         $dato_per = new ServicioPersona();
    //         $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
    //         //dd($datos_persona);
    //         $tipo_asegurado = $datos_persona['tipo_asegurado'];
    //         //$institucion = $datos_persona['institucion'];
    //         $institucion = $datos_persona['institucion'] ?? null;

    //     // Servicio para mostrar las instituciones de la persona
    //         $dato_per = new ServicioPersonaInstitucion();
    //         $persona_institucion = $dato_per->personaInstitucion($id_persona);

    //         $instituciones = $persona_institucion['instituciones'] ?? [];
    //         //dd($instituciones);
    //         $codigo_institucional = null;
    //         if (!empty($instituciones)) {
    //                 foreach ($instituciones as $institucion_individual) {
    //                     if ($institucion_individual['nombre_institucion'] == $institucion) {
    //                         $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
    //                         break;
    //                     }
    //                 }
    //         }else{
    //             $codigo_institucional = null;
    //         }

    //     $fechaHoraServidor = Carbon::now();
    //     $nombreUsuario = auth()->user()->name;

    //     $generarQr = new ObtencionQrDocumentos();
    //     $respuesta = $generarQr->generarCodigoDocumento($fechaHoraServidor);
    //     $codigoSeguridad = $respuesta['cod_seguridad'];
    //     $imagenQr = $respuesta['imagen_qr'];

    //     // Para abrir el blade vista del pdf con variables ya definidas
    //     $pdf = PDF::loadView(
    //             'AtencionMedica.ConsultaExterna.PdfConsultaExterna',
    //             compact(
    //                 'id_historia_clinica',
    //                 'nombre_especialista',
    //                 'especialidad',
    //                 'historia_clinica',
    //                 'codigo_institucional',
    //                 'hc_cie10',
    //                 'referencia',
    //                 'interconsulta',
    //                 'especialidad_ref',
    //                 'especialidad_inter',
    //                 'internacion',
    //                 'fisioterapia',
    //                 'baja_medica',
    //                 'imagenologias_con_estudios',
    //                 'receta',
    //                 'receta_medicamento',
    //                 'receta_medicamento_por_receta',
    //                 'institucion',
    //                 'imagenQr',
    //                 'solicitudes_con_examenes'
    //             )
    //         )
    //             ->setPaper('letter', 'portrait');

    //         //Registrar en la b.d. el documento
    //         $datosDocumento = [
    //             'id_persona' => auth()->user()->id_persona,
    //             'id_usuario_created' => auth()->user()->id,
    //             'id_tipo_documento' => 17,
    //             'fecha_vigencia_inicio' => null,
    //             'fecha_vigencia_fin' => null,
    //             'indefinido' => true,
    //             'modulo' => 'CONSULTA EXTERNA',
    //             'cod_seguridad' => $codigoSeguridad,
    //             'fecha_generado' => $fechaHoraServidor,
    //             'cantidad_paginas' => 1,
    //         ];
    //         $generarQr->registrarDatosDocumento($datosDocumento);

    //     $nombre_pdf = 'historia_clinica_'.$rowId;

    //     // Para descargar pdf
    //     return $pdf->download($nombre_pdf . '.pdf', [
    //         'Attachment' => false,
    //         'Content-Disposition' => 'inline'
    //     ]);

    // }

    public function pdfConsultaExternaCopia($rowId)
    {
        dd('sdfsd');
        $id_historia_clinica = $rowId;
        $historia_clinica = HistoriaClinica::find($id_historia_clinica);
        $id_persona = $historia_clinica->id_persona;
        $id_especialista = $historia_clinica->id_especialista;

        $hc_cie10 = HCCie10::where('id_historia_clinica', $id_historia_clinica)->get();

        // Datos de Interconsulta
        $hc_planes_inter = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 8)
            ->first();

        $especialidad_inter = '';
        if ($hc_planes_inter) {
            $id_interconsulta = $hc_planes_inter->id_corresponde;
            $interconsulta = Interconsulta::find($id_interconsulta);

            // Servicio para mostrar datos de la especialidad registrada
            $dato_esp_ref = new ObtenerDatosEspecialidad();
            $datos_ref = $dato_esp_ref->datosEspecialidad($interconsulta->id_especialidad);
            $especialidad_inter = $datos_ref['especialidad'];
            //dd($especialidad_inter);
        } else {
            $interconsulta = null;
        }

        // Datos referencia
        $hc_planes_ref = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 7)
            ->first();

        $especialidad_ref = '';
        if ($hc_planes_ref) {
            $id_referencia = $hc_planes_ref->id_corresponde;
            $referencia = Referencia::find($id_referencia);
            // Servicio para mostrar datos de la especialidad registrada
            $dato_esp_ref = new ObtenerDatosEspecialidad();
            $datos_ref = $dato_esp_ref->datosEspecialidad($referencia->id_especialidad);
            $especialidad_ref = $datos_ref['especialidad'];
        } else {
            $referencia = null;
        }

        // Datos de Internación
        $hc_planes_inter = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 6)
            ->first();

        if ($hc_planes_inter) {
            $id_internacion = $hc_planes_inter->id_corresponde;
            $internacion = Internacion::find($id_internacion);
        } else {
            $internacion = null;
        }

        // Datos de Fisioterapia
        $hc_planes_fisio = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 5)
            ->first();

        if ($hc_planes_fisio) {
            $id_fisioterapia = $hc_planes_fisio->id_corresponde;
            $fisioterapia = Fisioterapia::find($id_fisioterapia);
        } else {
            $fisioterapia = null;
        }

        // Datos de Baja Medica
        $hc_planes_baja = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 4)
            ->first();

        if ($hc_planes_baja) {
            $id_baja_medica = $hc_planes_baja->id_corresponde;
            $baja_medica = BajaMedica::find($id_baja_medica);
        } else {
            $baja_medica = null;
        }

        // Datos de Imagenologia
        $hc_planes_ima = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 3)
            ->get();
        $imagenologias_con_estudios = [];

        foreach ($hc_planes_ima as $plan) {
            $imagenologia = Imagenologia::find($plan->id_corresponde);

            if ($imagenologia) {
                $estudios = ImagenologiaEstudio::where('id_sol_imagenologia', $imagenologia->id)->get();

                $imagenologias_con_estudios[] = [
                    'imagenologia' => $imagenologia,
                    'estudios' => $estudios,
                ];
            }
        }

        // Datos de Receta
        $hc_planes_receta = HCPlanes::where('id_historia_clinica', $rowId)
            ->where('id_plan', 1)
            ->get();

        $receta_medicamento_por_receta = [];
        $receta_medicamento = [];

        if ($hc_planes_receta->isNotEmpty()) {
            foreach ($hc_planes_receta as $receta) {
                $id_receta = $receta->id_corresponde;

                if (! empty($id_receta)) {
                    $medicamentos = RecetaMedicamento::with('recetaRecetaMedicamento')
                        ->where('id_receta', $id_receta)->get();
                    $receta_medicamento_por_receta[$id_receta] = $medicamentos;
                }
            }
        } else {
            $receta = null;
        }

        // Datos de Laboratorio
        $laboratorios = Solicitudes::where('id_historia_clinica', $rowId)->get();
        $solicitudes_con_examenes = [];

        foreach ($laboratorios as $lab) {
            $examenes = SolicitudExamen::where('id_solicitudes', $lab->id)->get();
            $solicitudes_con_examenes[] = [
                'laboratorio' => $lab,
                'examenes' => $examenes,
                'tipo_laboratorio' => $examenes[0]->tipo_laboratorio ?? null,
            ];
        }

        // Servicio para mostrar datos del especialista
        $dato_esp = new DatosEspecialista();
        $datos = $dato_esp->datosEspecialista($id_especialista);
        $nombre_especialista = $datos['persona_especialista']['p_apellido'] . ' ' . $datos['persona_especialista']['s_apellido'] . ' ' . $datos['persona_especialista']['nombres'];
        $especialidad = $datos['especialidad_especialista']['especialidad'];

        // Servicio para mostrar datos tipo asegurado e institucion del asegurado
        $dato_per = new ServicioPersona();
        $datos_persona = $dato_per->datosPersonaInstitucion($id_persona);
        $tipo_asegurado = $datos_persona['tipo_asegurado'];
        $institucion = $datos_persona['institucion'];

        // Servicio para mostrar las instituciones de la persona
        $dato_per = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_per->personaInstitucion($id_persona);
        $instituciones = $persona_institucion['instituciones'] ?? [];
        $codigo_institucional = null;
        if (! empty($instituciones)) {
            foreach ($instituciones as $institucion_individual) {
                if ($institucion_individual['nombre_institucion'] == $institucion) {
                    $codigo_institucional = $institucion_individual['codigo_institucional'] ?? null;
                    break;
                }
            }
        }

        $fechaHoraServidor = Carbon::now();
        $nombreUsuario = auth()->user()->name;

        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = PDF::loadView(
            'AtencionMedica.ConsultaExterna.PdfConsultaExternaCopia',
            compact(
                'id_historia_clinica',
                'nombre_especialista',
                'especialidad',
                'historia_clinica',
                'codigo_institucional',
                'hc_cie10',
                'referencia',
                'interconsulta',
                'especialidad_ref',
                'especialidad_inter',
                'internacion',
                'fisioterapia',
                'baja_medica',
                'imagenologias_con_estudios',
                'receta',
                'receta_medicamento',
                'receta_medicamento_por_receta',
                'institucion',
                'solicitudes_con_examenes'
            )
        )
            ->setPaper('letter', 'portrait');
        $nombre_pdf = 'historia_clinica_' . $rowId;

        // Para ver pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline',
        ]);
    }

    public function recuperarHistoriaFtp($rowId)
    {
        //dd('hola bola');
        //dd($rowId);
        // $name = 'historia_clinica_' . $rowId . '.pdf';
        // $path = 'ConsultaExterna/HIstoriaClinica/' . $name;
        // if (!Storage::disk('ftp')->exists($path) || $rowId === null) {
        //     abort(404, 'Archivo no encontrado.');
        // }
        // return Storage::disk('ftp')->stream($path, $name);
        if ($rowId === null) {
            abort(404, 'Archivo no encontrado.');
            //dd('entra');
            //$this->pdfConsultaExterna($rowId);
        }

        $name = 'historia_clinica_' . $rowId . '.pdf';
        $path = 'ConsultaExterna/HIstoriaClinica/' . $name;

        if (! Storage::disk('ftp')->exists($path)) {
            //abort(404, 'Archivo no encontrado.');
            $this->pdfConsultaExterna($rowId);
        }

        $stream = Storage::disk('ftp')->readStream($path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $name . '"',
        ]);
    }
}
