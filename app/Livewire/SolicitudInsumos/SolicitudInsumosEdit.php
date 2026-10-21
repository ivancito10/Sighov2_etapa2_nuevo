<?php

namespace App\Livewire\SolicitudInsumos;

use App\Models\Administracion\LinkServicio;
use App\Models\Farmacia\Medicamento;
use App\Models\Farmacia\MedicamentoSolicitud;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Solicitud;
use App\Services\ListaUnidades;
use App\Services\ObtenerDatosPersonalSsu;
use App\Services\ObtenerToken;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;

class SolicitudInsumosEdit extends Component
{
    public $idSolicitud;
    public $nombreUsuario;
    public $unidadUsuario;
    public $cargoUsuario;
    public $cod_solicitud;
    public $concentracionMostrar;
    public $cod_solicitudLiteral;
    #[Validate]
    public $id_unidad_recepciona;
    public $unidad_recepciona;
    #[Validate]
    public $fecha_solicitud;
    #[Validate]
    public $motivo;
    public $descripcion;

    public $id_medicamento;
    public $medicamentos = [];
    public $medicamentosAgregados = [];
    #[Validate]
    public $cantidad;
    public $nombreMedicamento;
    public $formaFarmaceuticaMostrar;
    public $stockMed;
    public $listadoServicios = [];
    public $piso;
    public $id_unidad_servicio;
    public $pisoSwitch = false;

    public $unidadesRecepcionan = [];
    public $id_unidad_solicita;

    public $collectionListadoUnidades;

    public function render()
    {
        return view('livewire.solicitud-insumos.solicitud-insumos-edit');
    }

    public function mount()
    {
        $this->datosSolicitud($this->idSolicitud);
        $this->dispatch('cargarIdUnidadServicio', id: $this->id_unidad_servicio);
        $this->listadoServicios = $this->listadoUnidades();
        $this->collectionListadoUnidades = collect($this->listadoServicios);
        $this->medicamentos = Medicamento::where('estado', true)->get();
        $this->unidadRecep();
    }

    public function rules()
    {
        return [
            'fecha_solicitud' => 'required|date',
            'id_unidad_recepciona' => 'required',
            'motivo' => 'required',
        ];
    }

