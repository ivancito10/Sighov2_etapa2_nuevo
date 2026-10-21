<?php

namespace App\Http\Controllers\AlmacenGeneral;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\NumberToWords;
use App\Models\AlmacenGeneral\Factura;
use App\Models\AlmacenGeneral\Ingreso;
use App\Models\AlmacenGeneral\IngresoMaterial;
use App\Models\AlmacenGeneral\DetalleIngreso;
use App\Services\QRCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class PdfIngresoMaterialesController extends Controller
{
    public function generarPDFIngresoMateriales($modo, $rowId)
    {
        try {

            $ingreso = Ingreso::with([
                'proveedorCumple',
                'procesoIngreso:id,nombre,sigla',
                'institucionExternaIngreso:id,razon_social',
            ])->findOrFail($rowId);

            //dd($ingreso->proveedorCumple);
            // DATOS PRINCIPALES
            $codIngreso = $ingreso->codigo_ingreso;
            $fechaIngresoAnio = $ingreso->fecha_ingreso ? Carbon::parse($ingreso->fecha_ingreso)->format('Y') : ' ';
            $fechaIngreso = $ingreso->fecha_ingreso
                ? Carbon::parse($ingreso->fecha_ingreso)
                : null;

            $fileName = $ingreso->id . '_' . $fechaIngresoAnio . '.pdf';

            $filePath = 'AlmacenGeneral/Ingresos/' . $fileName;

            // VERIFICAR SI EXISTE PDF
            $existePdf = Storage::disk('ftp')->exists($filePath);

            if (
                $modo === 'create' ||
                $modo === 'edit' ||
                ($modo === 'ver' && !$existePdf)
            ) {

                $nroLote = $ingreso->nro_lote;
                $codAsmi = $ingreso->codigo_asmi;
                $montoTotal = $ingreso->monto_total;
                $responsables = $ingreso->responsables;
                $codigoCuce = $ingreso->codigo_cuce ?: 'NO REGISTRADO';
                $fechaContrato = $ingreso->fecha_contrato
                    ? Carbon::parse($ingreso->fecha_contrato)
                    : null;

                $anio = Carbon::parse($ingreso->created_at)->year;

                $nroIngresoLiteral = $ingreso->procesoIngreso?->sigla .
                    "/{$codIngreso}/{$anio}";

                $fechaEntrega = $ingreso->fecha_recepcion
                    ? Carbon::parse($ingreso->fecha_recepcion)
                    : null;

                $horaEntrega = $ingreso->hora_recepcion
                    ? Carbon::parse($ingreso->hora_recepcion)
                    : null;

                $proveedor = $ingreso->institucionExternaIngreso?->razon_social ?? '';

                $tipoProceso = $ingreso->procesoIngreso?->nombre ?? '';

                // FACTURAS
                $facturas = Factura::where('id_ingreso', $ingreso->id)
                    ->where('estado', true)
                    ->get();

                // MATERIALES
                $materialesIngreso = IngresoMaterial::where('id_ingreso', $ingreso->id)
                    ->with('materialIngresoMaterial')
                    ->get();

                // MONTO LITERAL
                $numero_formateado = number_format($montoTotal, 2, '.', '');
                $partes = explode('.', $numero_formateado);
                $parteDecimal = $partes[1] ?? '00';

                $numeroEnteroEnLetras = NumberToWords::convertir(
                    floor($montoTotal)
                );

                // FECHA GENERACIÓN
                $fechaHoraServidor = Carbon::now();
                $fechaActual = $fechaHoraServidor->format('d/m/Y H:i:s');
                $nombreUsuario = auth()->user()->name;

                // GENERAR QR
                $generarQr = new QRCodeService();

                $respuesta = $generarQr->generarCodigoDocumento(
                    $fechaHoraServidor
                );

                $codigoSeguridad = $respuesta['cod_seguridad'];
                $urlImagen = $respuesta['imagen_qr'] ?? '';
                $imagenQr = '';

                if ($urlImagen) {

                    $resp = Http::connectTimeout(3)
                        ->timeout(10)
                        ->get($urlImagen);

                    if ($resp->ok()) {

                        $contentType = $resp->header(
                            'Content-Type',
                            'image/png'
                        );

                        $imagenQr = 'data:' . $contentType .
                            ';base64,' .
                            base64_encode($resp->body());
                    }
                }

                // GENERAR PDF
                $pdf = PDF::loadView(
                    'AlmacenGeneral.Ingresos.pdfIngresoMateriales',
                    compact(
                        'codIngreso',
                        'fechaIngreso',
                        'nroLote',
                        'montoTotal',
                        'nroIngresoLiteral',
                        'fechaEntrega',
                        'horaEntrega',
                        'proveedor',
                        'tipoProceso',
                        'codigoCuce',
                        'fechaContrato',
                        'responsables',
                        'facturas',
                        'materialesIngreso',
                        'numeroEnteroEnLetras',
                        'parteDecimal',
                        'imagenQr',
                        'fechaActual',
                        'nombreUsuario',
                        'ingreso'
                    )
                )->setPaper([0, 0, 612, 932.87], 'portrait');

                $dompdf = $pdf->getDomPDF();

                $dompdf->render();

                $canvas = $dompdf->getCanvas();

                $totalPaginas = $canvas->get_page_count();

                $font = $dompdf->getFontMetrics()
                    ->getFont('DejaVu Sans', 'normal');

                // NUMERACIÓN
                $canvas->page_script(
                    function ($pageNumber, $pageCount, $canvas, $fontMetrics)
                    use ($font) {
                        $text = "Página $pageNumber de $pageCount";
                        $size = 7;
                        $width = $fontMetrics->getTextWidth(
                            $text,
                            $font,
                            $size
                        );

                        $x = $canvas->get_width() - $width - 20;
                        $y = $canvas->get_height() - 50;

                        $canvas->text(
                            $x,
                            $y,
                            $text,
                            $font,
                            $size,
                            [0, 0, 0]
                        );
                    }
                );

                // REGISTRO QR
                $datosDocumento = [
                    'id_persona' => auth()->user()->id_persona,
                    'id_usuario_created' => auth()->user()->id,
                    'id_tipo_documento' => 19,
                    'fecha_vigencia_inicio' => null,
                    'fecha_vigencia_fin' => null,
                    'indefinido' => true,
                    'modulo' => 'ALMACEN GENERAL',
                    'cod_seguridad' => $codigoSeguridad,
                    'fecha_generado' => $fechaHoraServidor,
                    'cantidad_paginas' => $totalPaginas,
                ];

                $generarQr->registrarDatosDocumento(
                    $datosDocumento
                );

                // GUARDAR PDF EN FTP
                $pdfContent = $pdf->output();

                Storage::disk('ftp')->put(
                    $filePath,
                    $pdfContent
                );
            } else {

                // SOLO VISUALIZA
                $pdfContent = Storage::disk('ftp')->get($filePath);
            }

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header(
                    'Content-Disposition',
                    'inline; filename="' . $fileName . '"'
                );
        } catch (\Throwable $th) {
            //dd($th->getMessage());
            dd([
                'mensaje' => $th->getMessage(),
                'linea' => $th->getLine(),
                'archivo' => $th->getFile(),
            ]);
            Log::error(
                'Error PDF ingreso materiales: ' .
                    $th->getMessage()
            );

            abort(
                500,
                'Error al generar el PDF.'
            );
        }
    }
}
