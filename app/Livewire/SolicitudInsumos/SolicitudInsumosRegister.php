<?php

namespace App\Livewire\SolicitudInsumos;

use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Solicitud;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ConsultaExterna\MedicamentosService;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Services\ObtenerDatosUnidadPorId;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;

class SolicitudInsumosRegister extends Component
{
    public $nombreUsuario;
    public $unidadUsuario;
    public $cargoUsuario;
    public $unidadesRecepcionan = [];
    public $medicamentos = [];
    public $medicamentosAgregados = [];
    #[Validate]
    public $id_medicamento, $cantidad;
    public $nombreMedicamento;
    public $formaFarmaceuticaMostrar, $concentracionMostrar;


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
    public $stockMed;
    public $listadoServicios = [];
    public $piso;
    public $id_unidad_servicio;
    public $pisoSwitch = false;

    public $collectionListadoUnidades;


    public function render()
    {
        return view('livewire.solicitud-insumos.solicitud-insumos-register');
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
        $this->medicamentos = Medicamento::where('estado', true)
            ->orderBy('codigo_medicamento', 'asc')->get();
        // $this->unidadRecep();
        $this->datosUsuario();
        $this->listadoServicios = $this->listadoUnidades();
        $this->collectionListadoUnidades = collect($this->listadoServicios);
        //dd($this->listadoServicios);
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
            return $listadoUnidades;
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
        //dd('entra');
        if ($this->id_unidad_servicio === null || trim($this->id_unidad_servicio) === "") {
            $this->dispatch('seleccioneUnidadServicio');
        }
        return;
    }

