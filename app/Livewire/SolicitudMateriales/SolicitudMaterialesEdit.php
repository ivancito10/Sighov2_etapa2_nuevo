<?php

namespace App\Livewire\SolicitudMateriales;

use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\MaterialSolicitud;
use App\Models\AlmacenGeneral\MaterialStock;
use App\Models\AlmacenGeneral\Solicitud;
use App\Services\AlmacenGeneral\MaterialesService;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Livewire\Attributes\On;

class SolicitudMaterialesEdit extends Component
{
    public $idSolicitud;
    public $nombreUsuario;
    public $unidadUsuario;
    public $cargoUsuario;
    public $cod_solicitud;
    public $descripcionMostrar;
    public $cod_solicitudLiteral;
    #[Validate]
    public $id_unidad_recepciona;
    public $unidad_recepciona;
    #[Validate]
    public $fecha_solicitud;
    #[Validate]
    public $motivo;
    public $descripcion;

    public $id_material;
    public $materiales = [];
    public $materialesAgregados = [];
    #[Validate]
    public $cantidad;
    public $nombreMaterial;
    public $unidadMostrar;
    public $stockMat;
    public $listadoServicios = [];
    public $piso;
    public $id_unidad_servicio;
    public $id_unidad_solicita;
    public $pisoSwitch = false;

    public $unidadesRecepcionan = [];

    public $collectionListadoUnidades;

    public function render()
    {
        return view('livewire.solicitud-materiales.solicitud-materiales-edit');
    }

    public function mount()
    {
        $this->datosSolicitud($this->idSolicitud);
        $this->dispatch('cargarIdUnidadServicio', id: $this->id_unidad_servicio);
        $this->listadoServicios = $this->listadoUnidades();
        $this->collectionListadoUnidades = collect($this->listadoServicios);
        $this->materiales = Material::where('estado', true)->get();
        $this->id_unidad_recepciona = 19; //Id de SECCION DE ALMACEN GENERAL OJO
        //$this->unidadRecep();
    }

    public function rules()
    {
        return [
            'fecha_solicitud' => 'required|date',
            'id_unidad_recepciona' => 'required',
            'motivo' => 'required',
        ];
    }

    /*public function unidadRecep()
    {
        $datosUnidades = new ListaUnidades();
        if ($datosUnidades->listaUnidades() != null) {
            $this->unidadesRecepcionan = array_filter($datosUnidades->listaUnidades(), function ($unidad) {
                return in_array($unidad['nombre'], ['FARMACIA', 'ALMACEN DE FARMACIA']);
            });
        } else {
            $this->unidadesRecepcionan = [];
        }
    }*/

    public function listadoUnidades()
    {
        $servicio = new ListaUnidades();
        $listadoUnidades = $servicio->listaUnidades();
        // dd($listadoUnidades);
        if (!empty($listadoUnidades)) {
            return $unidadesSelect = $listadoUnidades;
        } else {
            return [];
        }
    }

    public function obtenerNombreUnidadSeleccionada($idUnidadSeleccionada)
    {
        return $this->collectionListadoUnidades->firstWhere('id', $idUnidadSeleccionada)['nombre'] ?? null;
    }

    #[On('editarSolicitud')]
    public function editarSolicitud()
    {
        if ($this->materialesAgregados != []) {
            $this->validate();
            DB::beginTransaction();
            try {
                $solicitudEditada = Solicitud::where('id', $this->idSolicitud)->first();
                $solicitudEditada->fecha_solicitud = $this->fecha_solicitud;
                $solicitudEditada->motivo = $this->motivo;
                $solicitudEditada->descripcion = $this->descripcion;
                $solicitudEditada->id_unidad_servicio = $this->id_unidad_servicio;
                $solicitudEditada->piso = $this->piso;
                $solicitudEditada->id_unidad_recepciona = $this->id_unidad_recepciona;
                $solicitudEditada->id_unidad_solicita = $this->id_unidad_solicita;
                $solicitudEditada->nombre_unidad_servicio = $this->obtenerNombreUnidadSeleccionada($this->id_unidad_servicio);
                $solicitudEditada->nombre_unidad_solicita = $this->obtenerNombreUnidadSeleccionada($this->id_unidad_solicita);
                $solicitudEditada->id_user_updated = auth()->user()->id;
                $solicitudEditada->save();

                $materialSolicitudContador = 0;

                $materialesSolicitadosAnteriores = MaterialSolicitud::where('id_solicitud', $solicitudEditada->id)->get();
                foreach ($materialesSolicitadosAnteriores as $key => $materialAnterior) {
                    $materialAnterior->delete();
                }

                foreach ($this->materialesAgregados as $key => $materialAgregado) {
                    $nuevoMaterialSolicitud = MaterialSolicitud::create([
                        'cantidad_solicitada' => $materialAgregado['cantidad'],
                        'cantidad_solicitada_original' => $materialAgregado['cantidad'],
                        'id_solicitud' => $solicitudEditada->id,
                        'id_material' => $materialAgregado['id_material'],
                        'id_user_created' => $solicitudEditada->id_user_created,
                        'id_user_updated' => auth()->user()->id,
                    ]);

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

                    //CREA PDF CON DATOS DE EDICION Y REEMPLAZA EN EL FTP
                    /*$url = route('almacen_general.solicitud_unidades.pdf', ['modo' => 'edit', 'rowId' => $solicitudEditada->id]);
                    $this->js("window.open('{$url}', '_blank');");*/

                    return redirect()->route('solicitud_materiales.index');
                } else {
                    DB::rollBack();
                    $this->dispatch('noGuardado');
                }
            } catch (\Throwable $th) {
                //dd($th);
                DB::rollBack();
                $this->dispatch('noGuardado');
            }
        } else {
            $this->dispatch('materialesVacio');
        }
    }

