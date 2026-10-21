<?php

namespace App\Livewire\Farmacia\Farmacia\CierreGestion;

use App\Models\Farmacia\CierreGestion;
use App\Models\Farmacia\DetalleIngreso;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use App\Models\Farmacia\Movimiento;
use App\Models\Administracion\Persona;

class CierreGestionMain extends Component
{
    public $id_cierre,
        $cantidad_fisica,
        $cantidad_sistema,
        $fecha_registro,
        $diferencia, $diferencia_mostrar,
        $id_unidad,
        $id_medicamento,
        $created_at,
        $updated_at,
        $id_user_created,
        $id_user_updated,
        $codigo_medicamento,
        $nombre_medicamento,
        $forma_farmaceutica,
        $concentracion, $cantidad_bajas, $stock_actual, $fecha_vencimiento, $fecha_vencimiento_baja;
    public $precio_unitario;

    //datos persona
    public $unidadInventariador, $cargoInventariador, $nombre, $carnet;

    public function render()
    {
        return view('livewire.farmacia.farmacia.cierre-gestion.cierre-gestion-main');
    }

    public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }

    public function mostrarCierre($id)
    {
        $cierre = CierreGestion::with(['medicamentoCierreGestion.detalleIngresoMedicamento'])->find($id);
        $this->id_cierre = $cierre->id;
        $this->cantidad_fisica = $cierre->cantidad_fisica;
        $this->cantidad_sistema = $cierre->cantidad_sistema;
        $this->cantidad_bajas = $cierre->cantidad_bajas;
        $this->fecha_registro = $cierre->fecha_registro;
        $this->diferencia = $cierre->diferencia;
        $this->diferencia_mostrar = abs($this->diferencia);
        $this->fecha_vencimiento = $cierre->fecha_vencimiento;
        $this->fecha_vencimiento_baja = $cierre->fecha_vencimiento_baja;
        $this->id_unidad = $cierre->id_unidad;
        $this->id_medicamento = $cierre->id_medicamento;
        $this->created_at = $cierre->created_at;
        $this->updated_at = $cierre->updated_at;
        $this->id_user_created = $cierre->id_user_created;
        $this->id_user_updated = $cierre->id_user_updated;
        $this->codigo_medicamento = $cierre->medicamentoCierreGestion->codigo_medicamento;
        $this->nombre_medicamento = $cierre->medicamentoCierreGestion->nombre;
        $this->forma_farmaceutica = $cierre->medicamentoCierreGestion->forma_farmaceutica;
        $this->concentracion = $cierre->medicamentoCierreGestion->concentracion;
        $stock = Movimiento::where('id_medicamento', $this->id_medicamento)
            ->where('id_unidad', $this->obtenerIdFarmacia())
            ->orderBy('id', 'desc')
            ->first();
        $this->stock_actual = $stock->saldo ?? 0;
        //datos persona
        $personaData = $this->obtenerDatosPersona($cierre->usuarioCreador->id_persona);
        $this->unidadInventariador = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoInventariador = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
        $persona = Persona::where('id', $cierre->usuarioCreador->id_persona)->first();
        $this->nombre = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
        $this->carnet = $persona->ci;
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    #[On('verCierre')]
    public function verCierre($data)
    {
        $this->id_cierre = $data;
        // dd('entro');
        $this->mostrarCierre($data);
        $this->dispatch('verCierreFarmacia');
    }
    #[On('agregarPrecio')]
    public function agregarPrecio($data)
    {
        // $this->id_cierre = $data;
        // dd('entro');
        $this->mostrarCierre($data);
        $this->dispatch('abrirModalPrecioFarmacia');
    }

    #[On('registrarPrecio')]
    public function registrarPrecio()
    {
        if ($this->precio_unitario == null) {
            $this->dispatch('olvidaDatosFarmacia');
            return;
        }
    }


    #[On('reporteCierreFarmacia')]
    public function reporteCierreFarmacia()
    {
        $unidad = 'FARMACIA';
        $url = route('farmacia.farmacia.cierre-gestion.pdf', ['unidad' => $unidad]);
        $this->js("window.open('{$url}', '_blank');");
    }

    #[On('reporteExcelCierreFarmacia')]
    public function reporteExcelCierreFarmacia()
    {
        $unidad = 'FARMACIA';
        $url = route('farmacia.farmacia.cierre-gestion.excel', ['unidad' => $unidad]);
        $this->js("window.open('{$url}', '_blank');");
    }
}
