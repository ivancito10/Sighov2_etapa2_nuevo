<?php

namespace App\Livewire\AlmacenGeneral\Reingreso;

use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\MaterialStock;
use App\Models\AlmacenGeneral\Movimiento;
use App\Models\AlmacenGeneral\Reingreso;
use App\Models\AlmacenGeneral\ReingresoMaterial;
use App\Models\Etapa3\Planificacion\Unidad;
use App\Services\ListaUnidades;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

class ReingresoRegister extends Component
{
    #[Validate]
    public $unidad, $nro_comprobante, $fecha_reingreso, $paciente, $nro_habitacion, $piso, $observacion, $codigo_reingreso;

    public $id_material, $materiales;

    public $cantidad, $nombreMaterial, $unidadMaterial, $fecha_vencimiento, $descripcion, $motivo_reingreso;
    public $materialesAgregados = [];
    public $unidades;
    public $id_persona, $nombreCompleto, $ci, $matricula, $personaSeleccionada = false, $noExiste = false;

    public function render()
    {
        return view('livewire.almacen-general.reingreso.reingreso-register');
    }

    public function mount()
    {
        $this->materiales = Material::where('estado', true)
            ->orderBy('codigo', 'asc')
            ->get();
        $this->fecha_reingreso = now()->toDateString();
        $this->unidades =  $this->listadoUnidades();
        $this->crearCodigos();
    }

    public function rules()
    {
        $reglas = [
            'unidad' => 'required',
            'fecha_reingreso' => 'required|date',
            'nro_comprobante' => 'required|min:1|max:10',
            'motivo_reingreso' => 'required',
        ];

        return $reglas;
    }

    #[On('crearReingreso')]
    public function crearReingreso()
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if ($this->unidad == null) {
                $this->dispatch('agregarUnidad');
                return;
            }

            if ($this->materialesAgregados === null || $this->materialesAgregados === []) {
                $this->dispatch('ingreseMaterialesAReingresar');
                return;
            }

