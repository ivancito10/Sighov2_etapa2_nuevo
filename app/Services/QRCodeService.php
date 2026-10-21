<?php

namespace App\Services;

use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Administracion\DocumentoPDF;
use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;

class QRCodeService
{
    public function generarCodigoDocumento($fecha)
    {
        $servicioDocumentos = new ObtenerToken();
        $servicioDocumentos->refresh_token();

        $servicio = LinkServicio::where('nombre_servicio', 'GENERAR QR')->first();

        $token = session('access_token');

        $response = Http::withToken($token)->post($servicio->link_servicio, ['fecha_generado' => $fecha]);

        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        }
        //dd($response->json());
    }

    public function registrarDatosDocumento(array $data)
    {
        $servicioDocumentos = new ObtenerToken();
        $servicioDocumentos->refresh_token();

        $servicio = LinkServicio::where('nombre_servicio', 'REGISTRAR PDF')->first();

        $token = session('access_token');

        $response = Http::withToken($token)->post($servicio->link_servicio, $data);
        //dd($response->json());
        return $response->json();
    }


    // Add your service logic here
    public static function generarQR($fechaHoraActual = null)
    {
        // $fechaHoraActual = Carbon::now()->format('Y-m-d H:i:s');
        if ($fechaHoraActual === null) {
            $fechaHoraActual = Carbon::now()->format('Y-m-d H:i:s');
        }
        // Generar un hash SHA-256 del valor
        $cod_seguridad = hash('sha256', $fechaHoraActual);
        // Limitar el hash a 20 caracteres
        // $cod_seguridad = substr($hash, 0, 20);
        $urlVerificacion = config('app.url') . '/verificar-documento/' . $cod_seguridad;
        //$urlVerificacion = 'http://192.168.4.118:8000' . '/verificar-documento/' . $cod_seguridad;
        //dd($urlVerificacion);
        //APP_URL=https://sigho.ssulapaz.org

        //dd($urlVerificacion);

        // $qrCode = new QrCode;
        // $qrCode = QrCode::format('png')
        // ->margin(1)
        // ->merge('images/ssu.png', .2, true)
        // ->size(350)->color(3, 28, 111)
        // ->generate($urlVerificacion);
        // $qrCode = base64_encode($qrCode);
        $qrCode = base64_encode(QrCode::format('svg')
            ->size(150)
            ->color(
                3,
                125,
                155
            )
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')
            ->generate($urlVerificacion));
        $datos = [
            'cod_seguridad' => $cod_seguridad,
            'qrCode' => $qrCode
        ];
        return $datos;
    }


    public static function registrarPDF(
        $pdf,
        $cod_seguridad,
        $id_persona,
        $id_tipo_documento,
        $modulo,
    ) {
        //dd($id_persona,'entra');
        // $dompdf = $pdf->getDomPDF();
        //   $dompdf->render(); // Renderizar el PDF para poder acceder al canvas
        //  $canvas = $dompdf->get_canvas();
        // $cantidadPaginas = $canvas->get_page_count();

        $fechaVigenciaInicio = now();
        $fechaVigenciaFin = $fechaVigenciaInicio->copy()->addDays(15);
        $pdf = DocumentoPDF::where('codigo_seguridad', $cod_seguridad)->exists();
        if (!$pdf) {
            $documentoPdf = [
                'id_tipo_documento'     => $id_tipo_documento, // 2 = AFILIACION SSU
                'codigo_seguridad'      => $cod_seguridad,
                'modulo'                => $modulo, // O el módulo correspondiente
                'fecha_generado'        => now(),
                // 'cantidad_paginas'      => $cantidadPaginas, //como 
                'pertenece_a'           => $id_persona, // ID del certificado o dato relevante
                'fecha_vigencia_inicio' => $fechaVigenciaInicio, // O la fecha que corresponda
                'fecha_vigencia_fin'    => $fechaVigenciaFin,  // Si aplica
                'indefinido'            => false,  // true o false según corresponda
                'id_usuario_created'    => auth()->user()->id,
            ];
            //dd($documentoPdf);
            DocumentoPDF::create($documentoPdf);
        }
    }
}
