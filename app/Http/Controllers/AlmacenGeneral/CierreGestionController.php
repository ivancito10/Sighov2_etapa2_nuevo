<?php

namespace App\Http\Controllers\AlmacenGeneral;

use App\Exports\AlmacenGeneral\CierreGestionAlmacenGeneralExport;
use App\Http\Controllers\Controller;
use App\Models\Administracion\Persona;
use App\Models\AlmacenGeneral\CierreGestion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CierreGestionController extends Controller
{
    private const ID_UNIDAD_ALMACEN_GENERAL = 19;

    public function pdfCierreGestionAlmacenGeneral()
    {
        // Extender límites para reportes voluminosos
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);

        try {
            //Obtención de datos del usuario autenticado
            $user = auth()->user();
            $nombreUsuario = $user->name ?? 'USUARIO S/D';
            $ci = 'S/C';

            if ($user && $user->id_persona) {
                $persona = Persona::find($user->id_persona);
                $ci = $persona->ci ?? 'S/C';
            }

            $fechaActual = Carbon::now()->format('d/m/Y H:i:s');

            //Consulta filtrada por id_unidad = 19 Almacen general
            $cierreGestion = CierreGestion::with([
                'materialCierreGestion',
                'usuarioCreador',
                'usuarioEditor'
            ])
                ->where('id_unidad', self::ID_UNIDAD_ALMACEN_GENERAL)
                ->get();

            //dd($cierreGestion);
            //Carga y renderizado de la vista en PDF
            $vistaPdf = 'AlmacenGeneral.CierreGestion.reportePdfCierreGestion';

            $pdf = Pdf::loadView($vistaPdf, [
                'cierreGestion' => $cierreGestion,
                'ci'            => $ci,
                'fechaActual'   => $fechaActual,
                'nombreUsuario' => $nombreUsuario
            ])->setPaper('letter', 'landscape');

            $dompdf = $pdf->getDomPDF();
            $dompdf->render();

            //Numeracion de pagina
            $canvas = $dompdf->getCanvas();
            $font = $dompdf->getFontMetrics()->getFont("DejaVu Sans", "normal");

            $canvas->page_script(function ($pageNumber, $pageCount, $canvasInstance, $fontMetrics) use ($font) {
                $text = "Página {$pageNumber} de {$pageCount}";
                $size = 7;
                $width = $fontMetrics->getTextWidth($text, $font, $size);
                $x = $canvasInstance->get_width() - $width - 20;
                $y = $canvasInstance->get_height() - 60;

                $canvasInstance->text($x, $y, $text, $font, $size, [0, 0, 0]);
            });

            $fileName = 'Reporte_Cierre_Gestion_Almacen_General.pdf';

            //Retorno de respuesta en flujo PDF
            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename=\"{$fileName}\"");
        } catch (\Throwable $th) {
            Log::error("Error al generar PDF de Cierre de Gestión (Almacén General - ID 19): " . $th->getMessage(), [
                'exception' => $th
            ]);

            abort(500, "Ocurrió un error al generar el reporte de Cierre de Gestión.");
        }
    }

    /*
    public function excelCierreGestionAlmacenGeneral($unidad)
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
*/
    public function excelCierreGestionAlmacenGeneral()
    {
        try {
            $user = auth()->user();
            $nombreUsuario = $user->name ?? 'USUARIO S/D';
            $ci = 'S/C';

            if ($user && $user->id_persona) {
                $persona = Persona::find($user->id_persona);
                $ci = $persona->ci ?? 'S/C';
            }

            $fechaActual = Carbon::now()->format('d/m/Y H:i:s');

            $cierreGestion = CierreGestion::with(['materialCierreGestion', 'usuarioCreador', 'usuarioEditor'])
                ->where('id_unidad', self::ID_UNIDAD_ALMACEN_GENERAL)
                ->get();
            //dd($cierreGestion);
            $dato = $cierreGestion->map(function ($item, $index) {
                return [
                    $index + 1,
                    $item->fecha_registro ? Carbon::parse($item->fecha_registro)->format('d/m/Y') : '-',
                    optional($item->materialCierreGestion)->codigo ?? '-',
                    optional($item->materialCierreGestion)->nombre_material ?? '-',
                    $item->fecha_vencimiento ? Carbon::parse($item->fecha_vencimiento)->format('d/m/Y') : '-',
                    optional($item->materialCierreGestion)->unidad ?? '-',
                    (int) ($item->cantidad_fisica ?? 0),
                    (int) ($item->cantidad_sistema ?? 0),
                    (int) ($item->cantidad_bajas ?? 0),
                    $item->fecha_vencimiento_baja ? Carbon::parse($item->fecha_vencimiento_baja)->format('d/m/Y') : '-',
                ];
            })->toArray();

            $infoCierreGestion = [
                'nombreUsuario' => $nombreUsuario,
                'ci'            => $ci,
                'fechaActual'   => $fechaActual,
                'unidad'        => 'ALMACEN GENERAL',
            ];

            return Excel::download(
                new CierreGestionAlmacenGeneralExport($dato, $infoCierreGestion),
                'CIERRE-GESTION-ALMACEN-GENERAL.xlsx'
            );
        } catch (\Throwable $th) {
            Log::error("Error al generar Excel de Cierre de Gestión (Almacén General): " . $th->getMessage(), [
                'exception' => $th
            ]);

            return back()->with('error', 'Ocurrió un error al exportar el archivo Excel.');
        }
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