            if ($this->materialesAgregados != []) {
                $nuevoReingreso = Reingreso::create([
                    'codigo_reingreso' => $this->codigo_reingreso,
                    'fecha_ingreso' => $this->fecha_reingreso,
                    'observacion' => $this->observacion,
                    'motivo_reingreso' => $this->motivo_reingreso,
                    'id_unidad' => $this->unidad,
                    'estado' => true,
                    'id_user_created' => auth()->user()->id,
                ]);

                if ($nuevoReingreso) {

                    foreach ($this->materialesAgregados as $key => $materialAgregado) {

                        $precioUnitario = $this->registrarMaterialStock(19, $materialAgregado, $nuevoReingreso);

                        $nuevoReingresoMaterial = ReingresoMaterial::create([
                            'id_reingreso' => $nuevoReingreso->id,
                            'id_material' => $materialAgregado['id_material'],
                            'fecha' => $this->fecha_reingreso,
                            'estado' => true,
                            'id_user_created' => auth()->user()->id,
                            'cantidad_ingreso' => $materialAgregado['cantidad'],
                            'fecha_vencimiento' => $materialAgregado['fecha_vencimiento'],
                            'precio_unitario' => $precioUnitario,
                        ]);
                    }

                    DB::commit();

                    //SE CREA EL PDF DE REINGRESO Y SE GUARDA EN EL FTP
                    $url = route('almacen_general.reingresos.pdf', ['modo' => 'create', 'id_reingreso' => $nuevoReingreso->id]);
                    $this->js("window.open('{$url}', '_blank');");

                    $this->dispatch('registroReingresoExitoso');
                    return redirect()->route('almacen-general.reingresos.index');
                }
            }
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            $this->dispatch('registroReingresoNoExitoso');
        }
    }

    private function registrarMaterialStock($idUnidad, $materialAgregado, $nuevoReingreso)
    {
        $precioUnitarioReingreso = 0;
        $materialStockAnterior = MaterialStock::where('id_material', $materialAgregado['id_material'])
            ->where('id_unidad', $idUnidad)
            ->orderBy('nro_lote', 'desc')
            ->first();
        //dd($this->noExiste);
        if ($this->noExiste == true) {
            $nuevoMaterialStock = MaterialStock::create([
                'cantidad_inicial' => $materialAgregado['cantidad'],
                'precio_unitario' => 0,
                'stock_actualizado' => $materialAgregado['cantidad'],
                'nro_lote' =>  1,
                'id_material' => $materialAgregado['id_material'],
                'id_unidad' => $idUnidad,
                'ajuste_contable' => false,
                'id_user_created' => auth()->user()->id,
                'fecha_vencimiento' => $materialAgregado['fecha_vencimiento'],
            ]);
        } else {
            $nuevoMaterialStock = MaterialStock::create([
                'cantidad_inicial' => $materialAgregado['cantidad'],
                'precio_unitario' => $materialStockAnterior->precio_unitario,
                'stock_actualizado' => $materialAgregado['cantidad'],
                'nro_lote' => $materialStockAnterior->nro_lote + 1,
                'id_material' => $materialAgregado['id_material'],
                'id_unidad' => $idUnidad,
                'ajuste_contable' => false,
                'id_user_created' => auth()->user()->id,
                'fecha_vencimiento' => $materialAgregado['fecha_vencimiento'],
            ]);
        }

        if ($nuevoMaterialStock) {

            $precioUnitarioReingreso = $nuevoMaterialStock->precio_unitario;

            /*$movimientoPrevio = Movimiento::where('id_material', $materialAgregado['id_material'])
                ->where('id_unidad', $idUnidad)
                ->orderBy('fecha', 'desc')
                ->first();*/

            $saldoMatStock = MaterialStock::where('id_material', $materialAgregado['id_material'])
                ->where('id_unidad', $idUnidad)
                ->sum('stock_actualizado');

            $saldo = 0;

            if ($saldoMatStock) {
                //DADO QUE ES COMO UN INGRESO, EN CUALQUIER CASO VA A SUMAR AL SALDO ANTERIOR
                $saldo = $saldoMatStock;
            } else {
                $saldo = $materialAgregado['cantidad'];
            }

            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $nuevoReingreso->id,
                'id_identificador_tabla' => 5,
                'dato_solicitante' => 'ALMACEN GENERAL',
                'nro_documento' => $nuevoReingreso->codigo_reingreso,
                'fecha' => $nuevoReingreso->fecha_ingreso,
                'dato_interesado' => $this->obtenerNombreUnidad($this->unidad),
                'cantidad_ingreso' => $materialAgregado['cantidad'],
                'cantidad_salida' => 0,
                'saldo' => $saldo,
                'id_estado_validacion' => 7,
                'id_material' => $materialAgregado['id_material'],
                'id_unidad' => $idUnidad,
                'nro_lote' => $nuevoMaterialStock->nro_lote,
                'precio_unitario_peps' => $nuevoMaterialStock->precio_unitario,
                'id_user_created' => auth()->user()->id,
                'fecha_vencimiento' => $materialAgregado['fecha_vencimiento'],
                'stock_real' => true,
            ]);
        }
        return $precioUnitarioReingreso;
    }

    private static function obtenerNombreUnidad($idUnidad)
    {
        /*$datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidad)['nombre'];*/
        $nombreUnidad = Unidad::find($idUnidad);

        return $nombreUnidad->nombre;

    }

    public function listadoUnidades()
    {
        $listadoUnidades = Unidad::where('estado', true)->orderBy('id', 'asc')->get()->toArray();
        //dd($listadoCompleto);
        /*$servicio = new ListaUnidades();
        $listadoUnidades = $servicio->listaUnidades();*/
        
        if (!empty($listadoUnidades)) {
            return $listadoUnidades;
        } else {
            return [];
        }
    }

    public function crearCodigos()
    {
        //dd('entra');
        $reingresoAnterior = Reingreso::orderBy('id', 'desc')->first();
        if ($reingresoAnterior) {
            $ultimoAño = Carbon::parse($reingresoAnterior->created_at)->format('Y');
            if ($ultimoAño == Carbon::now()->format('Y')) {
                $this->codigo_reingreso = $reingresoAnterior->codigo_reingreso + 1;
                $this->nro_comprobante = $this->codigo_reingreso . ' / ' . $ultimoAño;
            } else {
                $añoActual = Carbon::now()->format('Y');
                $this->codigo_reingreso = 1;
                $this->nro_comprobante = $this->codigo_reingreso . ' / ' . $añoActual;
            }
        } else {
            $añoActual = Carbon::now()->format('Y');
            $this->codigo_reingreso = 1;
            $this->nro_comprobante = $this->codigo_reingreso . ' / ' . $añoActual;
        }
    }


    public function agregarMaterial()
    {
        // try {
        $this->validate(
            [
                'id_material' => 'required',
                'unidad' => 'required',
                'descripcion' => 'required',
                //'fecha_vencimiento' => 'required|date',
                'cantidad' => 'required|max:10',
            ]
        );

        $material = Material::with('materialIngresoMaterial')->where('id', $this->id_material)->first();
        //dd($material);
        $codigosExistentes = array_column($this->materialesAgregados, 'codigo');
        // dd($codigosExistentes);

        $tieneRegistro =  MaterialStock::where('id_material', $this->id_material)
            ->where('id_unidad', 19)
            ->orderBy('nro_lote', 'desc')
            ->first();
        // dd($tieneRegistro);
        if (is_null($tieneRegistro)) {
            $this->noExiste = true;
            // $this->dispatch('sinRegistroPrevio');
            // return;
        }

        if (in_array($material->codigo, $codigosExistentes)) {
            $this->cantidad = null;
            $this->id_material = null;
            $this->nombreMaterial = null;
            $this->unidadMaterial = null;
            $this->dispatch('materialDuplicado');
            $this->resetearDatos();
        } else {

            $this->materialesAgregados[] = [
                'id_material' => $material->id,
                'codigo' => $material->codigo,
                'nombre_material' => $material->nombre_material,
                'unidad' => $material->unidad,
                'descripcion' => $material->descripcion,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'motivo_reingreso' => $this->motivo_reingreso,
                'cantidad' => $this->cantidad,
            ];

            // dd($this->materialesAgregados);
            $this->actualizarListado();
            $this->dispatch('materialAgregado');

            $this->dispatch('resetSelectMaterial');
            $this->resetearDatos();
        }
        // } catch (\Throwable $th) {
        //     $this->dispatch('error');
        // }
    }
    #[On('muestraMaterialSolicitado')]
    public function muestraMaterialSolicitado()
    {
        if ($this->id_material != null || $this->id_material != '') {
            $material = Material::with('materialIngresoMaterial')->where('id', $this->id_material)
                ->first();
            $this->nombreMaterial = $material->nombre_material;
            $this->unidadMaterial = $material->unidad;
            $this->descripcion = $material->descripcion ?? 'SIN DATO';
            // $this->fecha_vencimiento = $material->materialIngresoMaterial->fecha_vencimiento;
        }
    }
    public function eliminarMaterial($cod_mat)
    {
        $this->materialesAgregados = array_filter($this->materialesAgregados, function ($material) use ($cod_mat) {
            return $material['codigo'] !== $cod_mat;
        });
        $this->materialesAgregados = array_values($this->materialesAgregados);
        $this->actualizarListado();
    }

    public function actualizarListado()
    {
        $materialesAgregados = array_map(function ($codigos_materiales) {
            return $codigos_materiales['codigo'];
        }, $this->materialesAgregados);
        $this->materiales = Material::where('estado', true)->whereNotIn('codigo', $materialesAgregados)->get();
    }

    public function resetearDatos()
    {
        // dd('asdsa');
        $this->reset('id_material', 'nombreMaterial', 'unidadMaterial', 'cantidad', 'fecha_vencimiento', 'descripcion');
    }

    public function atras()
    {
        return redirect()->route('almacen-general.reingresos.index');
    }

    public function cancelar()
    {
        return redirect()->route('almacen-general.reingresos.index');
    }
}
