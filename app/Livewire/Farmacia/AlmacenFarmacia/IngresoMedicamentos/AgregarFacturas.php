<?php

namespace App\Livewire\Farmacia\AlmacenFarmacia\IngresoMedicamentos;

use Livewire\Attributes\On;
use App\Models\Farmacia\Factura;
use App\Models\Farmacia\Ingreso;
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
        return view('livewire.farmacia.almacen-farmacia.ingreso-medicamentos.agregar-facturas');
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
                'nro_factura' => 'required | gt:0',
                'fecha_factura' => 'required',
                'monto_total_factura' => 'required | gt:0',
            ];
    }


    public function agregarFactura()
    {
        // dd($this->nota_remision, $this->nro_factura, $this->fecha_factura, $this->monto_total_factura);
        $this->validate();
        $existeFactura = array_column($this->facturas, 'nro_factura');
        //dd($existeFactura);
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
        //dd($this->nuevasFacturas);
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
        //   dd($this->facturas);
    }

    #[On('eliminarFactura')]
    public function eliminarFactura($nroFactura)
    {
        //dd('asdfs', $index);
        // $factura = $this->facturas[$index];
        // if (!is_null($factura['id'])) {
        //     $facturaEliminada = Factura::where('id', $factura['id'])->update(['estado' => false]);
        // }
        // unset($this->facturas[$index]);
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

        // dd('dasda');
        try {

            $ingreso =  Ingreso::find($this->id_ingreso);
            //dd($ingreso);
            //dd($this->facturas);
            foreach ($this->facturas as $fac) {
                if (is_null($fac['id'])) {
                    //dd('sds', $this->id_ingreso);
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
                    // dd($nuevaFac);
                }
            }
            // dd($this->facturas_eliminadas);
            if (!empty($this->facturas_eliminadas)) {
                //  dd('adfas');
                $facturaEmilinada = Factura::whereIn('id', $this->facturas_eliminadas)
                    ->update(['estado' => false]);
                //dd($facturaEmilinada);
            }
            $this->dispatch('factruraCreada');
            return redirect()->route('almacen_farmacia.ingresos.index');
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
