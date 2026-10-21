<?php

namespace App\Http\Controllers\Farmacia\AlmacenFarmacia;

use App\Http\Controllers\Controller;
use App\Models\Farmacia\Reingreso;
use App\Models\Farmacia\ReingresoMedicamento;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\Solicitud;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\QRCodeService;
use App\Services\ObtenerDatosUnidadPorId;

class PdfReingresoAlmacenMedController extends Controller
{
    public function generarPDFReingreso($modo, $id_reingreso)
    {
        $fileName = "REINGRESO_{$id_reingreso}.pdf";
        $filePath = "/Farmacia/AlmacenFarmacia/Reingreso/{$fileName}";

        // Verificar si el PDF ya existe en FTP
        $existePdf = Storage::disk('ftp')->exists($filePath);

        if (
            $modo === 'create' ||
            ($modo === 'ver' && !$existePdf)
        ) {
            $reingreso = Reingreso::with([
                'paciente.tipoAseguradoPersona',
                'detallesMedicamento.medicamentoReingresoMedicamento'
            ])->findOrFail($id_reingreso);

            $codigoReingreso = $reingreso->codigo_reingreso . ' / ' . $reingreso->created_at->year;
            $fechaIngreso = Carbon::parse($reingreso->fecha_ingreso)->format('d/m/Y');
            $tipoReingreso = $reingreso->tipo_reingreso;
            $detalleMedicamentos = $reingreso->reingresoReingresoMedicamento;
            $unidadReingresante = $this->obtenerNombreUnidad($reingreso->id_unidad);

            //dd($codigoReingreso, $paciente);


            $user_name = auth()->user()->name;
            $fecha_actual = Carbon::now()->format('d/m/Y H:i:s');

            $fechaHoraServidor = Carbon::now();
            $fechaHoy = Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');

            $generarQr = new QRCodeService();
            $respuesta = $generarQr->generarCodigoDocumento($fechaHoraServidor);
            $codigoSeguridad = $respuesta['cod_seguridad'];
            $imagenQr = $respuesta['imagen_qr'];

            $pdf = PDF::loadView('Farmacia.AlmacenFarmacia.Reingresos.pdfReingresoAlmacen', compact(
                'reingreso',
                'codigoReingreso',
                'fechaIngreso',
                'detalleMedicamentos',
                'imagenQr',
                'fecha_actual',
                'user_name',
                'unidadReingresante'
            ))->setPaper('letter', 'portrait');

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
                'id_tipo_documento' => 22,
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

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    public function obtenerNombreUnidad($idUnidad)
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidad)['nombre'];
    }
}
