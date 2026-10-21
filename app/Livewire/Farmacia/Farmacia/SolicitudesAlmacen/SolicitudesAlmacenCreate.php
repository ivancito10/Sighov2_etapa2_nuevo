<?php

namespace App\Livewire\Farmacia\Farmacia\SolicitudesAlmacen;

use Livewire\Component;
use App\Models\Farmacia\Solicitud;
use App\Services\ListaUnidades;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Services\ObtenerDatosPersonalSsu;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Services\obtenerDatosUnidadPorId;
use App\Models\Administracion\NotificacionUsuarios;

class SolicitudesAlmacenCreate extends Component
{
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante;
    public $unidadRecepciona, $motivo_anulacion, $estadoValidacion, $cod_solicitudLiteral, $usuarioEntrega, $nombreUsuario, $unidadUsuario, $cargoUsuario;
    public  $count, $id_unidad_solicita;
    public $unidadRecepcionan, $año;
    public $id_medicamento;
    public $cod_medicamento, $nombreMedicamento, $forma_farmaceutica, $concentracion, $stockFarmacia, $stockAlmacen;
    public $medicamentos, $medicamentosAgregados = [];
    public $fechaCreado, $today;
    public $tieneListadoMedicamentos = false;

    public $cantidad;

    public function render()
    {
        return view('livewire.farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-create');
    }

    public function mount()
    {
        $this->datosSolicitante();
        $this->medicamentos = Medicamento::where('estado', true)
            ->orderBy('codigo_medicamento', 'asc')->get();
        $this->fecha_solicitud = Carbon::now()->format('Y-m-d');
        $this->nuevoCodigoSolicitud();
    }

    public function datosSolicitante()
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        $id_persona = auth()->user()->id_persona;
        $solicitante = $datosPersona->obtenerDatosPersonalSsu($id_persona);
        $this->usuarioSolicita = auth()->user()->name;
        $this->unidadSolicitante = $solicitante['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'Sin dato';
        $this->cargoSolicitante = $solicitante['cargo_personal_ssu']['nombre'] ?? 'Sin dato';
        $this->id_unidad_solicita = $solicitante['cargo_personal_ssu']['unidad_organizacional']['id'] ?? null;
    }

    /*public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        //dd($datosUnidades->listaUnidades());
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }*/

    /*public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }*/

