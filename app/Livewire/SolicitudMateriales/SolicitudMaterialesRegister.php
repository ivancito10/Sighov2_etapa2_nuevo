<?php

namespace App\Livewire\SolicitudMateriales;

use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\AlmacenGeneral\Material;
use App\Models\AlmacenGeneral\MaterialSolicitud;
use App\Models\AlmacenGeneral\Solicitud;
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
        return view('livewire.solicitud-materiales.solicitud-materiales-register');
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
        $servicio = new ListaUnidades();
        $listadoUnidades = $servicio->listaUnidades();
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
            //try {
            $nuevaSolicitud = Solicitud::create([
                'cod_solicitud' => $this->cod_solicitud,
                'fecha_solicitud' => $this->fecha_solicitud,
                'motivo' => $this->motivo,
                'descripcion' => $this->descripcion,
                'estado_validacion' => 'REGISTRADO',
                'estado' => true,
                'piso' => $this->piso,
                'id_unidad_servicio' => $this->id_unidad_servicio,
                'id_unidad_solicita' => $this->id_unidad_solicita,
                'id_unidad_recepciona' => $this->id_unidad_recepciona,
                'nombre_unidad_servicio' => $this->obtenerNombreUnidadSeleccionada($this->id_unidad_servicio),
                'nombre_unidad_solicita' => $this->obtenerNombreUnidadSeleccionada($this->id_unidad_solicita),
                'id_user_created' => auth()->user()->id,
            ]);
            $materialSolicitudContador = 0;
            if ($nuevaSolicitud) {
                foreach ($this->materialesAgregados as $key => $materialAgregado) {
                    $nuevoMaterialSolicitud = MaterialSolicitud::create([
                        'cantidad_solicitada' => $materialAgregado['cantidad'],
                        'cantidad_solicitada_original' => $materialAgregado['cantidad'],
                        'id_solicitud' => $nuevaSolicitud->id,
                        'id_material' => $materialAgregado['id_material'],
                        'id_user_created' => auth()->user()->id,
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
                    // inicio de notificacion
                    //dd($nuevaSolicitud->id_unidad_solicita);
                    /*$serviceUnidad = new ObtenerDatosUnidadPorId();
                    $unidadReceptora = $serviceUnidad->obtenerDatosUnidadPorId($nuevaSolicitud->id_unidad_recepciona)['nombre'];
                    if ($unidadReceptora === 'ALMACEN DE FARMACIA') {
                        $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Auxiliar de Almacen de Farmacia'])->get();
                        $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
                        $unidadSolicita = $serviceUnidad->obtenerDatosUnidadPorId($nuevaSolicitud->id_unidad_solicita)['nombre'];
                        $nombre_usuario = $nuevaSolicitud->usuarioCreador->name;
                        foreach ($receptores as $receptor) {
                            NotificacionUsuarios::create([
                                'estado' => 'NO LEIDO',
                                'prioridades' => 2,
                                'id_usu_emisor' => auth()->user()->id,
                                'id_usu_receptor' =>  $receptor->model_id,
                                'fecha_hora_enviada' => Carbon::now(),
                                'asunto' => 'ALMACEN GENERAL -  NUEVA SOLICITUD',
                                'mensaje' => 'Se ha realizado una nueva solicitud con codigo: ' . $nuevaSolicitud->cod_solicitud . ' de la unidad: ' . $unidadSolicita .
                                    '. Realizado por el usuario: ' . $nombre_usuario .
                                    '. En fecha: ' . Carbon::now()->format('d/m/Y'),
                            ]);
                        }
                    }

                    if ($unidadReceptora === 'FARMACIA') {
                        $roles = Role::whereIn('name', ['Regente de Farmacia', 'Farmaceutica', 'Aprobacion Solicitudes Farmacia'])->get();
                        $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();

                        $unidadSolicita = $serviceUnidad->obtenerDatosUnidadPorId($nuevaSolicitud->id_unidad_solicita)['nombre'];
                        $nombre_usuario = $nuevaSolicitud->usuarioCreador->name;

                        foreach ($receptores as $receptor) {
                            NotificacionUsuarios::create([
                                'estado' => 'NO LEIDO',
                                'prioridades' => 2,
                                'id_usu_emisor' => auth()->user()->id,
                                'id_usu_receptor' =>  $receptor->model_id,
                                'fecha_hora_enviada' => Carbon::now(),
                                'asunto' => 'FARMACIA -  NUEVA SOLICITUD',
                                'mensaje' => 'Se ha realizado una nueva solicitud con codigo: ' . $nuevaSolicitud->cod_solicitud . ' de la unidad: ' . $unidadSolicita .
                                    '. Realizado por el usuario: ' . $nombre_usuario .
                                    '. En fecha: ' . Carbon::now()->format('d/m/Y'),
                            ]);
                        }
                    }*/
                    // fin de notificacion
                    DB::commit();
                    $this->dispatch('guardado');

                    //GUARDA EL PDF EN EL FTP
                    /*$url = route('almacen_general.solicitud_unidades.pdf', ['modo' => 'create', 'rowId' => $nuevaSolicitud->id]);
                    $this->js("window.open('{$url}', '_blank');");*/
                    return redirect()->route('solicitud_materiales.index');
                } else {
                    DB::rollBack();
                    $this->dispatch('noGuardado');
                }
            } else {
                DB::rollBack();
                $this->dispatch('noGuardado');
            }
            /*} catch (\Throwable $th) {
                DB::rollBack();

                $errorMessage = $th->getMessage();
                dd("Error capturado: " . $errorMessage);
                $this->dispatch('noGuardado');
            }*/
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
        if (!$this->verificarStock($this->id_material)){
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
        return redirect()->route('solicitud_materiales.index');
    }

    public function cancelarSolicitud()
    {
        return redirect()->route('solicitud_materiales.index');
    }
}