    public function unidadRecep()
    {
        $datosUnidades = new ListaUnidades();
        if ($datosUnidades->listaUnidades() != null) {
            $this->unidadesRecepcionan = array_filter($datosUnidades->listaUnidades(), function ($unidad) {
                return in_array($unidad['nombre'], ['FARMACIA', 'ALMACEN DE FARMACIA']);
            });
        } else {
            $this->unidadesRecepcionan = [];
        }
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

    public function obtenerNombreUnidadSeleccionada($idUnidadSeleccionada)
    {
        return $this->collectionListadoUnidades->firstWhere('id', $idUnidadSeleccionada)['nombre'] ?? null;
    }

    #[On('editarSolicitud')]
    public function editarSolicitud()
    {

        if ($this->medicamentosAgregados != []) {
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

                $medicamentoSolicitudContador = 0;

                $medicamentosSolicitadosAnteriores = MedicamentoSolicitud::where('id_solicitud', $solicitudEditada->id)->get();
                foreach ($medicamentosSolicitadosAnteriores as $key => $medicamentoAnterior) {
                    $medicamentoAnterior->delete();
                }

                foreach ($this->medicamentosAgregados as $key => $medicamentoAgregado) {
                    $nuevoMedicamentoSolicitud = MedicamentoSolicitud::create([
                        'cantidad_solicitada' => $medicamentoAgregado['cantidad'],
                        'id_solicitud' => $solicitudEditada->id,
                        'id_medicamento' => $medicamentoAgregado['id_medicamento'],
                        'id_user_created' => $solicitudEditada->id_user_created,
                        'id_user_updated' => auth()->user()->id,
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
                    DB::commit();
                    $this->dispatch('guardado');

                    //CREA PDF CON DATOS DE EDICION Y REEMPLAZA EN EL FTP
                    $url = route('farmacia.solicitudinsumos.pdf', ['modo' => 'edit', 'id' => $solicitudEditada->id]);
                    $this->js("window.open('{$url}', '_blank');");

                    return redirect()->route('solicitud_insumos.index');
                } else {
                    DB::rollBack();
                    $this->dispatch('noGuardado');
                }
            } catch (\Throwable $th) {
                dd($th);
                DB::rollBack();
                $this->dispatch('noGuardado');
            }
        } else {
            $this->dispatch('medicamentosVacio');
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
        $listaMedicamentos = MedicamentoSolicitud::where('id_solicitud', $solicitud->id)->with('medicamentoMedicamentoSolicitud')->get();
        foreach ($listaMedicamentos as $key => $medicamentoSol) {
            //PARA MOSTRAR EL STOCK ACTUAL DEL MEDICAMENTOS SEGUN LA UNIDAD
            $stockMed = MedicamentoStock::where('id_medicamento', $medicamentoSol->id_medicamento)
                ->where('id_unidad', $this->id_unidad_recepciona)
                ->sum('stock_actualizado');


            $this->medicamentosAgregados[] = [
                'id_medicamento' => $medicamentoSol->id_medicamento,
                'codigo_medicamento' => $medicamentoSol->medicamentoMedicamentoSolicitud->codigo_medicamento,
                'nombre' => $medicamentoSol->medicamentoMedicamentoSolicitud->nombre,
                'forma_farmaceutica' => $medicamentoSol->medicamentoMedicamentoSolicitud->forma_farmaceutica,
                'concentracion' => $medicamentoSol->medicamentoMedicamentoSolicitud->concentracion,
                'stock_actual' => $stockMed,
                'cantidad' => $medicamentoSol->cantidad_solicitada,
            ];
        }
    }

    public static function obtenerIdHospitalizacion()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'SERVICIO DE HOSPITALIZACIÓN')['id'];
    }

    #[On('servicioSeleccionado')]
    public function servicioSeleccionado()
    {
        //dd('entra locooo');
        if ($this->obtenerIdHospitalizacion() === (int) $this->id_unidad_servicio) {
            $this->pisoSwitch = true;
        } else {
            $this->pisoSwitch = false;
            $this->piso = null;
        }
    }

    public function validacionUnidadServicio()
    {
        //dd('entra');
        if ($this->id_unidad_servicio === null || trim($this->id_unidad_servicio) === "") {
            $this->dispatch('seleccioneUnidadServicio');
        }
        return;
    }

    public function actualizarStock()
    {
        $this->medicamentosAgregados = [];
        $listaMedicamentos = MedicamentoSolicitud::where('id_solicitud', $this->idSolicitud)->with('medicamentoMedicamentoSolicitud')->get();
        foreach ($listaMedicamentos as $key => $medicamentoSol) {
            //PARA MOSTRAR EL STOCK ACTUAL DEL MEDICAMENTOS SEGUN LA UNIDAD
            $stockMed = MedicamentoStock::where('id_medicamento', $medicamentoSol->id_medicamento)
                ->where('id_unidad', $this->id_unidad_recepciona)
                ->sum('stock_actualizado');


            $this->medicamentosAgregados[] = [
                'id_medicamento' => $medicamentoSol->id_medicamento,
                'codigo_medicamento' => $medicamentoSol->medicamentoMedicamentoSolicitud->codigo_medicamento,
                'nombre' => $medicamentoSol->medicamentoMedicamentoSolicitud->nombre,
                'forma_farmaceutica' => $medicamentoSol->medicamentoMedicamentoSolicitud->forma_farmaceutica,
                'concentracion' => $medicamentoSol->medicamentoMedicamentoSolicitud->concentracion,
                'stock_actual' => $stockMed,
                'cantidad' => $medicamentoSol->cantidad_solicitada,
            ];
        }
    }

    #[On('medicamentoSeleccionado')]
    public function medicamentoSeleccionado()
    {
        if ($this->id_medicamento != null || $this->id_medicamento != '') {
            $nombreMed = Medicamento::where('id', $this->id_medicamento)->first();
            $this->nombreMedicamento = $nombreMed->nombre;
            $this->formaFarmaceuticaMostrar = $nombreMed->forma_farmaceutica;
            $this->concentracionMostrar = $nombreMed->concentracion;
            $this->stockMed = MedicamentoStock::where('id_medicamento', $nombreMed->id)
                ->where('id_unidad', $this->id_unidad_recepciona)
                ->sum('stock_actualizado');
        } else {
            $this->nombreMedicamento = null;
            $this->formaFarmaceuticaMostrar = null;
            $this->stockMed = null;
        }
    }

    public function agregarMedicamento()
    {
        if ($this->id_medicamento === null) {
            $this->dispatch('sinMedicamentoSeleccionado');
            return;
        }
        $this->validate([
            'id_medicamento'  => 'required',
            'cantidad' => 'required | max:10 |gt:0',
        ]);

        if ($this->id_medicamento != null && $this->cantidad != null) {
            if ($this->stockMed >= $this->cantidad) {
                $datosMedicamento = Medicamento::where('id', $this->id_medicamento)->first();

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
            } else {
                $this->dispatch('cantidadNoDisponible');
            }
        } else {
            $this->dispatch('datosIncompletos');
        }
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

    public function atras()
    {
        return redirect()->route('solicitud_insumos.index');
    }

    public function cancelarSolicitud()
    {
        return redirect()->route('solicitud_insumos.index');
    }
}
