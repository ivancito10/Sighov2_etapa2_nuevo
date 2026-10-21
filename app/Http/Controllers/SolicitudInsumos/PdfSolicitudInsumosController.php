<?php

namespace App\Http\Controllers\SolicitudInsumos;

use App\Http\Controllers\Controller;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\Solicitud;
use App\Services\ObtenerDatosPersonalSsu;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\QRCodeService;
use App\Services\ObtenerDatosUnidadPorId;

class PdfSolicitudInsumosController extends Controller
{
    public function generarPDFSolicitudInsumos($modo, $rowId)
    {
        //dd('dasd');
        try {
            // Nombre y ruta del archivo en FTP
            $fileName = "SOLICITUD_UNIDADES_{$rowId}.pdf";
            $filePath = "/Farmacia/Solicitud_Unidades/{$fileName}";

            // Verificar si el PDF ya existe en FTP
            $existePdf = Storage::disk('ftp')->exists($filePath);

            if (
                $modo === 'create' ||
                $modo === 'edit' ||
                ($modo === 'ver' && !$existePdf)
            ) {
                $solicitud = Solicitud::with([
                    'usuarioCreador.personaUser',
                    'usuarioApruebaSolicitud',
                    'usuarioAnulaSolicitud',
                ])->findOrFail($rowId);

                // Datos básicos
                $cod_solicitud = "{$solicitud->cod_solicitud} / " . $solicitud->created_at->year;
                $fecha_solicitud = $solicitud->fecha_solicitud?->format('d/m/Y');
                $fechaCreado = $solicitud->created_at->format('d/m/Y');

                // Texto seguro
                $descripcion = $solicitud->descripcion ?: 'NO CUENTA CON DESCRIPCIÓN';
                $motivo = $solicitud->motivo;
                $estadoValidacion = $solicitud->estado_validacion;
                $motivo_anulacion = $solicitud->motivo_anulacion;

                // Fechas opcionales simplificadas
                $fecha_anulacion   = $solicitud->fecha_anulacion?->format('d/m/Y');
                $fecha_aprobacion  = $solicitud->fecha_aprobacion?->format('d/m/Y');
                $fecha_entregado   =  $solicitud->fecha_entregado?->format('d/m/Y');

                // Usuarios
                $usuarioSolicita = $solicitud->usuarioCreador->name ?? '';
                $usuarioAnula    = $solicitud->usuarioAnulaSolicitud->name ?? '';
                $usuarioAprueba  = $solicitud->usuarioApruebaSolicitud->name ?? '';

                // Datos del solicitante (cargo/unidad)
                $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->personaUser->id);
                //$unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
                $servicioSeleccionadoServicio = $this->obtenerDatosUnidad($solicitud->id_unidad_servicio);
                $unidadSolicitante = $servicioSeleccionadoServicio['nombre'] ?? 'SIN UNIDAD';
                $cargoSolicitante  = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';

                // Usuario actual
                $nombre_usuario = auth()->user()->name;

                // Medicamentos solicitados (YA optimizados con tus modelos)
                $medicamentos = MedicamentoSolicitud::where('id_solicitud', $rowId)
                    ->with(['medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud'])
                    ->get();
                //  dd($solicitud->id_unidad_recepciona);

                if ($solicitud->id_unidad_recepciona == 89) {
                    // dd('sdasd');
                    $nombreUnidad = 'ALMACEN DE MEDICAMENTOS';
                } else {
                    $obtenerUnidad = $this->obtenerDatosUnidad($solicitud->id_unidad_recepciona);
                    $nombreUnidad = $obtenerUnidad['nombre'] ?? 'SIN UNIDAD';
                }

                // Fecha actual en español
                $fechaHoraServidor = Carbon::now();
                $fechaHoy = Carbon::now()->format('d/m/Y H:i:s');
                //$fechaHoy = Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');

                $generarQr = new QRCodeService();
                $respuesta = $generarQr->generarCodigoDocumento($fechaHoraServidor);
                $codigoSeguridad = $respuesta['cod_seguridad'];
                $imagenQr = $respuesta['imagen_qr'];

                // Generar PDF
                $pdf = PDF::loadView(
                    'SolicitudInsumos.pdfSolicitudInsumos',
                    compact(
                        'fechaHoy',
                        'nombre_usuario',
                        'medicamentos',
                        'cod_solicitud',
                        'fecha_solicitud',
                        'usuarioSolicita',
                        'estadoValidacion',
                        'motivo',
                        'descripcion',
                        'usuarioAprueba',
                        'usuarioAnula',
                        'fecha_aprobacion',
                        'unidadSolicitante',
                        'cargoSolicitante',
                        'fecha_entregado',
                        'fecha_anulacion',
                        'motivo_anulacion',
                        'imagenQr',
                        'nombreUnidad'
                    )
                )->setPaper('letter', 'portrait');

                $dompdf = $pdf->getDomPDF();
                $dompdf->render();
                $canvas = $dompdf->getCanvas();
                $totalPaginas = $dompdf->getCanvas()->get_page_count();
                $font = $dompdf->getFontMetrics()->getFont("DejaVu Sans", "normal");

                //Mostrar numeracion de pagina
                $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($font) {
                    $text = "Página $pageNumber de $pageCount";
                    $size = 7;
                    $width = $fontMetrics->getTextWidth($text, $font, $size);
                    $x = $canvas->get_width() - $width - 20;
                    $y = $canvas->get_height() - 65;

                    $canvas->text($x, $y, $text, $font, $size, [0, 0, 0]);
                });

                //Registro del documento
                $datosDocumento = [
                    'id_persona' => auth()->user()->id_persona,
                    'id_usuario_created' => auth()->user()->id,
                    'id_tipo_documento' => 21,
                    'fecha_vigencia_inicio' => null,
                    'fecha_vigencia_fin' => null,
                    'indefinido' => true,
                    'modulo' => 'FARMACIA',
                    'cod_seguridad' => $codigoSeguridad,
                    'fecha_generado' => $fechaHoraServidor,
                    'cantidad_paginas' => $totalPaginas,
                ];
                $generarQr->registrarDatosDocumento($datosDocumento);

                // Guardar en FTP
                $pdfContent = $pdf->output();
                Storage::disk('ftp')->put($filePath, $pdfContent);
            } else {
                // Si existe solo se visualiza
                $pdfContent = Storage::disk('ftp')->get($filePath);
            }

            // Retornar al navegador
            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        } catch (\Throwable $th) {
            Log::error("Error al generar o recuperar comprobante diario: " . $th->getMessage());
            abort(500, "Error al generar o mostrar el comprobante diario.");
        }
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    public function obtenerDatosUnidad($id_unidad)
    {
        $obtenerDatosUnidad = new ObtenerDatosUnidadPorId();
        return $obtenerDatosUnidad->obtenerDatosUnidadPorId($id_unidad);
    }
}
