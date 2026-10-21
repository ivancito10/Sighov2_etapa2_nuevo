<?php

namespace App\Livewire\Administracion\VerificacionDocumentos;

use Livewire\Component;
use App\Models\Administracion\DocumentoPDF;
use App\Models\Administracion\TipoDocumento;
use App\Models\Administracion\Persona;
use Carbon\Carbon;

class VerificarDocumento extends Component
{
    public $codigo_seguridad;
    public $documento;
    public $vigente;
    public $tipoDocumentoNombre;
    public $tipoDocumentoDescripcion;

    //datos Persona
    public $nombres;
    public $apellidos;
    public $fecha_formateada;
    public $hora_formateada;
    public function mount($codigo_seguridad)
    {
        $this->codigo_seguridad = $codigo_seguridad;

        // Buscar el documento
        $this->documento = DocumentoPDF::where('codigo_seguridad', $this->codigo_seguridad)->first();
        //dd($this->documento);
        if ($this->documento) {
            $fecha = $this->documento->fecha_generado;  // Suponiendo que $documento->fecha_generado contiene la fecha completa
            $this->fecha_formateada = date("d-m-Y", strtotime($fecha));  // Formato día-mes-año
            $this->hora_formateada = date("H:i:s", strtotime($fecha));
            $tipoDocumentoId = $this->documento->id_tipo_documento;
            // Buscar el tipo de documento una sola vez
            $tipoDocumento = TipoDocumento::find($tipoDocumentoId);
            $this->tipoDocumentoNombre = $tipoDocumento->nombre;
            $this->tipoDocumentoDescripcion = $tipoDocumento->descripcion;
            $this->vigente = true;

            //datos persona
            $Datospersona = Persona::find($this->documento->pertenece_a);
            // dd($Datospersona);
            $this->nombres = $Datospersona->nombres;
            $this->apellidos = $Datospersona->p_apellido . ' ' . $Datospersona->s_apellido;

            if (!$this->documento->indefinido) {
                $fechaActual = Carbon::now();
                $this->vigente = $fechaActual->between($this->documento->fecha_vigencia_inicio, $this->documento->fecha_vigencia_fin);
            }
        } else {

            $this->vigente = false;
        }
    }

    public function render()
    {
        return view('livewire.Administracion.verificacion-documentos.verificar-documento');
    }
}