    #[On('crearSolicitud')]
    public function crearSolicitud()
    {
        DB::beginTransaction();
        try {
            $nuevaSolicitud = Solicitud::create([
                'cod_solicitud' => $this->cod_solicitud,
                'fecha_solicitud' => $this->fecha_solicitud,
                'descripcion' => $this->descripcion,
                'estado' => true,
                'estado_validacion' => 'PENDIENTE',
                'id_unidad_solicita' => 76,
                'id_unidad_recepciona' => 89,
                'id_user_created' => auth()->user()->id,
                'farmacia' => true,
            ]);
            // inicio de notificacion
            $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
            $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
            $nombre_usuario = $nuevaSolicitud->usuarioCreador->name;
            foreach ($receptores as $receptor) {
                NotificacionUsuarios::create([
                    'estado' => 'NO LEIDO',
                    'prioridades' => 2,
                    'id_usu_emisor' => auth()->user()->id,
                    'id_usu_receptor' =>  $receptor->model_id,
                    'fecha_hora_enviada' => Carbon::now(),
                    'asunto' => 'FARMACIA -  NUEVA SOLICITUD',
                    'mensaje' => 'Se ha realizado una nueva solicitud con codigo: ' . $nuevaSolicitud->cod_solicitud . ' de la unidad: FARMACIA' .
                        '. Realizado por el usuario: ' . $nombre_usuario .
                        '. En fecha: ' . Carbon::now()->format('d/m/Y'),
                ]);
            }
            // fin de notificacion
            if (!$nuevaSolicitud) {
                DB::rollBack();
                $this->dispatch('solicitudNoGuardada');
                return;
            }

            $medicamentoSolicitudContador = 0;
            foreach ($this->medicamentosAgregados as $medicamentoAgregado) {
                $nuevoMedicamentoSolicitud = MedicamentoSolicitud::create([
                    'cantidad_solicitada' => null,
                    'id_solicitud' => $nuevaSolicitud->id,
                    'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                    'id_user_created' => auth()->user()->id,
                ]);
                if ($nuevoMedicamentoSolicitud) {
                    $medicamentoSolicitudContador++;
                }
            }
            if ($medicamentoSolicitudContador !== count($this->medicamentosAgregados)) {
                DB::rollBack();
                $this->dispatch('solicitudNoGuardada');
                return;
            }
            if (!$this->tieneListadoMedicamentos) {
                $this->dispatch('agregaMedicamentos');
                return;
            }
            DB::commit();
            //Aqui se crea el PDF de solicitud de farmacia a almacen de Farmacia
            $url = route('farmacia.farmacia.solicitudes-almacen.pdf', ['modo' => 'create', 'id' => $nuevaSolicitud->id]);
            $this->js("window.open('{$url}', '_blank');");

            //$this->js('window.open("/farmacia/solicitudes/almacen/Pdf/' . $nuevaSolicitud->id . '", "_blank")');

            return redirect()->route('farmacia.solicitudes.almacen.index')->with('evento', 'solicitudGuardada');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('solicitudNoGuardada');
            report($th);
        }
    }
    #[On('addMedicamentoListado')]
    public function addMedicamentoListado()
    {
        try {
            if ($this->id_medicamento != null) {
                $datosMedicamento = Medicamento::where('id', $this->id_medicamento)->first();
                $codigosExistentes = array_column($this->medicamentosAgregados, 'codigo_medicamento');
                if (in_array($datosMedicamento->codigo_medicamento, $codigosExistentes)) {
                    $this->dispatch('medicamentoYaSeleccionado');
                    $this->resetearListadoMedicamentos();
                } else {
                    $this->medicamentosAgregados[] = [
                        'id_medicamento' => $datosMedicamento->id,
                        'codigo_medicamento' => $datosMedicamento->codigo_medicamento,
                        'nombre' => $datosMedicamento->nombre,
                        'forma_farmaceutica' => $datosMedicamento->forma_farmaceutica,
                        'concentracion' => $datosMedicamento->concentracion,
                        'stockFarmacia' => $this->stockFarmacia,
                        'stockAlmacen' => $this->stockAlmacen,
                        $this->tieneListadoMedicamentos = true,
                    ];
                    $this->dispatch('medicamentoAgregadoCorrectamente');
                    $this->resetearListadoMedicamentos();
                }
            } else {
                $this->dispatch('ningunMediSeleccionado');
            }
        } catch (\Throwable $th) {
        }
    }
    #[On('muestraMedicamentoSolicitado')]
    public function muestraMedicamentoSolicitado()
    {
        if ($this->id_medicamento != null || $this->id_medicamento != '') {

            $datoMedicamento = Medicamento::where('id', $this->id_medicamento)
                ->first();
            $this->nombreMedicamento = $datoMedicamento->nombre;
            $this->cod_medicamento = $datoMedicamento->codigo_medicamento;
            $this->forma_farmaceutica = $datoMedicamento->forma_farmaceutica;
            $this->concentracion = $datoMedicamento->concentracion;

            $this->stockAlmacen = MedicamentoStock::where('id_medicamento', $datoMedicamento->id)
                ->where('id_unidad',  89)
                ->sum('stock_actualizado');
            //dd($this->stockAlmacen,$this->obtenerIdAlmacen() );
            $this->stockFarmacia = MedicamentoStock::where('id_medicamento', $datoMedicamento->id)
                ->where('id_unidad',  76)
                ->sum('stock_actualizado');
            if ($this->stockAlmacen == 0) {
                $this->dispatch('stockAlmacenCero');
                //   $this->resetearListadoMedicamentos();
            }
        }
    }
    public function eliminarMedicamentoSeleccionado($cod_med)
    {
        $this->medicamentosAgregados = array_filter($this->medicamentosAgregados, function ($medicamento) use ($cod_med) {
            return $medicamento['codigo_medicamento'] !== $cod_med;
        });
        $this->medicamentosAgregados = array_values($this->medicamentosAgregados);
        $this->actualizarListaMedicamentos();
        $this->dispatch('medicamentoEliminado');
    }
    public function actualizarListaMedicamentos()
    {
        $codigosAgregados = array_map(function ($codsMeds) {
            return $codsMeds['codigo_medicamento'];
        }, $this->medicamentosAgregados);

        $this->medicamentos = Medicamento::where('estado', true)->whereNotIn('codigo_medicamento', $codigosAgregados)->get();
    }
    public function nuevoCodigoSolicitud()
    {
        $solicitudAnterior = Solicitud::orderBy('id', 'desc')
            ->where('farmacia', true)
            ->first();
        if ($solicitudAnterior) {
            $ultimaGestion = Carbon::parse($solicitudAnterior->created_at)->format('Y');
            if ($ultimaGestion == Carbon::now()->format('Y')) {
                $this->cod_solicitud = (int)$solicitudAnterior->cod_solicitud + 1;
                $this->cod_solicitudLiteral = 'SOL/FAR/' . $this->cod_solicitud . '/' . $ultimaGestion;
            } else {
                $ultimaGestion = Carbon::now()->format('Y');
                $this->cod_solicitud = 1;
                $this->cod_solicitudLiteral = 'SOL/FAR/' . $this->cod_solicitud . '/' . $ultimaGestion;
            }
        } else {
            $ultimaGestion = Carbon::now()->format('Y');
            $this->cod_solicitud = 1;
            $this->cod_solicitudLiteral = 'SOL/FAR/' . $this->cod_solicitud . '/' . $ultimaGestion;
        }
    }
    public function atras()
    {
        return redirect()->route('farmacia.solicitudes.almacen.index');
    }
    public function cancelarSolicitud()
    {
        $this->dispatch('solicitudCancelada');
        return redirect()->route('farmacia.solicitudes.almacen.index');
    }
    public function resetearListadoMedicamentos()
    {
        $this->reset('id_medicamento', 'nombreMedicamento', 'forma_farmaceutica', 'concentracion');
    }
}