    #[On('registrarSolicitud')]
    public function registrarSolicitud()
    {
        $this->validacionUnidadServicio();
        if ($this->medicamentosAgregados != []) {
            $this->validate();
            DB::beginTransaction();
            //try {
            $nuevaSolicitud = Solicitud::create([
                'cod_solicitud' => $this->cod_solicitud,
                'fecha_solicitud' => $this->fecha_solicitud,
                'motivo' => $this->motivo,
                'descripcion' => $this->descripcion,
                'estado_validacion' => 'PENDIENTE',
                'estado' => true,
                'piso' => $this->piso,
                'id_unidad_servicio' => $this->id_unidad_servicio,
                'id_unidad_solicita' => $this->id_unidad_solicita,
                'id_unidad_recepciona' => $this->id_unidad_recepciona,
                'nombre_unidad_servicio' => $this->obtenerNombreUnidadSeleccionada($this->id_unidad_servicio),
                'nombre_unidad_solicita' => $this->obtenerNombreUnidadSeleccionada($this->id_unidad_solicita),
                'id_user_created' => auth()->user()->id,
            ]);
            $medicamentoSolicitudContador = 0;
            if ($nuevaSolicitud) {
                foreach ($this->medicamentosAgregados as $key => $medicamentoAgregado) {
                    $nuevoMedicamentoSolicitud = MedicamentoSolicitud::create([
                        'cantidad_solicitada' => $medicamentoAgregado['cantidad'],
                        'id_solicitud' => $nuevaSolicitud->id,
                        'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                        'id_user_created' => auth()->user()->id,
                    ]);
                    if ($nuevoMedicamentoSolicitud) {
                        $medicamentoSolicitudContador = $medicamentoSolicitudContador + 1;
                    }
                }
                if ($medicamentoSolicitudContador == count($this->medicamentosAgregados)) {
                    $nuevoMedicamentoSolicitudCreado = true;
                } else {
                    $nuevoMedicamentoSolicitudCreado = false;
                }
                if ($nuevoMedicamentoSolicitudCreado) {
                    // inicio de notificacion
                    //dd($nuevaSolicitud->id_unidad_solicita);
                    $serviceUnidad = new ObtenerDatosUnidadPorId();
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
                                'asunto' => 'ALMACEN DE MEDICAMENTOS -  NUEVA SOLICITUD',
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
                    }
                    // fin de notificacion
                    DB::commit();
                    $this->dispatch('guardado');

                    //GUARDA EL PDF EN EL FTP
                    $url = route('farmacia.solicitudinsumos.pdf', ['modo' => 'create', 'id' => $nuevaSolicitud->id]);
                    $this->js("window.open('{$url}', '_blank');");
                    return redirect()->route('solicitud_insumos.index');
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
                //dd("Error capturado: " . $errorMessage);
                $this->dispatch('noGuardado');
            }*/
        } else {
            $this->dispatch('medicamentosVacio');
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

    public function unidadRecep()
    {
        $datosUnidades = new ListaUnidades();
        $this->unidadesRecepcionan = array_filter($datosUnidades->listaUnidades(), function ($unidad) {
            return in_array($unidad['nombre'], ['FARMACIA', 'ALMACEN DE FARMACIA']);
        });
    }

    #[On('medicamentoSeleccionado')]
    public function medicamentoSeleccionado()
    {
        // dd('sdas');
        if ($this->id_unidad_recepciona != null) {
            // dd($this->id_unidad_recepciona);
            if ($this->id_medicamento != null || $this->id_medicamento != '') {
                $nombreMed = Medicamento::where('id', $this->id_medicamento)->first();
                $this->nombreMedicamento = $nombreMed->nombre;
                $this->formaFarmaceuticaMostrar = $nombreMed->forma_farmaceutica;
                $this->concentracionMostrar = $nombreMed->concentracion;
                $servicioStock = new MedicamentosService();
                $stock = $servicioStock->stockActual($this->id_medicamento, $this->id_unidad_recepciona);
                // dd($stock);
                /*  $this->stockMed = MedicamentoStock::where('id_medicamento', $nombreMed->id)
                    ->where('id_unidad', $this->id_unidad_recepciona)
                    ->sum('stock_actualizado'); */
                // dd($this->stockMed);
                $this->stockMed = $stock;
                if ($this->stockMed == 0) {
                    //  $this->dispatch('refreshSelect2');
                    $this->dispatch('cantidadNoDisponible');
                    //  $this->id_medicamento = null;
                    //  $this->nombreMedicamento = null;
                    //  $this->formaFarmaceuticaMostrar = null;
                    //  $this->stockMed = null;
                }
            } else {
                $this->nombreMedicamento = null;
                $this->formaFarmaceuticaMostrar = null;
                $this->stockMed = null;
                $this->id_medicamento = null;
                $this->dispatch('refreshSelect2');
            }
        } else {
            $this->id_medicamento = null;
            $this->dispatch('refreshSelect2');
            $this->dispatch('seleccioneUnidad');
        }
    }

    public function agregarMedicamento()
    {
        //dd('asds');
        $this->validate([
            'id_medicamento'  => 'required',
            'cantidad' => 'required | max:10 |gte:0',
        ]);
        // if ($this->id_medicamento != null && $this->cantidad != null) {
        //dd('entra');
        //if ($this->stockMed >= $this->cantidad) {
        $datosMedicamento = Medicamento::where('id', $this->id_medicamento)->first();
        //dd($datosMedicamento);
        $codigosExistentes = array_column($this->medicamentosAgregados, 'codigo_medicamento');
        if (in_array($datosMedicamento->codigo_medicamento, $codigosExistentes)) {
            $this->cantidad = null;
            $this->id_medicamento = null;
            $this->nombreMedicamento = null;
            $this->formaFarmaceuticaMostrar = null;
            $this->concentracionMostrar = null;
            $this->stockMed = null;
            $this->dispatch('refreshSelect2');
            $this->dispatch('insumoDuplicado');
        } else {
            $this->medicamentosAgregados[] = [
                'id_medicamento' => $datosMedicamento->id,
                'codigo_medicamento' => $datosMedicamento->codigo_medicamento,
                'nombre' => $datosMedicamento->nombre,
                'forma_farmaceutica' => $datosMedicamento->forma_farmaceutica,
                'concentracion' => $datosMedicamento->concentracion,
                'cantidad' => $this->cantidad,
            ];
            $this->actualizarListaMedicamentos();
            $this->cantidad = null;
            $this->id_medicamento = null;
            $this->nombreMedicamento = null;
            $this->formaFarmaceuticaMostrar = null;
            $this->concentracionMostrar = null;
            $this->stockMed = null;
            $this->dispatch('refreshSelect2');
            $this->dispatch('insumoAgregadoCorrectamente');
        }
        // }
        // else {
        //    $this->dispatch('cantidadNoDisponible');
        //}
        //} else {
        //    $this->dispatch('datosIncompletos');
        //}
    }

    public function actualizarListaMedicamentos()
    {
        $codigosAgregados = array_map(function ($codsMeds) {
            return $codsMeds['codigo_medicamento'];
        }, $this->medicamentosAgregados);

        $this->medicamentos = Medicamento::where('estado', true)->whereNotIn('codigo_medicamento', $codigosAgregados)->get();
    }

    public function eliminarMedicamento($cod_med)
    {
        $this->medicamentosAgregados = array_filter($this->medicamentosAgregados, function ($medicamento) use ($cod_med) {
            return $medicamento['codigo_medicamento'] !== $cod_med;
        });

        $this->medicamentosAgregados = array_values($this->medicamentosAgregados);

        $this->actualizarListaMedicamentos();
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
        return redirect()->route('solicitud_insumos.index');
    }

    public function cancelarSolicitud()
    {
        return redirect()->route('solicitud_insumos.index');
    }
}
