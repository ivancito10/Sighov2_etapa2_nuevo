<?php

namespace App\Http\Controllers\AtencionMedica\Laboratorio;

use App\Http\Controllers\Controller;
use App\Services\DatosEspecialista;
use App\Services\LaboratorioResultados;
use Carbon\Carbon;

class LaboratorioResultController extends Controller
{
    public function resultados_pdf($id)
    {
        $laboratorio = new LaboratorioResultados();
        $datos = $laboratorio->datosLaboratorioResultados($id);

        //dd($datos);
        $solicitud = $datos['solicitud'];
        // dd($solicitud);
        //dd($solicitud);
        $parametro = $datos['parametro'];
        // dd($parametro);
        $fecha_pdf = $datos['fecha_pdf'];
        $dato_fecha_modificacion = $datos['fecha_modificacion'];

        $ci = $datos['solicitud']['persona_solicitante']['numero_identificacion'];

        $especialista = new DatosEspecialista();
        $dato_especialidad = $especialista->datosEspecialista_ci($ci);
        $nombre_especialidad = $dato_especialidad['especialidad_especialista']['especialidad'];
        //dd($nombre_especialidad);
        // dd($solicitud, $parametro, $fecha_pdf, $nombre_especialidad, $dato_fecha_modificacion);
        $view = \View::make(
            'AtencionMedica.Laboratorio.pdfLaboratorioResultados',
            compact('solicitud', 'parametro', 'fecha_pdf', 'nombre_especialidad', 'dato_fecha_modificacion')
        )->render();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('letter', 'portrait');
        $pdf->loadHTML($view);

        return $pdf->stream("resultados_{$id}.pdf");
    }

    //Aqui el controlador
    // public function resultados_pdf($id)
    // {
    //     //dd($id);
    //     $laboratorio = new LaboratorioResultados();
    //     $datos = $laboratorio->datosLaboratorioResultados($id);
    //     //dd($datos);

    //     $solicitud = $datos['solicitud'];
    //     //dd($solicitud['id_solicitud']);

    //     $parametro = $datos['parametro'];
    //     //dd($solicitud, $parametro);
    //     //dd($parametro);

    //     setlocale(LC_TIME, 'Spanish');
    //     Carbon::setUtf8(true);
    //     //$fecha_pdf = Carbon::now()->formatLocalized('%A %d %B %Y %H:%M:%S');
    //     $fecha_pdf = $datos['fecha_pdf'];

    //     $view = \View::make('AtencionMedica.Laboratorio.pdfLaboratorioResultados', compact('solicitud', 'parametro', 'fecha_pdf'))->render();

    //     $pdf = \App::make('dompdf.wrapper');
    //     $pdf->setPaper('letter', 'portrait');
    //     $pdf->loadHTML($view);

    //     return $pdf->stream("resultados_{$id}.pdf");

    // }
}
