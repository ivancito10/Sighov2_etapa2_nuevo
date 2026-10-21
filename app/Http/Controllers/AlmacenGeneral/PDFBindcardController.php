<?php

namespace App\Http\Controllers\AlmacenGeneral;

use App\Http\Controllers\Controller;
use App\Models\AlmacenGeneral\CierreGestion;
use App\Models\AlmacenGeneral\Ingreso;
use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\Movimiento;
use App\Models\AlmacenGeneral\Reingreso;
use App\Models\AlmacenGeneral\Solicitud;
use App\Exports\BindcardAlmacenGeneralExport;
use App\Services\ListaUnidades;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PDFBindcardController extends Controller
{
    public static function generarPDFBindcard($fecha_inicio, $fecha_fin, $id_material, $unidad)
    {
        $fecha_inicio = Carbon::parse($fecha_inicio)->startOfDay();
        $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();

        $unidades = [
            'ALMACEN GENERAL'       => self::obtenerIdAlmacenGeneral(),
        ];
        $id_unidad = $unidades[$unidad] ?? null;

        $material = Material::findOrFail($id_material);
        $cod_material    = $material->codigo;
        $nombre_material  = $material->nombre_material;
        $unidad  = $material->unidad;

        $movimientos = Movimiento::with(['usuarioCreador'])
            ->where('id_material', $id_material)
            ->where('id_unidad', $id_unidad)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('fecha', 'ASC')
            ->get();

        // Construir datos para la tabla
        $datos = $movimientos->map(function ($mov) {

            $codigo = null;
            $tipo = null;
            $datosTranscripcion = '-';
            switch ($mov->id_identificador_tabla) {
                case 1:  // INGRESO
                    $dato = Ingreso::with('procesoIngreso')->find($mov->id_corresponde);
                    $tipo = $dato ? 'INGRESO - ' . $dato->procesoIngreso->sigla ?? '' : '-';
                    $codigo = $dato ?  $dato->codigo_ingreso : '-';
                    break;

                case 2:  // SOLICITUD
                    $dato = Solicitud::find($mov->id_corresponde);
                    $tipo = 'SOL. ALM. GENERAL';
                    $codigo = $dato->cod_solicitud;
                    break;

                case 4:  // CIERRE GESTION
                    $dato = CierreGestion::find($mov->id_corresponde);
                    $tipo = $dato ? 'CIERRE DE GESTIÓN' : '-';
                    $codigo = $dato ? $dato->id : '-';
                    break;

                case 5:  // REINGRESO
                    $dato = Reingreso::find($mov->id_corresponde);
                    $tipo = $dato ? 'REINGRESO' : '-';
                    $codigo = $dato ? $dato->codigo_reingreso : '-';
                    break;

                default:
                    $codigo = '-';
            }

            // Construir array de datos del movimiento
            return [
                'fecha'       => Carbon::parse($mov->fecha)->format('d/m/Y'),
                'solicitante' => $mov->dato_solicitante ?? '-',
                'tipo' => $tipo,
                'codigo'      => $codigo,
                'interesado'  => $mov->dato_interesado ?? '-',
                'ingreso'     => $mov->cantidad_ingreso,
                'salida'      => $mov->cantidad_salida,
                'saldo'       => $mov->saldo,
                'datos_transcripcion' => $datosTranscripcion,
                'observacion' => $mov->observacion ?? '-',
            ];
        });

        $numero_registros = $movimientos->count();
        $user_name        = auth()->user()->name;
        $fecha_actual     = Carbon::now()->format('d/m/Y');

        $vistaPdf = 'AlmacenGeneral.BindCard.pdfBindCard';

        // Generar PDF
        $pdf = Pdf::loadView($vistaPdf, [
            'datos'               => $datos,
            'nombre_material'     => $nombre_material,
            'unidad'              => $unidad,
            'numero_registros'    => $numero_registros,
            'cod_material'        => $cod_material,
            'fecha_inicio'        => $fecha_inicio,
            'fecha_fin'           => $fecha_fin,
            'fecha_actual'        => $fecha_actual,
            'user_name'           => $user_name,
            'unidad_llega'        => $unidad,
        ])->setPaper('letter', 'landscape');

        $dompdf = $pdf->getDomPDF();
        $dompdf->render();
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont("DejaVu Sans", "normal");

        //Mostrar numeracion de pagina
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($font) {
            $text = "Página $pageNumber de $pageCount";
            $size = 7;
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $x = $canvas->get_width() - $width - 20;
            $y = $canvas->get_height() - 60;

            $canvas->text($x, $y, $text, $font, $size, [0, 0, 0]);
        });


        $fileName = 'BindCardAlmacenGeneral' . $cod_material . '.pdf';

        return $pdf->stream($fileName, [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    public static function obtenerIdAlmacenGeneral()
    {
        /*$datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];*/
        return 19;
    }



    public function exportarExcel($fecha_inicio, $fecha_fin, $id_material, $unidad)
    {
        $fecha_inicio = Carbon::parse($fecha_inicio)->startOfDay();
        $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();

        $unidades = [
            'ALMACEN GENERAL' => self::obtenerIdAlmacenGeneral(),
        ];
        $id_unidad = $unidades[$unidad] ?? null;

        $material = Material::findOrFail($id_material);

        $movimientos = Movimiento::with('usuarioCreador')
            ->where('id_material', $id_material)
            ->where('id_unidad', $id_unidad)
            // ->where('id_corresponde', 8086) // PARA PRUEBAS DEL CAMPO RECETA
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('fecha', 'asc')
            ->get();
        // dd($movimientos);
        $datos = $movimientos->map(function ($mov, $index) {
            $tipo = '-';
            $codigo = '-';
            $datosTranscripcion = '-';
            match ($mov->id_identificador_tabla) {
                // INGRESO
                1 => tap(Ingreso::with('procesoIngreso')->find($mov->id_corresponde), function ($dato) use (&$tipo, &$codigo) {
                    if ($dato) {
                        $tipo   = 'INGRESO ' . $dato->procesoIngreso->sigla ?? '';
                        $codigo = $dato ?  $dato->cod_ingreso : '-';
                    }
                }),

                // SOLICITUD
                2 => tap(Solicitud::find($mov->id_corresponde), function ($dato) use (&$tipo, &$codigo) {
                    if ($dato) {
                        $tipo   = 'SOL. ALM. GENERAL';
                        $codigo = $dato->cod_solicitud;
                    }
                }),

                // CIERRE GESTION
                4 => tap(CierreGestion::find($mov->id_corresponde), function ($dato) use (&$tipo, &$codigo) {
                    if ($dato) {
                        $tipo   = 'CIERRE DE GESTIÓN';
                        $codigo = $dato->id;
                    }
                }),

                // REINGRESO
                5 => tap(Reingreso::find($mov->id_corresponde), function ($dato) use (&$tipo, &$codigo) {
                    if ($dato) {
                        $tipo   = 'REINGRESO';
                        $codigo = $dato->codigo_reingreso;
                    }
                }),

                default => null,
            };

            return [
                $index + 1,
                Carbon::parse($mov->fecha)->format('d/m/Y'),
                $mov->dato_solicitante ?? '-',
                $tipo,
                $codigo,
                $mov->dato_interesado ?? '-',
                $mov->cantidad_ingreso,
                $mov->cantidad_salida,
                $mov->saldo,
                $mov->observacion ?? '-',
            ];
        })->toArray();
        // dd($datos);
        $info_material = [
            'cod_material' => $material->codigo,
            'nombre_material' => $material->nombre_material,
            'unidad_material' => $material->unidad,
            'numero_registros' => $movimientos->count(),
            'fecha_inicio' => $fecha_inicio->format('d/m/Y'),
            'fecha_fin' => $fecha_fin->format('d/m/Y'),
            'fecha_actual' => now()->format('d/m/Y'),
            'user_name' => auth()->user()->name,
            'unidad' => $unidad
        ];

        $fileName = 'BINDCARD-ALMACEN-GENERAL ' . $material->codigo . '.xlsx';

        return Excel::download(new BindcardAlmacenGeneralExport($datos, $info_material), $fileName);
    }
}