    public function datosUsuarioSolicitante($idSolicitante)
    {
        $obtenerDatos = new ObtenerDatosPersonalSsu();
        return $obtenerDatos->obtenerDatosPersonalSsu($idSolicitante);
    }

    public function datosSolicitud($idSol)
    {
        $solicitud = Solicitud::where('id', $idSol)->with('usuarioCreador')->first();
        //dd($solicitud);
        $datosSolicitante = $this->datosUsuarioSolicitante($solicitud->usuarioCreador->id_persona);
        $this->nombreUsuario = $solicitud->usuarioCreador->name;
        $this->unidadUsuario = $datosSolicitante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        $this->cargoUsuario = $datosSolicitante['cargo_personal_ssu']['nombre'];

        $this->cod_solicitud = $solicitud->cod_solicitud;
        $this->cod_solicitudLiteral = $solicitud->cod_solicitud . '/' . Carbon::parse($solicitud->created_at)->format('Y');
        $this->id_unidad_recepciona = $solicitud->id_unidad_recepciona;
        $this->id_unidad_solicita = $solicitud->id_unidad_solicita;
        $this->unidad_recepciona = '';
        $this->fecha_solicitud = Carbon::parse($solicitud->fecha_solicitud)->format('Y-m-d');
        $this->motivo = $solicitud->motivo;
        $this->descripcion = $solicitud->descripcion;

        $this->id_unidad_servicio = $solicitud->id_unidad_servicio;
        if ($solicitud->piso !== null && $solicitud->piso !== "") {
            $this->pisoSwitch = true;
            $this->piso = $solicitud->piso;
        }

        //dd($this->id_unidad_servicio, $this->piso, $solicitud->piso, $solicitud->id_unidad_servicio);

        //DATOS DE LOS MEDICAMENTOS YA SOLICITADOS
        $listaMateriales = MaterialSolicitud::where('id_solicitud', $solicitud->id)->with('materialMaterialSolicitud')->get();
        foreach ($listaMateriales as $key => $materialSol) {
            //PARA MOSTRAR EL STOCK ACTUAL DEL MEDICAMENTOS SEGUN LA UNIDAD
            $stockMat = MaterialStock::where('id_material', $materialSol->id_material)
                ->where('id_unidad', $this->id_unidad_recepciona)
                ->sum('stock_actualizado');


            $this->materialesAgregados[] = [
                'id_material' => $materialSol->id_material,
                'codigo' => $materialSol->materialMaterialSolicitud->codigo,
                'nombre_material' => $materialSol->materialMaterialSolicitud->nombre_material,
                'unidad' => $materialSol->materialMaterialSolicitud->unidad,
                'descripcion' => $materialSol->materialMaterialSolicitud->descripcion,
                'stock_actual' => $stockMat,
                'cantidad' => $materialSol->cantidad_solicitada,
            ];
        }
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
        if ($this->id_material != null || $this->id_material != '') {
            $nombreMat = Material::where('id', $this->id_material)->first();
            $this->nombreMaterial = $nombreMat->nombre_material;
            $this->unidadMostrar = $nombreMat->unidad;
            $this->descripcionMostrar = $nombreMat->descripcion;
            $this->stockMat = MaterialStock::where('id_material', $nombreMat->id)
                ->where('id_unidad', $this->id_unidad_recepciona)
                ->sum('stock_actualizado');
        } else {
            $this->nombreMaterial = null;
            $this->unidadMostrar = null;
            $this->stockMat = null;
        }
    }


    public function agregarMaterial()
    {
        if ($this->id_material === null) {
            $this->dispatch('sinMaterialSeleccionado');
            return;
        }

        if (!$this->verificarStock($this->id_material)) {
            $this->dispatch('sinStockDisponible');
            return;
        };

        $this->validate([
            'id_material'  => 'required',
            'cantidad' => 'required | max:10 |gt:0',
        ]);

        if ($this->id_material != null && $this->cantidad != null) {
            if ($this->stockMat >= $this->cantidad) {
                $datosMaterial = Material::where('id', $this->id_material)->first();

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
            } else {
                $this->dispatch('cantidadNoDisponible');
            }
        } else {
            $this->dispatch('datosIncompletos');
        }
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

    public function atras()
    {
        return redirect()->route('solicitud_materiales.index');
    }

    public function cancelarSolicitud()
    {
        return redirect()->route('solicitud_materiales.index');
    }
}
