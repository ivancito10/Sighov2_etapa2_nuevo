<?php

namespace App\Http\Controllers\Farmacia;

use App\Exports\CierreGestionExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Administracion\Persona;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Farmacia\CierreGestion;
use App\Services\ListaUnidades;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CierreGestionController extends Controller
{
    public function reporteCierreGestionFarmacia($unidad)
    {
        ini_set('memory_limit', '10024M');
        ini_set('max_execution_time', 12000);

        $unidades = [
            'FARMACIA'       => self::obtenerIdFarmacia(),
            'ALMACEN DE FARMACIA' => self::obtenerIdAlmacen(),
        ];
        $idUnidad = $unidades[$unidad] ?? null;

        $nombreUsuario = auth()->user()->name;
        $idPersona = auth()->user()->id_persona;
        $persona = Persona::find($idPersona);
        $ci = $persona->ci;
        $idUsuario = auth()->user()->id;
        $fechaActual     = Carbon::now()->format('d/m/Y H:i:s');

        $cierreGestion = CierreGestion::with(['medicamentoCierreGestion', 'usuarioCreador', 'usuarioEditor'])
            ->where('id_unidad', $idUnidad)
            //->where('id_user_created', $idUsuario)
            ->get();

        $vistaPdf = ($unidad === 'FARMACIA')
            ? 'Farmacia.Farmacia.CierreGestion.reportePdfCierreGestion'
            : 'Farmacia.AlmacenFarmacia.CierreGestion.reportePdfCierreGestion';

        $pdf = Pdf::loadView($vistaPdf, [
            'cierreGestion' => $cierreGestion,
            'ci' => $ci,
            'fechaActual' => $fechaActual,
            'nombreUsuario' => $nombreUsuario
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

        $fileName = 'ReporteCierre.pdf';

        return $pdf->stream($fileName, [
            'Attachment' => false,
            'Content-Disposition' => '  '
        ]);
    }

    public function reporteCierreGestionExcel($unidad)
    {
        $unidades = [
            'FARMACIA' => self::obtenerIdFarmacia(),
            'ALMACEN DE FARMACIA' => self::obtenerIdAlmacen(),
        ];
        $idUnidad = $unidades[$unidad] ?? null;

        $nombreUsuario = auth()->user()->name;
        $idPersona = auth()->user()->id_persona;
        $persona = Persona::find($idPersona);
        $ci = $persona->ci;
        $idUsuario = auth()->user()->id;
        $fechaActual = Carbon::now()->format('d/m/Y H:i:s');

        $cierreGestion = CierreGestion::with(['medicamentoCierreGestion', 'usuarioCreador', 'usuarioEditor'])
            ->where('id_unidad', $idUnidad)
            //->where('id_user_created', $idUsuario)
            ->get();


        $dato = $cierreGestion->map(function ($item, $index) {
            return [
                $index + 1,
                $item->fecha_registro ? Carbon::parse($item->fecha_registro)->format('d/m/Y') : '-',
                optional($item->medicamentoCierreGestion)->codigo_medicamento ?? '-',
                optional($item->medicamentoCierreGestion)->nombre ?? '-',
                $item->fecha_vencimiento ? Carbon::parse($item->fecha_vencimiento)->format('d/m/Y') : '-',
                optional($item->medicamentoCierreGestion)->forma_farmaceutica ?? '-',
                (int)$item->cantidad_fisica,
                (int)$item->cantidad_sistema,
                (int)$item->cantidad_bajas,
                $item->fecha_vencimiento_baja ? Carbon::parse($item->fecha_vencimiento_baja)->format('d/m/Y') : '-',
            ];
        })->toArray();

        $infoCierreGestion = [
            'nombreUsuario' => $nombreUsuario,
            'ci' => $ci,
            'fechaActual' => $fechaActual,
            'unidad' => $unidad,
        ];

        $fileName = ($unidad === 'FARMACIA') ? 'CIERRE-GESTION-FARMACIA.xlsx' : 'CIERRE-GESTION-ALMACEN-MEDICAMENTOS.xlsx';
        // Descargar archivo Excel
        return Excel::download(new CierreGestionExport($dato, $infoCierreGestion), $fileName);
    }

    public static function obtenerIdAlmacen()
    {
        // $datosUnidades = new ListaUnidades();
        // return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
        return 89;
    }

    public static function obtenerIdFarmacia()
    {
        // $datosUnidades = new ListaUnidades();
        // return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
        return 76;
    }
}
