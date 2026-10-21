<?php

namespace App\Http\Controllers\Farmacia;

use App\Exports\BindcardExport;
use App\Http\Controllers\Controller;
use App\Models\Farmacia\Ingreso;
use App\Models\Farmacia\Reingreso;
use App\Models\Farmacia\CierreGestion;
use App\Models\ConsultaExterna\Receta;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Movimiento;
use App\Models\Farmacia\Solicitud;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosUnidadPorId;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Exports\MedicamentosExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Services\QRCodeService;


class PDFBindcardController extends Controller
{
    public static function generarPDFBindcard($fecha_inicio, $fecha_fin, $id_medicamento, $unidad)
    {
        $fecha_inicio = Carbon::parse($fecha_inicio)->startOfDay();
        $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();

        $unidades = [
            'FARMACIA'       => self::obtenerIdFarmacia(),
            'ALMACEN DE FARMACIA' => self::obtenerIdAlmacen(),
        ];
        $id_unidad = $unidades[$unidad] ?? null;

        $medicamento = Medicamento::findOrFail($id_medicamento);
        $cod_medicamento     = $medicamento->codigo_medicamento;
        $nombre_medicamento  = $medicamento->nombre;
        $forma_farmaceutica  = $medicamento->forma_farmaceutica;

        $movimientos = Movimiento::with(['receta', 'usuarioCreador'])
            ->where('id_medicamento', $id_medicamento)
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
                    $codigo = $dato ?  $dato->cod_ingreso : '-';
                    break;

                case 2:  // SOLICITUD
                    $dato = Solicitud::find($mov->id_corresponde);
                    $tipo = $dato->farmacia ? 'SOL. FARM' : 'SOL. INSUMOS';
                    $codigo = $dato->cod_solicitud;
                    break;

                case 3: // RECETA
                    $dato = $mov->receta;
                    if ($dato) {
                        $tipo   = 'RECETA';
                        $codigo = $dato->nro_receta ?? '-';

                        if ($dato->manual) {
                            $usuario = $mov->usuarioCreador
                                ? $mov->usuarioCreador->name
                                : 'No definido';

                            $datosTranscripcion =
                                'Receta Manual, número de receta manual: ' .
                                ($dato->nro_receta_manual ?? '-') .
                                ', transcrita en fecha ' .
                                optional($dato->created_at)->format('d/m/Y') .
                                ', por: ' . $usuario;
                        }
                    }
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

        $vistaPdf = ($unidad === 'FARMACIA')
            ? 'Farmacia.Farmacia.BindCard.pdfBindCard'
            : 'Farmacia.AlmacenFarmacia.BindCard.pdfBindCard';

        // Generar PDF
        $pdf = Pdf::loadView($vistaPdf, [
            'datos'               => $datos,
            'nombre_medicamento'  => $nombre_medicamento,
            'forma_farmaceutica'  => $forma_farmaceutica,
            'numero_registros'    => $numero_registros,
            'cod_medicamento'     => $cod_medicamento,
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


        $fileName = 'BindCardFarmacia' . $cod_medicamento . '.pdf';

        return $pdf->stream($fileName, [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }

    public static function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    public static function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }



    public function exportarExcel($fecha_inicio, $fecha_fin, $id_medicamento, $unidad)
    {
        $fecha_inicio = Carbon::parse($fecha_inicio)->startOfDay();
        $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();

        $unidades = [
            'FARMACIA' => self::obtenerIdFarmacia(),
            'ALMACEN DE FARMACIA' => self::obtenerIdAlmacen(),
        ];
        $id_unidad = $unidades[$unidad] ?? null;

        $medicamento = Medicamento::findOrFail($id_medicamento);

        $movimientos = Movimiento::with([
            'usuarioCreador',
            'receta.personaReceta',
            'receta.recetaEspecialista.especialidadEspecialista',
            'receta.receta_historiaClinica.EspecialidadHistoriaClinica'
        ])
            ->where('id_medicamento', $id_medicamento)
            ->where('id_unidad', $id_unidad)
            // ->where('id_corresponde', 8086) // PARA PRUEBAS DEL CAMPO RECETA
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->orderBy('fecha', 'asc')
            ->get();
        // dd($movimientos);
        $datos = $movimientos->map(function ($mov, $index) {
            $tipo = '-';
            $codigo = '-';
            $especialidad = '-';
            $diagnostico = '-';
            $datosTranscripcion = '-';
            $matriculaMostrar = '-';
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
                        $tipo   = $dato->farmacia ? 'SOL. FARMACIA' : 'SOL. INSUMOS';
                        $codigo = $dato->cod_solicitud;
                    }
                }),

                // RECETA
                3 => tap(Receta::find($mov->id_corresponde), function ($dato) use (&$tipo, &$codigo, &$datosTranscripcion, $mov, &$especialidad, &$diagnostico, &$matriculaMostrar) {
                    if ($dato) {
                        $tipo   = 'RECETA';
                        $codigo = $dato->nro_receta;

                        if ($dato->manual) {
                            $usuario = $mov->usuarioCreador
                                ? $mov->usuarioCreador->name
                                : 'No definido';

                            $datosTranscripcion =
                                'Receta Manual, número de receta manual: ' .
                                ($dato->nro_receta_manual ?? '-') .
                                ', transcrita en fecha ' .
                                optional($dato->created_at)->format('d/m/Y') .
                                ', por: ' . $usuario;
                            // dd($datosTranscripcion);
                        }
                        if ($mov->stock_real === false) {
                            $mov->stock_real = 'RESERVADO';
                        } else if ($mov->stock_real === true) {
                            $mov->stock_real = 'ENTREGADO';
                        }

                        $resultado = $this->buscarReceta($dato);

                        //dd($resultado);
                        if ($resultado) {
                            $especialidad = $resultado['especialidad'];
                            $diagnostico = $resultado['diagnostico'];
                            $matriculaMostrar = $resultado['matricula'];
                        }
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
                $matriculaMostrar,
                $tipo,
                $codigo,
                $mov->dato_interesado ?? '-',
                $especialidad,
                $diagnostico,
                $mov->cantidad_ingreso,
                $mov->cantidad_salida,
                $mov->saldo,
                $datosTranscripcion,
                $mov->observacion ?? '-',
                $mov->stock_real ?? '-'
            ];
        })->toArray();
        // dd($datos);
        $info_medicamento = [
            'cod_medicamento' => $medicamento->codigo_medicamento,
            'nombre_medicamento' => $medicamento->nombre,
            'forma_farmaceutica' => $medicamento->forma_farmaceutica,
            'numero_registros' => $movimientos->count(),
            'fecha_inicio' => $fecha_inicio->format('d/m/Y'),
            'fecha_fin' => $fecha_fin->format('d/m/Y'),
            'fecha_actual' => now()->format('d/m/Y'),
            'user_name' => auth()->user()->name,
            'unidad' => $unidad
        ];
        // dd($datos, $info_medicamento);

        $fileName = ($unidad === 'FARMACIA') ? 'BINDCARD-FARMACIA ' . $medicamento->codigo_medicamento . '.xlsx' : 'BINDCARD-ALMACEN-MEDICAMENTOS ' . $medicamento->codigo_medicamento . '.xlsx';

        return Excel::download(new BindcardExport($datos, $info_medicamento), $fileName);
    }

    /*
    public function buscarReceta($idReceta)
    {
        $receta = Receta::find($idReceta);

        if (!$receta) {
            return null;
        }

        if ($receta->manual) {
            $diagnostico = 'SIN DIAGNOSTICO';
            $especialidad = $receta->recetaEspecialista->especialidadEspecialista->especialidad;
        } else {
            $especialidad = $receta->receta_historiaClinica?->EspecialidadHistoriaClinica?->especialidad;
            $diagnostico = $receta->receta_historiaClinica?->descriptivo;
        }

        return [
            'especialidad' => $especialidad ?? 'SIN ESPECIALIDAD',
            'diagnostico'  => $diagnostico ?? 'SIN DIAGNOSTICO'
        ];
    }*/

    public function buscarReceta($receta)
    {
        if (!$receta) {
            return null;
        }

        if ($receta->manual) {
            $diagnostico = 'SIN DIAGNOSTICO';
            $especialidad = $receta->recetaEspecialista->especialidadEspecialista->especialidad;
        } else {
            $especialidad = $receta->receta_historiaClinica?->EspecialidadHistoriaClinica?->especialidad;
            $diagnostico = $receta->receta_historiaClinica?->descriptivo;
        }

        return [
            'especialidad' => $especialidad ?? 'SIN ESPECIALIDAD',
            'diagnostico'  => $diagnostico ?? 'SIN DIAGNOSTICO',
            'matricula'    => $receta->personaReceta?->matricula_seguro ?? '-'
        ];
    }
}
