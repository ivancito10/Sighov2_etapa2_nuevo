<?php

namespace App\Livewire\Farmacia\Farmacia\SolicitudesAlmacen;

use Livewire\Component;
use App\Models\Farmacia\Solicitud;

use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerDatosUnidadPorId;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\Movimiento;
use App\Services\ListaUnidades;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SolicitudesAlmacenEdit extends Component
{
    public $idSolicitud;
    public $cod_solicitud, $fecha_solicitud, $motivo, $descripcion, $fecha_aprobacion, $fecha_anulacion, $fecha_entregado;
    public $usuarioAprueba, $usuarioAnula, $usuarioSolicita, $unidadSolicitante, $cargoSolicitante;
    public $unidadRecepciona, $motivo_anulacion, $estadoValidacion, $unidad_receptora, $usuarioEntrega;
    public $medicamento, $count;

    // datos del medicamento
    public $id_medicamento;
    public $cod_medicamento, $nombreMedicamento, $forma_farmaceutica, $concentracion, $stockFarmacia, $stockAlmacen;
    public $tieneListadoMedicamentos = false;

    public $fechaCreado;


    public $medicamentos, $medicamentosAgregados = [];
    public function render()
    {
        return view('livewire.farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-edit');
    }

    public function mount()
    {
        $this->mostrarSolicitud($this->idSolicitud);
        $this->medicamentos = Medicamento::where('estado', true)->get();
    }

    public function mostrarSolicitud($id)
    {
        $solicitud = Solicitud::with('usuarioApruebaSolicitud', 'usuarioCreador.personaUser', 'usuarioApruebaSolicitud', 'usuarioAnulaSolicitud', 'usuarioEntregaSolicitud')
            ->find($id);

        $this->cod_solicitud = $solicitud->cod_solicitud . ' / ' .  Carbon::parse($solicitud->created_at)->year;
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

        $this->unidad_receptora = $this->obtenerDatosUnidadReceptora($solicitud->id_unidad_recepciona)['nombre'];

        if ($solicitud->id_usuario_anula) {
            $this->usuarioAnula = $solicitud->usuarioAnulaSolicitud->name;
        }
        $this->fecha_aprobacion = Carbon::parse($solicitud->fecha_aprobacion)->format('d/m/Y H:i:s');
        $this->fecha_entregado = Carbon::parse($solicitud->fecha_entregado)->format('d/m/Y H:i:s');
        if ($solicitud->id_usuario_aprueba) {
            $this->usuarioAprueba = $solicitud->usuarioApruebaSolicitud->name;
        }
        if ($solicitud->id_usuario_entrega) {
            $this->usuarioEntrega = $solicitud->usuarioEntregaSolicitud->name;
        }
        $personaData = $this->obtenerDatosPersona($solicitud->usuarioCreador->id_persona);
        $this->unidadSolicitante = $personaData['cargo_personal_ssu']['unidad_organizacional']['nombre'] ?? 'SIN DATO';
        $this->cargoSolicitante = $personaData['cargo_personal_ssu']['nombre'] ?? 'SIN DATO';

        // MEDICAMENTOS INGRESADOS HASTA AUI LO DEJE, HAY QUE CAMBIAR LA ESTRUCUTURA DEL ARRAY, ATTE.LIZ
        $medicamentosSolicitados = MedicamentoSolicitud::where('id_solicitud', $id)
            ->with('medicamentoMedicamentoSolicitud', 'solicitudMedicamentoSolicitud')
            ->get();

        foreach ($medicamentosSolicitados as $key => $medicamentoSol) {
            //$stockMedicamento = MedicamentoStock::where('id_medicamento', $medicamentoSol->id_medicamento)->sum('stock_actualizado');
            $stockAlmacen = MedicamentoStock::where('id_medicamento', $medicamentoSol->id_medicamento)
                ->where('id_unidad',  $this->obtenerIdAlmacen())
                ->sum('stock_actualizado');
            $stockFarmacia = MedicamentoStock::where('id_medicamento', $medicamentoSol->id_medicamento)
                ->where('id_unidad',  $this->obtenerIdFarmacia())
                ->sum('stock_actualizado');
            $this->medicamentosAgregados[] = [
                'id_medicamento' => $medicamentoSol->id_medicamento,
                'codigo_medicamento' => $medicamentoSol->medicamentoMedicamentoSolicitud->codigo_medicamento,
                'nombre' => $medicamentoSol->medicamentoMedicamentoSolicitud->nombre,
                'forma_farmaceutica' => $medicamentoSol->medicamentoMedicamentoSolicitud->forma_farmaceutica,
                'concentracion' => $medicamentoSol->medicamentoMedicamentoSolicitud->concentracion ?? 'SIN DATO',
                'cantidad' => $medicamentoSol->cantidad_solicitada,
                'stock_farmacia' => $stockFarmacia,
                'stock_almacen' => $stockAlmacen,
            ];
        }
    }

    public function obtenerDatosPersona($id_persona)
    {
        $datosPersona = new ObtenerDatosPersonalSsu();
        return $datosPersona->obtenerDatosPersonalSsu($id_persona);
    }

    public function obtenerDatosUnidadReceptora($idUnidad)
    {
        $datosUnidad = new ObtenerDatosUnidadPorId();
        return $datosUnidad->obtenerDatosUnidadPorId($idUnidad);
    }

    public function obtenerIdAlmacen()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'ALMACEN DE FARMACIA')['id'];
    }

    public function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }

    public function atras()
    {
        return redirect()->route('farmacia.solicitudes.almacen.index');
    }
    public function cancelarEntrega()
    {
        return redirect()->route('farmacia.solicitudes.almacen.index');
    }

    #[On('guardar_entrega')]
    public function guardar_entrega()
    {
        try {
            $solicitud = Solicitud::find($this->idSolicitud);
            if ($solicitud) {
                $solicitud->estado_validacion = 'APROBADO';
                $solicitud->fecha_aprobacion = Carbon::now();
                $solicitud->id_usuario_aprueba = auth()->user()->id;
                //$solicitud->id_user_updated = auth()->user()->id;
                $solicitud->save();

                $this->dispatch('pg:eventRefresh-SolFarmaciaAlmacenFarmaciaTable');
                $this->dispatch('guardar_entrega_almacen');
                return redirect()->route('farmacia.solicitudes.almacen.index');
            }
        } catch (\Throwable $th) {
            $this->dispatch('errorFuncion');
        }
        //$this->dispatch('guardar_entrega');
        //dd('aquii');
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
                ->where('id_unidad',  $this->obtenerIdAlmacen())
                ->sum('stock_actualizado');
            //dd($this->stockAlmacen,$this->obtenerIdAlmacen() );
            $this->stockFarmacia = MedicamentoStock::where('id_medicamento', $datoMedicamento->id)
                ->where('id_unidad',  $this->obtenerIdFarmacia())
                ->sum('stock_actualizado');
            //dd($this->stockAlmacen, $this->stockFarmacia);
            if ($this->stockAlmacen == 0) {
                $this->dispatch('stockAlmacenCero');
                $this->resetearListadoMedicamentos();
            }
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
                        'stock_farmacia' => $this->stockFarmacia,
                        'stock_almacen' => $this->stockAlmacen,
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

    public function eliminarMedicamentoSeleccionado($cod_med)
    {
        $this->medicamentosAgregados = array_filter($this->medicamentosAgregados, function ($medicamento) use ($cod_med) {
            return $medicamento['codigo_medicamento'] !== $cod_med;
        });
        $this->medicamentosAgregados = array_values($this->medicamentosAgregados);
        //ELIMINAR LA SOLICITUD DEL MEDICAMENTO
        $medicamento = $medicamento = Medicamento::where('codigo_medicamento', $cod_med)->first();
        $medicamentoSolicitud = MedicamentoSolicitud::where('id_solicitud', $this->idSolicitud)->where('id_medicamento', $medicamento->id)->first();
        $medicamentoSolicitud->delete();

        $this->actualizarListaMedicamentos();
        //$this->dispatch('medicamentoEliminado');
    }

    public function actualizarListaMedicamentos()
    {
        $codigosAgregados = array_map(function ($codsMeds) {
            return $codsMeds['codigo_medicamento'];
        }, $this->medicamentosAgregados);
        $this->medicamentos = Medicamento::where('estado', true)->whereNotIn('codigo_medicamento', $codigosAgregados)->get();
    }
    public function resetearListadoMedicamentos()
    {
        $this->reset('id_medicamento', 'nombreMedicamento', 'forma_farmaceutica', 'concentracion');
    }

    //=================== EDITAR SOLICITUD ============================
    #[On('EditSolicitud')]
    public function EditSolicitud()
    {
        DB::beginTransaction();
        //try {
        $nuevaSolicitud = $this->editarSolicitud();
        //DD('LLEGA 1');
        if (!$nuevaSolicitud) {
            throw new \Exception('No se pudo crear la solicitud');
        }

        if (!$this->agregarMedicamentosASolicitud($this->idSolicitud)) {
            throw new \Exception('No se pudieron agregar los medicamentos');
        }

        DB::commit();
        $this->dispatch('solicitudGuardada');

        //CREA PDF CON DATOS DE EDICION Y REEMPLAZA EN EL FTP
        $url = route('farmacia.farmacia.solicitudes-almacen.pdf', ['modo' => 'edit', 'id' => $this->idSolicitud]);
        $this->js("window.open('{$url}', '_blank');");

        return redirect()->route('farmacia.solicitudes.almacen.index');

        /*} catch (\Throwable $th) {
            DD('LLEGA 0');
            DB::rollBack();
            $this->dispatch('solicitudNoGuardada');
        }*/
    }

    private function editarSolicitud()
    {
        $solicitudLista = Solicitud::find($this->idSolicitud);

        if (!$solicitudLista) {
            return false;
        }
        $solicitudLista->descripcion = $this->descripcion;
        $solicitudLista->id_user_updated = auth()->user()->id;
        $solicitudLista2 = $solicitudLista->save();
        return $solicitudLista2;
    }


    private function agregarMedicamentosASolicitud($solicitudId)
    {
        $contador = 0;

        foreach ($this->medicamentosAgregados as $medicamentoAgregado) {
            // Usar firstOrCreate para buscar o crear el medicamento
            $nuevoMedicamentoSolicitud = MedicamentoSolicitud::firstOrCreate(
                [
                    'id_solicitud' => $solicitudId,
                    'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                ],
                [
                    'cantidad_solicitada' => null,
                    'id_user_created' => auth()->user()->id,
                ]
            );

            // Incrementar contador solo si el registro existe o se crea
            if ($nuevoMedicamentoSolicitud->wasRecentlyCreated || $nuevoMedicamentoSolicitud->exists) {
                $contador++;
            }
        }

        return $contador === count($this->medicamentosAgregados);
    }

    //=================



}
