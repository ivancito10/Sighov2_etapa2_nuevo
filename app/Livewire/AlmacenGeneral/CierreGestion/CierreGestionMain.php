<?php

namespace App\Livewire\AlmacenGeneral\CierreGestion;

use App\Models\Administracion\Persona;
use App\Models\AlmacenGeneral\CierreGestion;
use App\Models\AlmacenGeneral\Movimiento;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use Livewire\Attributes\On;
use Livewire\Component;

class CierreGestionMain extends Component
{
    public $id_cierre,
        $cantidad_fisica,
        $cantidad_sistema,
        $fecha_registro,
        $diferencia,
        $id_unidad,
        $id_material,
        $created_at,
        $updated_at,
        $id_user_created,
        $id_user_updated,
        $codigo,
        $nombre_material,
        $fecha_vencimiento,
        $fecha_vencimiento_baja,
        $unidad,
        $descripcion, $cantidad_bajas, $stock_actual, $diferencia_mostrar;
    public $precio_unitario;
    public $idAlmacenGeneral;

    public $unidadInventariador, $cargoInventariador, $nombre, $carnet;

    public function render()
    {
        return view('livewire.almacen-general.cierre-gestion.cierre-gestion-main');
    }

    public function mount()
    {
        $this->idAlmacenGeneral = $this->obtenerIdAlmacen();
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    #[On('verCierreAlmacenGeneral')]
    public function verCierreAlmacenGeneral($data)
    {
        //dd('sfds');
        $this->id_cierre = $data;
        $this->mostrarCierre($data);
        $this->dispatch('verCierreAlmacenGeneralModalcito');
    }

    #[On('agregarPrecioAlmGeneral')]
    public function agregarPrecioAlmGeneral($data)
    {
        // $this->id_cierre = $data;
        // dd('entro');
        $this->mostrarCierre($data);
        $this->dispatch('abrirModalPrecioAlmGeneral');
    }

    #[On('registrarPrecioAlmGeneral')]
    public function registrarPrecioAlmGeneral()
    {
        if ($this->precio_unitario == null) {
            $this->dispatch('olvidaDatosAlmGeneral');
            return;
        }
    }

    public function obtenerIdAlmacen()
    {
        /*$datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];*/
        return 19;
    }

    public function mostrarCierre($id)
    {
        $cierre = CierreGestion::with(['materialCierreGestion.materialIngresoMaterial'])->find($id);
        $this->id_cierre = $cierre->id;
        $this->cantidad_fisica = $cierre->cantidad_fisica;
        $this->cantidad_sistema = $cierre->cantidad_sistema;
        $this->cantidad_bajas = $cierre->cantidad_bajas;
        $this->fecha_registro = $cierre->fecha_registro;
        $this->fecha_vencimiento = $cierre->fecha_vencimiento;
        $this->fecha_vencimiento_baja = $cierre->fecha_vencimiento_baja;
        $this->diferencia = $cierre->diferencia;
        $this->diferencia_mostrar = abs($this->diferencia);
        $this->id_unidad = $cierre->id_unidad;
        $this->id_material = $cierre->id_material;
        $this->created_at = $cierre->created_at;
        $this->updated_at = $cierre->updated_at;
        $this->id_user_created = $cierre->id_user_created;
        $this->id_user_updated = $cierre->id_user_updated;
        $this->codigo = $cierre->materialCierreGestion->codigo;
        $this->nombre_material = $cierre->materialCierreGestion->nombre_material;
        $this->unidad = $cierre->materialCierreGestion->unidad;
        $this->descripcion = $cierre->materialCierreGestion->descripcion;
        //datos persona
        $personaData = $this->obtenerDatosPersona($cierre->usuarioCreador->id_persona);
        $this->unidadInventariador = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoInventariador = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
        $persona = Persona::where('id', $cierre->usuarioCreador->id_persona)->first();
        $this->nombre = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
        $this->carnet = $persona->ci;

        $stock = Movimiento::where('id_material', $cierre->id_material)
            ->where('id_unidad', $this->idAlmacenGeneral)
            ->orderBy('fecha', 'desc')
            ->first();

        $this->stock_actual = $stock->saldo ?? 0;
    }

    #[On('reporteCierreAlmacenGeneral')]
    public function reporteCierreAlmacenGeneral()
    {
        //dd("Llega");
        //$unidad = 'ALMACEN GENERAL';
        $url = route('almacen_general.cierre_gestion.pdf');
        $this->js("window.open('{$url}', '_blank');");
    }

    #[On('reporteExcelCierreAlmacenGeneral')]
    public function reporteExcelCierreAlmacenGeneral()
    {
        //$unidad = 'ALMACEN GENERAL';
        $url = route('almacen_general.cierre_gestion.excel');
        $this->js("window.open('{$url}', '_blank');");
    }
}
