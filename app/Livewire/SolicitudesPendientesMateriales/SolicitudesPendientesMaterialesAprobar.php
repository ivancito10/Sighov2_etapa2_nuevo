<?php

namespace App\Livewire\SolicitudesPendientesMateriales;

use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\AlmacenGeneral\MaterialSolicitud;
use App\Models\AlmacenGeneral\MaterialStock;
use App\Models\AlmacenGeneral\Movimiento;
use App\Models\AlmacenGeneral\Solicitud;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class SolicitudesPendientesMaterialesAprobar extends Component
{
    public $id_solicitud;

    public $cod_solicitud;

    public $cod_solicitud_numero;

    public $fecha_solicitud;

    public $unidad_solicitante;

    public $motivo;

    public $descripcion;

    public $estado_validacion;

    public $personal_solicitante;

    public $usuarioSolicita;

    public $fechaCreado;

    public $estadoValidacion;

    public $motivo_anulacion;

    public $fecha_anulacion;

    public $usuarioAnula;

    public $fecha_aprobacion;

    public $usuarioAprueba;

    public $fecha_entregado;

    public $usuarioEntrega;

    public $unidadSolicitante;

    public $cargoSolicitante;

    public $unidad_receptora;

    public $id_unidad_recepciona;

    public $count;

    public $cargoRechazante;

    public $unidadRechazante;

    public $cargoAprobado;

    public $unidadAprobado;

    public $cargoEntregado;

    public $unidadEntregado;

    public $materialesAgregados = [];

    public function render()
    {
        return view('livewire.solicitudes-pendientes-materiales.solicitudes-pendientes-materiales-aprobar');
    }

    public function mount()
    {
        $this->mostrarSolicitud($this->id_solicitud);
        //$this->NotificacionesMedicamentosStockMenosCien();
        //$this->NotificacionesMedicamentosPorVencer();
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    /*public function obtenerDatosUnidadReceptora($idUnidad)
    {
        $datosUnidad = new ObtenerDatosUnidadPorId();
        //dd($datosUnidad->obtenerDatosUnidadPorId($idUnidad));
        return $datosUnidad->obtenerDatosUnidadPorId($idUnidad);
    }*/

    public function obtenerNombreUnidad($idUnidad)
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('id', $idUnidad)['nombre'];
    }

    #[On('guardarRevision')]
    public function guardarRevision()
    {
        //$this->validate();

        $materialesAColeccion = collect($this->materialesAgregados);

        $hayErroresDeStock = $materialesAColeccion->contains(function ($materialcito) {
            $cantidad = floatval($materialcito['cantidad'] ?? 0);
            $stock = floatval($materialcito['stock_actual'] ?? 0);
            return $stock > 0 && $cantidad > $stock;
        });

        $hayErroresDeSolicitud = $materialesAColeccion->contains(function ($materialcito) {
            $cantidad = floatval($materialcito['cantidad'] ?? 0);
            $solicitado = floatval($materialcito['cantidad_original'] ?? 0);

            return $cantidad > $solicitado;
        });

        $hayVacios = $materialesAColeccion->contains(function ($materialcito) {
            return empty($materialcito['cantidad']) && $materialcito['cantidad'] < 0;
        });

        if ($hayErroresDeStock) {
            $this->dispatch('cantidadSuperaStock');
            return;
        }

        if ($hayErroresDeSolicitud) {
            $this->dispatch('cantidadSuperaSolicitado');
            return;
        }

        if ($hayVacios) {
            $this->dispatch('cantidadVacia');
            return;
        }

        $materialesSolicitados = MaterialSolicitud::where('id_solicitud', $this->id_solicitud)->get();
        DB::beginTransaction();
        try {
            $solicitudEntregada = Solicitud::where('id', $this->id_solicitud)->first();
            $solicitudEntregada->fecha_aprobacion = Carbon::now();
            $solicitudEntregada->id_usuario_aprueba = auth()->user()->id;
            $solicitudEntregada->estado_validacion = 'APROBADO';
            $solicitudEntregada->save();

            // inicio de notificaciones
            /*$idUsuarioCreador = $solicitudEntregada->id_user_created;
            NotificacionUsuarios::create([
                'estado' => 'NO LEIDO',
                'prioridades' => 3,
                'id_usu_emisor' => auth()->user()->id,
                'id_usu_receptor' => $idUsuarioCreador,
                'fecha_hora_enviada' => Carbon::now(),
                'asunto' => 'ALMACEN DE MEDICAMENTOS - SOLICITUD ENTREGADA',
                'mensaje' => 'Su solicitud con codigo: ' . $solicitudEntregada->cod_solicitud . ' .Fue entregado exitosamente' .
                    '. En fecha: ' . Carbon::now()->format('d/m/Y'),
            ]);*/
            // fin de notificaciones

            $materialesSolicitados = MaterialSolicitud::where('id_solicitud', $this->id_solicitud)->get();
            foreach ($this->materialesAgregados as $materialIngresado) {
                $materialSolicitado = $materialesSolicitados->firstWhere('id_material', $materialIngresado['id_material']);
                if ($materialSolicitado) {
                    $materialSolicitado->cantidad_solicitada = $materialIngresado['cantidad'];
                    $materialSolicitado->id_user_updated = auth()->user()->id;
                    $materialSolicitado->save();
                }

                //LOGICA PARA EL STOCK
                //$cantidad = $materialIngresado['cantidad'];
                //$this->procesarLotesEntrega($materialIngresado['id_material'], $cantidad);

                //FIN LOGICA PARA EL STOCK
            }
            DB::commit();
            // SE CREA EL PDF DE SOLICITUD DE UNIDAD A ALMACEN DE FARMACIA
            /*$url = route('almacen_general.solicitud_unidades.pdf', ['modo' => 'create', 'rowId' => $solicitudEntregada->id]);
            $this->js("window.open('{$url}', '_blank');");*/

            $this->dispatch('pg:eventRefresh-AlmacenGeneralSolicitudesTable');
            $this->dispatch('entregadoExitosamente');

            return redirect()->route('solicitudes_pendientes_materiales.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('noEntregadoExitosamente');
        }
    }

    public function mostrarSolicitud($id)
    {
        $solicitud = Solicitud::with('usuarioApruebaSolicitud', 'usuarioCreador.personaUser', 'usuarioApruebaSolicitud', 'usuarioAnulaSolicitud', 'usuarioEntregaSolicitud')
            ->find($id);
        $this->cod_solicitud_numero = $solicitud->cod_solicitud;
        $this->cod_solicitud = $solicitud->cod_solicitud . ' / ' . Carbon::parse($solicitud->created_at)->year;
        $this->fecha_solicitud = Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y');
        $this->motivo = $solicitud->motivo;
        $this->fechaCreado = Carbon::parse($solicitud->created_at)->format('d/m/Y');
        $descri = $solicitud->descripcion;
        if ($descri) {
            $this->descripcion = $solicitud->descripcion;
        } else {
            $this->descripcion = 'No cuenta con una descripción';
        }
        $this->estadoValidacion = $solicitud->estado_validacion;
        $this->motivo_anulacion = $solicitud->motivo_anulacion;
        $this->fecha_anulacion = Carbon::parse($solicitud->fecha_anulacion)->format('d/m/Y H:i:s');
        $this->usuarioSolicita = $solicitud->usuarioCreador->name;
        $this->id_unidad_recepciona = $solicitud->id_unidad_recepciona;
        $this->unidad_receptora = $this->obtenerNombreUnidad($solicitud->id_unidad_recepciona);
        if ($solicitud->id_usuario_anula) {
            $this->usuarioAnula = $solicitud->usuarioAnulaSolicitud->name;
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioAnulaSolicitud->id_persona);
            $this->cargoRechazante = $datos_entregante['cargo_personal_ssu']['nombre'];
            $this->unidadRechazante = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        } else {
            $this->usuarioAnula = null;
        }
        $this->fecha_aprobacion = Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i:s');
        $this->fecha_entregado = Carbon::parse($solicitud->fecha_entregado)->format('d/m/Y H:i:s');
        if ($solicitud->id_usuario_aprueba) {
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name;
            $datos_aprobante = $this->obtenerDatosPersona($solicitud->usuarioApruebaSolicitud->id_persona);
            $this->cargoAprobado = $datos_aprobante['cargo_personal_ssu']['nombre'];
            $this->unidadAprobado = $datos_aprobante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        } else {
            $this->usuarioAprueba = null;
        }

        if ($solicitud->id_usuario_entrega) {
            $this->usuarioEntrega = $solicitud->usuarioEntregaSolicitud->name;
            $datos_entregante = $this->obtenerDatosPersona($solicitud->usuarioEntregaSolicitud->id_persona);
            $this->cargoEntregado = $datos_entregante['cargo_personal_ssu']['nombre'];
            $this->unidadEntregado = $datos_entregante['cargo_personal_ssu']['unidad_organizacional']['nombre'];
        } else {
            $this->usuarioEntrega = null;
        }
        $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'Sin dato';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'in dato';

        // MEDICAMENTOS INGRESADOS HASTA AUI LO DEJE, HAY QUE CAMBIAR LA ESTRUCUTURA DEL ARRAY, ATTE.LIZ
        $materialesSolicitados = MaterialSolicitud::where('id_solicitud', $id)
            ->with('materialMaterialSolicitud', 'solicitudMaterialSolicitud')
            ->get();

        foreach ($materialesSolicitados as $key => $materialSol) {
            $stockMaterial = MaterialStock::where('id_material', $materialSol->id_material)
                ->where('id_unidad', $solicitud->id_unidad_recepciona)
                ->sum('stock_actualizado');

            $this->materialesAgregados[] = [
                'id_material' => $materialSol->id_material,
                'codigo' => $materialSol->materialMaterialSolicitud->codigo,
                'nombre_material' => $materialSol->materialMaterialSolicitud->nombre_material,
                'unidad' => $materialSol->materialMaterialSolicitud->unidad,
                'descripcion' => $materialSol->materialMaterialSolicitud->descripcion,
                'cantidad' => $materialSol->cantidad_solicitada,
                'cantidad_original' => $materialSol->cantidad_solicitada_original,
                'stock_actual' => $stockMaterial,
            ];
        }
        $this->cargarMateriales($this->materialesAgregados);
    }

    private function evaluarErroresItem(&$mat)
    {
        $cant = floatval($mat['cantidad'] ?? 0);
        $stock = floatval($mat['stock_actual'] ?? 0);
        $solicitado = floatval($mat['cantidad_original'] ?? 0);

        $mat['excede_stock'] = $cant > $stock;
        $mat['excede_solicitado'] = $cant > $solicitado;
        $mat['tiene_error'] = $mat['excede_stock'] || $mat['excede_solicitado'];
    }

    public function updatedMaterialesAgregados($value, $key)
    {
        if (str_contains($key, '.cantidad')) {
            $index = explode('.', $key)[0];
            if (isset($this->materialesAgregados[$index])) {
                $this->evaluarErroresItem($this->materialesAgregados[$index]);
            }
        }
    }

    public function cargarMateriales($materiales)
    {
        foreach ($materiales as &$mat) {
            $this->evaluarErroresItem($mat);
        }
        $this->materialesAgregados = $materiales;
    }

    // ========== REGLAS / VALIDACIÓN =============
    public function rules()
    {
        $rules = [];
        foreach ($this->materialesAgregados as $index => $mat) {
            // Reglas sobre "medicamentosAgregados.$index.cantidad"
            $rules["materialesAgregados.$index.cantidad"] = [
                'required',
                'integer',
                'min:0',
                'max:' . ($mat['stock_actual'] ?? 0),
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // Coincidir con la clave 'medicamentosAgregados.*.cantidad'
            'materialesAgregados.*.cantidad.max' => 'stock insuficiente',
        ];
    }

    // validación en tiempo real para "medicamentosAgregados"
    /*public function updatedMaterialesAgregados($value, $key)
    {
        $this->validateOnly($key);

        [$index, $campo] = explode('.', $key);

        // Si el stock de este medicamento es 0, forzamos la cantidad a 0
        if (
            $campo === 'cantidad'
            && ($this->materialesAgregados[$index]['stock_actual'] ?? 0) == 0
        ) {
            $this->materialesAgregados[$index]['cantidad'] = 0;
        }
    }*/

    public function cancelarEntrega()
    {
        return redirect()->route('solicitudes_pendientes_materiales.index');
    }

    public function atras()
    {
        return redirect()->route('solicitudes_pendientes_materiales.index');
    }
}
