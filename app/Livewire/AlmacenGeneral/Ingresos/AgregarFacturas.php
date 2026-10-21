<?php

namespace App\Livewire\AlmacenGeneral\Ingresos;

use Livewire\Attributes\On;
use App\Models\AlmacenGeneral\Factura;
use App\Models\AlmacenGeneral\Ingreso;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AgregarFacturas extends Component
{
    public $facturas = [];
    public  $facturas_eliminadas = [];
    #[Validate]
    public $nro_factura, $fecha_factura, $monto_total_factura, $nota_remision = false;
    public $id_ingreso;

    public function render()
    {
        return view('livewire.almacen-general.ingresos.agregar-facturas');
    }

    #[On('detalleFacturas')]
    public function detalleFacturas($data)
    {
        $this->reseteo();
        $this->mostrarFacturas($data);
        $this->dispatch('modalAgregarFacturas');
    }

    public function rules()
    {
        return
            [
                'nro_factura' => 'required|numeric|gt:0',
                'fecha_factura' => 'required',
                'monto_total_factura' => 'required|gt:0',
            ];
    }

    //#[On('agregarFactura')]
    public function agregarFactura()
    {
        $this->validate();
        $existeFactura = array_column($this->facturas, 'nro_factura');
        if (in_array($this->nro_factura, $existeFactura)) {
            $this->dispatch('facturaYaExiste');
            return;
        }

        $this->facturas[] = [
            'id' => null,
            'nro_factura' => $this->nro_factura,
            'fecha_factura' => $this->fecha_factura,
            'total_factura' => $this->monto_total_factura,
            'nota_remision' => $this->nota_remision,
        ];

        $this->nro_factura = null;
        $this->fecha_factura = null;
        $this->monto_total_factura = null;
        $this->nota_remision = null;

        $this->dispatch('facturaAgregada');
    }




    public function mostrarFacturas($data)
    {
        $ingreso = Ingreso::find($data);
        $this->id_ingreso = $data;
        $facturas_pasadas = Factura::where('id_ingreso', $ingreso->id)
            ->where('estado', true)->get();
        if (!empty($facturas_pasadas)) {
            foreach ($facturas_pasadas as $facturas) {
                $this->facturas[] = [
                    'id' => $facturas->id,
                    'nro_factura' => $facturas->nro_factura,
                    'fecha_factura' => $facturas->fecha,
                    'total_factura' => $facturas->monto_total,
                    'nota_remision' => $facturas->nota_remision,
                ];
            }
        }
    }

    #[On('eliminarFactura')]
    public function eliminarFactura($nroFactura)
    {
        foreach ($this->facturas as $index => $factura) {
            if ($factura['nro_factura'] == $nroFactura) {
                if (!is_null($factura['id'])) {
                    $this->facturas_eliminadas[] = $factura['id'];
                }
                unset($this->facturas[$index]);
                $this->facturas = array_values($this->facturas);
                break;
            }
        }
    }

    #[On('guardarFactura')]
    public function guardarFactura()
    {
        try {
            $ingreso =  Ingreso::find($this->id_ingreso);
            foreach ($this->facturas as $fac) {
                if (is_null($fac['id'])) {
                    $nuevaFac = Factura::create([
                        'nro_factura' => $fac['nro_factura'],
                        'fecha' => $fac['fecha_factura'],
                        'id_ingreso' => $ingreso->id,
                        'id_institucion_externa' => $ingreso->id_institucion_externa,
                        'nota_remision' => $fac['nota_remision'],
                        'estado' => true,
                        'monto_total' => $fac['total_factura'],
                        'id_user_created' => auth()->user()->id,
                    ]);
                }
            }
            if (!empty($this->facturas_eliminadas)) {
                $facturaEmilinada = Factura::whereIn('id', $this->facturas_eliminadas)
                    ->update(['estado' => false]);
            }
            $this->dispatch('factruraCreada');
            return redirect()->route('almacen_general.ingresos.index');
            $this->facturas_eliminadas = [];
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function reseteo()
    {
        $this->reset('facturas', 'nro_factura', 'fecha_factura', 'monto_total_factura', 'nota_remision');
    }
}
