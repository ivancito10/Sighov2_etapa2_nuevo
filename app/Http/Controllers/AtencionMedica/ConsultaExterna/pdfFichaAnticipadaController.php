<?php

namespace App\Http\Controllers\AtencionMedica\ConsultaExterna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ServicioAgendaGeneral;
use App\Services\ServicioPersonaInstitucion;
use App\Services\ServicioPersona;
use DomPDF;

class pdfFichaAnticipadaController extends Controller
{
    // funcion para generar Pdf
    public function pdfFichaAnticipada($id){
         
        $id_agenda = $id;

        // Servicio de Especialista
            $dato_cita = new ServicioAgendaGeneral();
            $datos_agenda = $dato_cita->obtenerAgendaCitaMedicaPersona($id_agenda);
            
            $agenda = $datos_agenda['data'];
            $id_persona = $agenda['id_persona'];
        // Servicio para mostrar datos tipo asegurado e institucion del asegurado
            $dato_per = new ServicioPersona();
            $datos_persona = $dato_per->datosPersona($id_persona);
            $tipo_asegurado = $datos_persona['tipo_asegurado'];
            $institucion = $datos_persona['institucion'] ;

        // Servicio para mostrar las instituciones de la persona
            $dato_per = new ServicioPersonaInstitucion();
            $persona_institucion = $dato_per->personaInstitucion($id_persona);  
            $instituciones = $persona_institucion['instituciones'];
            $nro_instituciones = count($instituciones);
                // obtener el codigo institucional de la primera institucion 
                // Accedemos al primer elemento del array (índice 0)
                $primera_institucion = $instituciones[0]; 
                // Accedemos a la clave 'codigo_institucional' de ese primer elemento
                $codigo_institucional = $primera_institucion['codigo_institucional'];
         
        // Para abrir el blade vista del pdf con variables ya definidas
        $pdf = DomPDF::loadView(
            'AtencionMedica.ConsultaExterna.pdfFichaAnticipada',
                compact(
                    'id_agenda', 'agenda', 'tipo_asegurado', 'institucion', 'codigo_institucional'
                )
            )
            ->setPaper('letter', 'portrait');
 


        $nombre_pdf = 'pdfFichaAnticipada';
        // Para descargar pdf
        return $pdf->stream($nombre_pdf . '.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline'
        ]);
    }
}
