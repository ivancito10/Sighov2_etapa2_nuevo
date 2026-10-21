<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\CierreGestion;

use App\Models\Administracion\Persona;
use App\Models\Farmacia\CierreGestion;
use Livewire\Component;
use App\Services\ListaUnidades;
use Livewire\Attributes\On;
use App\Models\Farmacia\Movimiento;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\RegistrarCierresDiferenciaCero;

class CierreGestionMain extends Component
{
    public $id_cierre,
        $cantidad_fisica,
        $cantidad_sistema,
        $fecha_registro,
        $diferencia,
        $id_unidad,
        $id_medicamento,
        $created_at,
        $updated_at,
        $id_user_created,
        $id_user_updated,
        $codigo_medicamento,
        $nombre_medicamento,
        $fecha_vencimiento,
        $fecha_vencimiento_baja,
        $forma_farmaceutica,
        $concentracion, $cantidad_bajas, $stock_actual, $diferencia_mostrar;
    public $precio_unitario;
    public $idAlmacenFarmacia;

    //datos persona
    public $unidadInventariador, $cargoInventariador, $nombre, $carnet;
    public function render()
    {

        return view('livewire.farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-main');
    }

    public function mount()
    {
        $this->idAlmacenFarmacia = $this->obtenerIdAlmacen();
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    #[On('verCierreAlmacen')]
    public function verCierreAlmacen($data)
    {
        //dd('sfds');
        $this->id_cierre = $data;
        $this->mostrarCierre($data);
        $this->dispatch('verCierreAlmacenFarmacia');
    }

    #[On('agregarPrecioAlm')]
    public function agregarPrecioAlm($data)
    {
        // $this->id_cierre = $data;
        // dd('entro');
        $this->mostrarCierre($data);
        $this->dispatch('abrirModalPrecioAlmFarmacia');
    }

    #[On('registrarPrecioAlm')]
    public function registrarPrecioAlm()
    {
        if ($this->precio_unitario == null) {
            $this->dispatch('olvidaDatosAlmFarmacia');
            return;
        }
    }

    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    public function mostrarCierre($id)
    {
        $cierre = CierreGestion::with(['medicamentoCierreGestion.detalleIngresoMedicamento'])->find($id);
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
        $this->id_medicamento = $cierre->id_medicamento;
        $this->created_at = $cierre->created_at;
        $this->updated_at = $cierre->updated_at;
        $this->id_user_created = $cierre->id_user_created;
        $this->id_user_updated = $cierre->id_user_updated;
        $this->codigo_medicamento = $cierre->medicamentoCierreGestion->codigo_medicamento;
        $this->nombre_medicamento = $cierre->medicamentoCierreGestion->nombre;
        $this->forma_farmaceutica = $cierre->medicamentoCierreGestion->forma_farmaceutica;
        $this->concentracion = $cierre->medicamentoCierreGestion->concentracion;
        //datos persona
        $personaData = $this->obtenerDatosPersona($cierre->usuarioCreador->id_persona);
        $this->unidadInventariador = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoInventariador = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';
        $persona = Persona::where('id', $cierre->usuarioCreador->id_persona)->first();
        $this->nombre = $persona->nombres . ' ' . $persona->p_apellido . ' ' . $persona->s_apellido;
        $this->carnet = $persona->ci;

        $stock = Movimiento::where('id_medicamento', $cierre->id_medicamento)
            ->where('id_unidad', $this->idAlmacenFarmacia)
            ->orderBy('fecha', 'desc')
            ->first();

        $this->stock_actual = $stock->saldo ?? 0;
    }

    #[On('reporteCierreAlmacenFarmacia')]
    public function reporteCierreAlmacenFarmacia()
    {
        $unidad = 'ALMACEN DE FARMACIA';
        $url = route('farmacia.almacenfarmacia.cierre-gestion.pdf', ['unidad' => $unidad]);
        $this->js("window.open('{$url}', '_blank');");
    }

    #[On('reporteExcelCierreAlmacen')]
    public function reporteExcelCierreAlmacen()
    {
        $unidad = 'ALMACEN DE FARMACIA';
        $url = route('farmacia.alamacenfarmacia.cierre-gestion.excel', ['unidad' => $unidad]);
        $this->js("window.open('{$url}', '_blank');");
    }
}
