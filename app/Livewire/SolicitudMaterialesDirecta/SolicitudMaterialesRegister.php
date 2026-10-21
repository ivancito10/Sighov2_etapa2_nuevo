<?php

namespace App\Livewire\SolicitudMaterialesDirecta;

use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\MaterialSolicitud;
use App\Models\AlmacenGeneral\MaterialStock;
use App\Models\AlmacenGeneral\Solicitud;
use App\Models\Etapa3\Planificacion\Unidad;
use App\Models\AlmacenGeneral\Movimiento;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use App\Services\AlmacenGeneral\MaterialesService;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Carbon\Carbon;

class SolicitudMaterialesRegister extends Component
{

    public $nombreUsuario;
    public $unidadUsuario;
    public $cargoUsuario;
    public $unidadesRecepcionan = [];
    public $materiales = [];
    public $materialesAgregados = [];
    #[Validate]
    public $id_material, $cantidad;
    public $nombreMaterial;
    public $unidadMostrar, $descripcionMostrar;


    //ATRIBUTOS TABLA SOLICITUD
    public $cod_solicitud;
    public $cod_solicitudLiteral;
    #[Validate]
    public $fecha_solicitud;
    #[Validate]
    public $motivo;
    public $descripcion;
    public $estado;
    public $id_unidad_solicita;
    #[Validate]
    public $id_unidad_recepciona;
    public $motivo_anulacion;
    public $estado_validacion;
    public $stockMat;
    public $listadoServicios = [];
    public $piso;
    public $id_unidad_servicio;
    public $pisoSwitch = false;

    public $collectionListadoUnidades;

    public function render()
    {
        return view('livewire.solicitud-materiales-directa.solicitud-materiales-register');
    }

    public function rules()
    {
        $rules = [
            'fecha_solicitud' => 'required|date',
            'id_unidad_recepciona' => 'required',
            'motivo' => 'required',
            'id_unidad_servicio' => 'required',
        ];

        if ($this->pisoSwitch) {
            $rules['piso'] = 'required';
        }

        return $rules;
    }

    public function mount()
    {
        $this->fecha_solicitud = Carbon::now()->format('Y-m-d');
        $this->nuevoCodigoSolicitud();
        $this->materiales = Material::where('estado', true)
            ->orderBy('codigo', 'asc')->get();
        $this->datosUsuario();
        $this->listadoServicios = $this->listadoUnidades();
        $this->collectionListadoUnidades = collect($this->listadoServicios);
        $this->id_unidad_recepciona = 19;
        $this->motivo = 'APROVISIONAMIENTO DE MATERIALES E INSUMOS';
    }

