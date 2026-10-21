<?php

namespace App\Http\Controllers\Farmacia\AlmacenFarmacia;

use App\Helpers\NumberToWords;
use App\Http\Controllers\Controller;
use App\Models\Farmacia\DetalleIngreso;
use App\Models\Farmacia\Ingreso;
use App\Services\QRCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class pdfIngresoController extends Controller
{
    // este es el pdf de ingreso de medicamntos por almacen de farmacia
    public function generarPDFIngresoMedicamento($modo, $rowId)
    {
        try {
            $ingreso = Ingreso::with([
                'procesoIngreso:id,nombre,sigla',
                'institucionExternaIngreso:id,razon_social',
                'facturasActivas:id_ingreso,nro_factura,fecha,monto_total,nota_remision',
            ])->findOrFail($rowId);
            // dd($ingreso);
            $codIngreso = $ingreso->cod_ingreso;
            $fechaIngresoAnio = Carbon::parse($ingreso->fecha_ingreso)->format('Y');
            $fechaIngreso = $ingreso->fecha_ingreso
                ? Carbon::parse($ingreso->fecha_ingreso)->format('d/m/Y')
                : '';

            // Nombre y ruta del archivo en FTP
            $fileName = $ingreso->id . '_' . $fechaIngresoAnio . '.pdf';
            $filePath = 'Farmacia/Almacen_Farmacia/Ingresos/' . $fileName;

            // Verificar si el PDF ya existe en FTP
            $existePdf = Storage::disk('ftp')->exists($filePath);
            // dd($modo, $existePdf);
            if (
                $modo === 'create' ||
                $modo === 'edit' ||
                ($modo === 'ver' && $existePdf)
            ) {
                $nroLote = $ingreso->nro_lote;
                $codAsmi = $ingreso->cod_asmi;
                // dd($codAsmi);
                $montoTotal = $ingreso->monto_total;
                $responsables = $ingreso->responsables;
                $codigoCuce = $ingreso->cod_cuce;
                $fechaContrato = Carbon::parse($ingreso->fecha_contrato);

                $anio = Carbon::parse($ingreso->created_at)->year;

                $codAsmiLiteral = "{$codAsmi}/{$anio}";
                $nroIngresoLiteral = $ingreso->procesoIngreso?->sigla . "/{$codIngreso}/{$anio}";

                $fechaEntrega = $ingreso->fecha_recepcion
                    ? Carbon::parse($ingreso->fecha_recepcion)
                    : '';

                $horaEntrega = $ingreso->fecha_recepcion
                    ? Carbon::parse($ingreso->fecha_recepcion)
                    : '';

                $proveedor = $ingreso->institucionExternaIngreso?->razon_social ?? '';
                $tipoProceso = $ingreso->procesoIngreso?->nombre ?? '';

                //Facturas
                $facturas = $ingreso->facturasActivas ?? collect();
                //$total_facturas = $facturas->sum('monto_total');
               // dd($facturas);
                // 8. Detalle de medicamentos
                $medicamentosIngreso = DetalleIngreso::with([
                    'medicamentoDetalleIngreso:id,codigo_medicamento,nombre,concentracion,forma_farmaceutica',
                ])
                    ->where('id_ingreso', $ingreso->id)
                    ->get();

                $numero_formateado = number_format($montoTotal, 2, '.', '');
                $partes = explode('.', $numero_formateado);
                $parteDecimal = isset($partes[1]) ? $partes[1] : '';
                $numeroEnteroEnLetras = NumberToWords::convertir(floor($montoTotal));
                $numeroDecimalEnLetras = NumberToWords::convertir($parteDecimal);

                $fechaHoraServidor = Carbon::now();
                $fechaActual = $fechaHoraServidor->format('d/m/Y H:i:s');
                $nombreUsuario = auth()->user()->name;
                $generarQr = new QRCodeService();
                $respuesta = $generarQr->generarCodigoDocumento($fechaHoraServidor);
                // dd($respuesta);
                $codigoSeguridad = $respuesta['cod_seguridad'];
                //$imagenQr = $respuesta['imagen_qr'];

                $urlImagen = $respuesta['imagen_qr'] ?? '';
                // $imagenQr = $urlImagen
                //     ? route('imagen.proxy', ['u' => $urlImagen])
                //     : '';

                $imagenQr = '';
                if ($urlImagen) {
                    $resp = Http::connectTimeout(3)->timeout(10)->get($urlImagen);

                    if ($resp->ok()) {
                        $contentType = $resp->header('Content-Type', 'image/png');
                        $imagenQr = 'data:' . $contentType . ';base64,' . base64_encode($resp->body());
                    }
                }
                $pdf = PDF::loadView(
                    'Farmacia.AlmacenFarmacia.Ingresos.pdfIngresoMedicamentos',
                    compact(
                        'codIngreso',
                        'medicamentosIngreso',
                        'facturas',
                        'nroLote',
                        'codAsmiLiteral',
                        'montoTotal',
                        'nroIngresoLiteral',
                        'fechaIngreso',
                        'proveedor',
                        'tipoProceso',
                        'numeroEnteroEnLetras',
                        'parteDecimal',
                        'fechaEntrega',
                        'horaEntrega',
                        'codigoCuce',
                        'fechaContrato',
                        'responsables',
                        'imagenQr',
                        'fechaActual',
                        'nombreUsuario'
                    )
                )->setPaper([0, 0, 612, 932.87], 'portrait');

                $dompdf = $pdf->getDomPDF();
                $dompdf->render();
                $canvas = $dompdf->getCanvas();
                $totalPaginas = $dompdf->getCanvas()->get_page_count();
                $font = $dompdf->getFontMetrics()->getFont('DejaVu Sans', 'normal');

                //Mostrar numeracion de pagina
                $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($font) {
                    $text = "Página $pageNumber de $pageCount";
                    $size = 7;
                    $width = $fontMetrics->getTextWidth($text, $font, $size);
                    $x = $canvas->get_width() - $width - 20;
                    $y = $canvas->get_height() - 50;

                    $canvas->text($x, $y, $text, $font, $size, [0, 0, 0]);
                });

                $datosDocumento = [
                    'id_persona' => auth()->user()->id_persona,
                    'id_usuario_created' => auth()->user()->id,
                    'id_tipo_documento' => 18,
                    'fecha_vigencia_inicio' => null,
                    'fecha_vigencia_fin' => null,
                    'indefinido' => true,
                    'modulo' => 'FARMACIA',
                    'cod_seguridad' => $codigoSeguridad,
                    'fecha_generado' => $fechaHoraServidor,
                    'cantidad_paginas' => $totalPaginas,
                ];
                $generarQr->registrarDatosDocumento($datosDocumento);

                /* Para guardar el pdf en ftp */
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
            Log::error('Error al generar o recuperar comprobante diario: ' . $th->getMessage());
            abort(500, 'Error al generar o mostrar el comprobante diario.');
        }
    }
}