    public static function obtenerIdHospitalizacion()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'SERVICIO DE HOSPITALIZACIÓN')['id'];
    }

    public function listadoUnidades()
    {
        $listadoUnidades = Unidad::where('estado', true)->orderBy('id', 'asc')->get()->toArray();
        /*$servicio = new ListaUnidades();
        $listadoUnidades = $servicio->listaUnidades();*/
        // dd($listadoUnidades);
        if (!empty($listadoUnidades)) {
            return $unidadesSelect = $listadoUnidades;
        } else {
            return [];
        }
    }

    #[On('servicioSeleccionado')]
    public function servicioSeleccionado()
    {
        if ($this->obtenerIdHospitalizacion() === (int) $this->id_unidad_servicio) {
            $this->pisoSwitch = true;
        } else {
            $this->pisoSwitch = false;
            $this->piso = null;
        }
    }

    public function obtenerNombreUnidadSeleccionada($idUnidadSeleccionada)
    {
        return $this->collectionListadoUnidades->firstWhere('id', $idUnidadSeleccionada)['nombre'] ?? null;
    }

    public function validacionUnidadServicio()
    {
        if ($this->id_unidad_servicio === null || trim($this->id_unidad_servicio) === "") {
            $this->dispatch('seleccioneUnidadServicio');
        }
        return;
    }

    #[On('registrarSolicitud')]
    public function registrarSolicitud()
    {
        $this->validacionUnidadServicio();
        if ($this->materialesAgregados != []) {
            $this->validate();
            DB::beginTransaction();
            try {
                $nuevaSolicitud = Solicitud::create([
                    'cod_solicitud' => $this->cod_solicitud,
                    'fecha_solicitud' => $this->fecha_solicitud,
                    'motivo' => $this->motivo,
                    'descripcion' => $this->descripcion,
                    'estado_validacion' => 'ENTREGADO', //SOLAMENTE PARA EL CASO DE LAS SOLICITUDES
                    'estado' => true,
                    'piso' => $this->piso,
                    'id_unidad_servicio' => $this->id_unidad_servicio,
                    'id_unidad_solicita' => $this->id_unidad_solicita,
                    'id_unidad_recepciona' => $this->id_unidad_recepciona,
                    'nombre_unidad_servicio' => $this->obtenerNombreUnidadSeleccionada($this->id_unidad_servicio),
                    'nombre_unidad_solicita' => $this->obtenerNombreUnidadSeleccionada($this->id_unidad_solicita),
                    'id_user_created' => auth()->user()->id,
                    //DATOS QUE SOLO SE PONDRÁN PARA LAS SOLICITUDES RÁPIDAS
                    'fecha_aprobacion' => $this->fecha_solicitud,
                    'id_usuario_aprueba' => 4228,
                    'fecha_entregado' => $this->fecha_solicitud,
                    'id_usuario_entrega' => auth()->user()->id,
                    'fecha_revisado' => $this->fecha_solicitud,
                    'id_user_revisado' => auth()->user()->id,

                ]);
                $materialSolicitudContador = 0;
                if ($nuevaSolicitud) {
                    foreach ($this->materialesAgregados as $key => $materialAgregado) {
                        $nuevoMaterialSolicitud = MaterialSolicitud::create([
                            'cantidad_solicitada' => $materialAgregado['cantidad'],
                            'cantidad_solicitada_original' => $materialAgregado['cantidad'],
                            'cantidad_entregada' => $materialAgregado['cantidad'],
                            'id_solicitud' => $nuevaSolicitud->id,
                            'id_material' => $materialAgregado['id_material'],
                            'id_user_created' => auth()->user()->id,
                        ]);

                        $this->procesarLotesEntrega($materialAgregado['id_material'], $materialAgregado['cantidad'], $nuevaSolicitud->id, $nuevaSolicitud->cod_solicitud);

                        if ($nuevoMaterialSolicitud) {
                            $materialSolicitudContador = $materialSolicitudContador + 1;
                        }
                    }
                    if ($materialSolicitudContador == count($this->materialesAgregados)) {
                        $nuevoMaterialSolicitudCreado = true;
                    } else {
                        $nuevoMaterialSolicitudCreado = false;
                    }

                    if ($nuevoMaterialSolicitudCreado) {
                        DB::commit();
                        $this->dispatch('guardado');
                        return redirect()->route('solicitud_materiales_directa.index');
                    } else {
                        DB::rollBack();
                        $this->dispatch('noGuardado');
                    }
                } else {
                    DB::rollBack();
                    $this->dispatch('noGuardado');
                }
            } catch (\Throwable $th) {
                DB::rollBack();

                dd($th);
                $this->dispatch('noGuardado');
            }
        } else {
            $this->dispatch('materialesVacio');
        }
    }

    public function datosUsuario()
    {
        $datoUser = new ObtenerDatosPersonalSsu();
        $datoSolicitante = $datoUser->obtenerDatosPersonalSsu(auth()->user()->id_persona);
        // dd($datoSolicitante);
        $this->nombreUsuario = auth()->user()->name;
        $this->id_unidad_solicita = $datoSolicitante['cargo_personal_ssu']['unidad_organizacional']['id'] ?? null;
        $this->unidadUsuario = $datoSolicitante['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'Sin dato';
        $this->cargoUsuario = $datoSolicitante['cargo_personal_ssu']['nombre'] ?? 'Sin dato';
    }

    public function verificarStock($idMaterial)
    {
        $hayStock = false;
        $servicioStock = new MaterialesService();
        $stock = $servicioStock->stockActual($idMaterial, $this->id_unidad_recepciona);

        if ($stock > 0) {
            $hayStock = true;
        }

        return $hayStock;
    }

    #[On('materialSeleccionado')]
    public function materialSeleccionado()
    {
        $this->cantidad = null;
        if ($this->id_unidad_recepciona != null) {
            if ($this->id_material != null || $this->id_material != '') {
                $nombreMat = Material::where('id', $this->id_material)->first();
                $this->nombreMaterial = $nombreMat->nombre_material;
                $this->unidadMostrar = $nombreMat->unidad;
                $this->descripcionMostrar = $nombreMat->descripcion;
                $servicioStock = new MaterialesService();
                $stock = $servicioStock->stockActual($this->id_material, $this->id_unidad_recepciona);

                $this->stockMat = $stock;
                if ($this->stockMat == 0) {
                    $this->dispatch('cantidadNoDisponible');
                }
            } else {
                $this->nombreMaterial = null;
                $this->unidadMostrar = null;
                $this->stockMat = null;
                $this->id_material = null;
                $this->dispatch('refreshSelect2');
            }
        } else {
            $this->id_material = null;
            $this->dispatch('refreshSelect2');
            $this->dispatch('seleccioneUnidad');
        }
    }

    public function agregarMaterial()
    {
        //dd('asds');
        if (!$this->verificarStock($this->id_material)) {
            $this->dispatch('sinStockDisponible');
            return;
        };

        $this->validate([
            'id_material'  => 'required',
            'cantidad' => 'required | max:10 |gte:0',
        ]);
        // if ($this->id_material != null && $this->cantidad != null) {
        //dd('entra');
        //if ($this->stockMat >= $this->cantidad) {
        $datosMaterial = Material::where('id', $this->id_material)->first();
        //dd($datosMaterial);
        $codigosExistentes = array_column($this->materialesAgregados, 'codigo');
        if (in_array($datosMaterial->codigo, $codigosExistentes)) {
            $this->cantidad = null;
            $this->id_material = null;
            $this->nombreMaterial = null;
            $this->unidadMostrar = null;
            $this->descripcionMostrar = null;
            $this->stockMat = null;
            $this->dispatch('refreshSelect2');
            $this->dispatch('insumoDuplicado');
        } else {
            $this->materialesAgregados[] = [
                'id_material' => $datosMaterial->id,
                'codigo' => $datosMaterial->codigo,
                'nombre_material' => $datosMaterial->nombre_material,
                'unidad' => $datosMaterial->unidad,
                'descripcion' => $datosMaterial->descripcion,
                'cantidad' => $this->cantidad,
            ];
            $this->actualizarListaMateriales();
            $this->cantidad = null;
            $this->id_material = null;
            $this->nombreMaterial = null;
            $this->unidadMostrar = null;
            $this->descripcionMostrar = null;
            $this->stockMat = null;
            $this->dispatch('refreshSelect2');
            $this->dispatch('materialAgregadoCorrectamente');
        }
        // }
        // else {
        //    $this->dispatch('cantidadNoDisponible');
        //}
        //} else {
        //    $this->dispatch('datosIncompletos');
        //}
    }

    public function actualizarListaMateriales()
    {
        $codigosAgregados = array_map(function ($codsMats) {
            return $codsMats['codigo'];
        }, $this->materialesAgregados);

        $this->materiales = Material::where('estado', true)->whereNotIn('codigo', $codigosAgregados)->get();
    }

    public function eliminarMaterial($cod_mat)
    {
        $this->materialesAgregados = array_filter($this->materialesAgregados, function ($material) use ($cod_mat) {
            return $material['codigo'] !== $cod_mat;
        });

        $this->materialesAgregados = array_values($this->materialesAgregados);

        $this->actualizarListaMateriales();
    }

    public function nuevoCodigoSolicitud()
    {
        $solicitudAnterior = Solicitud::orderBy('id', 'desc')->first();
        if ($solicitudAnterior) {
            $ultimaGestion = Carbon::parse($solicitudAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $this->cod_solicitud = $solicitudAnterior->cod_solicitud + 1;
                $this->cod_solicitudLiteral = $this->cod_solicitud . '/' . $ultimaGestion;
            } else {
                $ultimaGestion = Carbon::now()->format('Y');
                $this->cod_solicitud = 1;
                $this->cod_solicitudLiteral = $this->cod_solicitud . '/' . $ultimaGestion;
            }
        } else {
            $ultimaGestion = Carbon::now()->format('Y');
            $this->cod_solicitud = 1;
            $this->cod_solicitudLiteral = $this->cod_solicitud . '/' . $ultimaGestion;
        }
    }

    public function atras()
    {
        return redirect()->route('solicitud_materiales_directa.index');
    }

    public function cancelarSolicitud()
    {
        return redirect()->route('solicitud_materiales_directa.index');
    }


    // SOLAMENTE PARA EL REGISTRO RÁPIDO


    private function procesarLotesEntrega(int $idMaterial, int $cantidad, int $idSolicitud, string $codSolicitud)
    {
        $lotes = MaterialStock::where('id_material', $idMaterial)
            ->where('id_unidad', $this->id_unidad_recepciona)
            ->where('stock_actualizado', '>', 0)
            // ->where('fecha_vencimiento', '>', Carbon::now())
            ->orderBy('id', 'asc')
            ->get();

        foreach ($lotes as $lote) {
            if ($cantidad <= 0) {
                break;
            }

            $disponible = (int) $lote->stock_actualizado;
            if ($disponible <= 0) {
                continue;
            }

            $cantidadARestar = min($disponible, $cantidad);

            $datosParaMovimientos = [
                'id_corresponde' => $idSolicitud,
                'id_material' => $idMaterial,
                'id_unidad' => $this->id_unidad_recepciona,
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => $this->obtenerNombreUnidadSeleccionada($this->id_unidad_servicio),
                'nro_documento' => $codSolicitud,
                'dato_interesado' => $this->obtenerNombreUnidadSeleccionada($this->id_unidad_recepciona),
                'nro_lote' => $lote->nro_lote,
                'precio_unitario_peps' => $lote->precio_unitario,
                'fecha_vencimiento' => $lote->fecha_vencimiento,
            ];

            $lote->stock_actualizado = $disponible - $cantidadARestar;
            $lote->save();

            $cantidad -= $cantidadARestar;

            $this->registrarMovimientoSolicitudUnidadAAlmacenGeneral($datosParaMovimientos);
        }
    }

    public function registrarMovimientoSolicitudUnidadAAlmacenGeneral(array $datosNecesarios)
    {
        //PARA LA TABLA DE MOVIMIENTOS 
        /*$movimientoAnterior = Movimiento::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->orderBy('fecha', 'desc')
            ->first();*/

        try {



            $saldoMatStock = MaterialStock::where('id_material', $datosNecesarios['id_material'])
                ->where('id_unidad', $datosNecesarios['id_unidad'])
                ->sum('stock_actualizado');

            $nuevoSaldo = $saldoMatStock;


            $nuevoMovimiento = Movimiento::create([
                'id_corresponde' => $datosNecesarios['id_corresponde'],
                'id_identificador_tabla' => 2,
                'dato_solicitante' => $datosNecesarios['dato_solicitante'],
                'nro_documento' => $datosNecesarios['nro_documento'],
                'fecha' => Carbon::now(),
                'dato_interesado' => $datosNecesarios['dato_interesado'],
                'cantidad_ingreso' => 0,
                'cantidad_salida' => $datosNecesarios['cantidad'],
                'saldo' => $nuevoSaldo,
                'id_estado_validacion' => 6,
                'id_material' => $datosNecesarios['id_material'],
                'id_unidad' => $datosNecesarios['id_unidad'],
                'nro_lote' => $datosNecesarios['nro_lote'],
                'id_user_created' => auth()->user()->id,
                'precio_unitario_peps' => $datosNecesarios['precio_unitario_peps'],
                'fecha_vencimiento' => $datosNecesarios['fecha_vencimiento'],
                'stock_real' => true,
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }

        //FIN DE LA TABLA DE MOVIMIENTOS 
    }
}
