<?php

namespace App\Livewire\Farmacia\Farmacia\Receta;

use App\Models\ConsultaExterna\Receta;
use App\Models\ConsultaExterna\RecetaMedicamento;
use App\Models\Farmacia\MedicamentoStock;
use App\Models\Farmacia\Movimiento;
use App\Services\VerificarCaducidadRecetas;
use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\ServicioPersona;
use App\Services\ServicioPersonaInstitucion;
use App\Services\DatosEspecialista;
use App\Services\Farmacia\AnularRecetasService;
use App\Services\ListaUnidades;
use App\Models\Administracion\Role;
use App\Models\Administracion\RolUsuario;
use App\Models\Administracion\NotificacionUsuarios;
use App\Models\Administracion\Persona;
use App\Models\Farmacia\Medicamento;
use App\Models\Plataforma\Especialista;

class RecetaMain extends Component
{
    public $idReceta;
    public $verificarServicioRisbou; // el servicio del gay

    public function render()
    {
        return view('livewire.farmacia.farmacia.receta.receta-main');
    }

    public function mount()
    {
        $verificar = new VerificarCaducidadRecetas();
        $verificar->verificarCaducidadRecetas();
        $this->envioNotificacionesFarmaciaMedicamentoFechaVencimiento();
        $this->envioNotificacionFarmaciaMedicamentoPorAgotarse();
    }
    //envio de notificaciones
    public function envioNotificacionesFarmaciaMedicamentoFechaVencimiento()
    {
        $hoy = Carbon::today();
        $limite_6 = Carbon::today()->addMonth(6);
        $medicamentos_por_vencer = MedicamentoStock::with('medicamentoMedicamentoStock')
            ->where('id_unidad', 89)
            ->where('stock_actualizado', '>', 0)
            ->whereBetween('fecha_vencimiento', [$hoy, $limite_6])->get();

        $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
        $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();
        if ($medicamentos_por_vencer->isNotEmpty()) {
            foreach ($medicamentos_por_vencer as $medicamento) {
                $meses_para_vencimiento = Carbon::now()->diffInMonths(Carbon::parse($medicamento->fecha_vencimiento), false);
                foreach ($receptores as $key => $receptor) {
                    if ($meses_para_vencimiento == 0) {
                        $asunto = '🔴¡MEDICAMENTO VENCE EN MENOS DE 1 MES!';
                        $mensaje = 'El medicamento: ' .
                            $medicamento->medicamentoMedicamentoStock->codigo_medicamento . ' ' .
                            $medicamento->medicamentoMedicamentoStock->nombre .
                            ' vencerá demasiado pronto, en fecha: (' .
                            Carbon::parse($medicamento->fecha_vencimiento)->format('d/m/Y') .
                            ').';
                    } else {
                        $asunto = ' ⚠️ MEDICAMENTO POR VENCER';
                        $mensaje = 'El medicamento: ' .
                            $medicamento->medicamentoMedicamentoStock->codigo_medicamento . ' ' .
                            $medicamento->medicamentoMedicamentoStock->nombre .
                            ' vence en aproximadamente ' . $meses_para_vencimiento . ' meses.';
                    }
                    NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 3,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => $asunto,
                        'mensaje' => $mensaje,
                    ]);
                }
            }
        }
    }

    public function envioNotificacionFarmaciaMedicamentoPorAgotarse()
    {
        $medicamento_por_agotarse = MedicamentoStock::join('farmacia.medicamento as m', 'm.id', '=', 'medicamento_stock.id_medicamento')
            ->select(
                'medicamento_stock.id_medicamento',
                DB::raw('SUM(medicamento_stock.stock_actualizado) as total_stock'),
                'm.stock_minimo_almacen',
                'm.nombre',
                'm.codigo_medicamento'
            )
            ->where('medicamento_stock.id_unidad', 76)
            ->whereColumn('m.stock_minimo_almacen', '>=', 'medicamento_stock.stock_actualizado')
            ->groupBy(
                'medicamento_stock.id_medicamento',
                'm.nombre',
                'm.codigo_medicamento',
                'm.stock_minimo_almacen'
            )
            ->get();
        //dd($medicamento_por_agotarse);
        $roles = Role::whereIn('name', ['Encargado de Almacen de Farmacia', 'Regente de Farmacia'])->get();
        $receptores = RolUsuario::whereIn('role_id', $roles->pluck('id'))->get();

        if ($medicamento_por_agotarse->isNotEmpty()) {

            foreach ($medicamento_por_agotarse as $medicamento) {
                //dd($medicamento->stock_minimo_almacen, $medicamento->total_stock);
                if (empty($medicamento->stock_minimo_almacen) || $medicamento->stock_minimo_almacen == 0) {
                    $porcentaje = 0;
                } else {
                    $porcentaje = ($medicamento->total_stock / $medicamento->stock_minimo_almacen) * 100;
                }
                //dd($porcentaje);
                foreach ($receptores as $key => $receptor) {
                    if ($porcentaje < 50) {
                        $asunto = '🔴¡MEDICAMENTO CON MENOS DE 50% DE STOCK!';
                    } elseif ($porcentaje >= 50) {
                        $asunto = ' ⚠️ MEDICAMENTO POR AGOTARSE';
                    }
                    $mensaje = 'El medicamento ' . $medicamento->codigo_medicamento . ' ' . $medicamento->nombre . ' tiene ' . round($porcentaje) . '% de stock restante.';
                    NotificacionUsuarios::create([
                        'estado' => 'NO LEIDO',
                        'prioridades' => 3,
                        'id_usu_emisor' => auth()->user()->id,
                        'id_usu_receptor' => $receptor->model_id,
                        'fecha_hora_enviada' => Carbon::now(),
                        'asunto' => $asunto,
                        'mensaje' => $mensaje,
                    ]);
                }
            }
        }
    }

    #[On('verReceta')]
    public function verReceta($data)
    {
        //dd('pantalla de recetas');
        $this->resetearReceta();
        $this->mostrarDatosReceta($data);
        $this->dispatch('abriModalVerRecetaM');
    }

    public $nro_receta, $receta, $datos_persona, $codigo_institucional, $medicamentos = [],
        $nombre_especialista, $especialidad, $id_estado, $tipo_consulta;
    public $usuario_registra;
    public $fecha_registro, $dispensado = false, $usuario_dispensa, $fecha_dispensa, $fue_anulado = false, $usuario_anula, $fecha_anula;

    public function mostrarDatosReceta($id)
    {
        $this->receta = Receta::with(
            'usuarioCreador',
            'usuarioDispensaReceta',
            'usuarioAnuladorReceta',
            'personaTranscribeReceta'
        )
            ->where('id', $id)->first();
        //dd($this->receta);
        $this->nro_receta = $this->receta->nro_receta;
        $this->id_estado = $this->receta->id_estado_validacion;
        $this->tipo_consulta = $this->receta->tipoConsultaReceta->nombre;;

        $dato_per = new ServicioPersona();
        $this->datos_persona = $dato_per->datosPersona($this->receta->id_persona);
        //dd($this->datos_persona);


        $dato_inst = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_inst->personaInstitucion($this->receta->id_persona);
        $instituciones = $persona_institucion['instituciones'];
        $this->codigo_institucional = $instituciones[0]['codigo_institucional'] ?? '';

        $this->medicamentos = RecetaMedicamento::where('id_receta', $id)->get();

        // Servicio de Especialista
        $dato_esp = new DatosEspecialista();
        $datos_esp = $dato_esp->datosEspecialista($this->receta->id_especialista);
        $this->nombre_especialista = $datos_esp['persona_especialista']['p_apellido'] . ' ' . $datos_esp['persona_especialista']['s_apellido'] . ' ' . $datos_esp['persona_especialista']['nombres'];
        $this->especialidad = $datos_esp['especialidad_especialista']['especialidad'];

        //datos para el seguimieto
        $this->usuario_registra = $this->receta->usuarioCreador->name ?? 'SIN DATO';
        $this->fecha_registro = $this->receta->created_at;
        if ($this->receta->id_usuario_dispensa) {
            //dd('asdas');
            $this->dispensado = true;
            $this->usuario_dispensa = $this->receta->usuarioDispensaReceta->name ?? 'SIN DATO';
            $this->fecha_dispensa = $this->receta->fecha_dispensado;
        }

        if ($this->receta->id_usuario_anula) {
            //dd('asdas');
            $this->fue_anulado = true;
            $this->usuario_anula = $this->receta->usuarioAnuladorReceta->name ?? 'SIN DATO';
            $this->fecha_anula = $this->receta->fecha_anulado;
        }

        //dd($this->fecha_registro);
    }

    public function resetearReceta()
    {
        $this->reset('usuario_registra', 'dispensado', 'fecha_registro', 'fue_anulado');
    }

    private function verificarStockReceta(int $idReceta)
    {
        $recetaMedicamentos = RecetaMedicamento::with('medicamentoRecetaMedicamento')
            ->where('id_receta', $idReceta)
            ->get();

        $idsMedicamentos = $recetaMedicamentos->pluck('id_medicamento');

        $stocksActuales = MedicamentoStock::whereIn('id_medicamento', $idsMedicamentos)
            ->where('id_unidad', 76)
            ->where('stock_actualizado', '>', 0)
            ->groupBy('id_medicamento')
            ->selectRaw('id_medicamento, SUM(stock_actualizado) as total_stock')
            ->pluck('total_stock', 'id_medicamento');

        $arraycitoCantidades = [];
        $hayInsuficiente = false;

        foreach ($recetaMedicamentos as $recetaMedicamento) {
            $cantidadStock = $stocksActuales[$recetaMedicamento->id_medicamento] ?? 0;
            $existeSuficienteStock = ($cantidadStock >= $recetaMedicamento->cantidad);

            if (!$existeSuficienteStock) {
                $hayInsuficiente = true;
            }

            $arraycitoCantidades[] = [
                'codigo_medicamento' => $recetaMedicamento->medicamentoRecetaMedicamento->codigo_medicamento,
                'nombre_medicamento' => $recetaMedicamento->medicamentoRecetaMedicamento->nombre,
                'existe_stock_suficiente' => $existeSuficienteStock,
            ];
        }

        return [
            'stock_suficiente' => !$hayInsuficiente,
            'medicamentos_observados' => $arraycitoCantidades,
        ];
    }

    /*private function verificarStockMedicamento(int $idMedicamento, int $idUnidad)
    {
        $medicamentosStock = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $idUnidad)
            ->where('stock_actualizado', '>', 0)
            ->get();

        $stockActual = 0;
        foreach ($medicamentosStock as $key => $medicamentoStock) {
            $stockActual = $stockActual + $medicamentoStock->stock_actualizado;
        }
        return $stockActual;
    }*/

    #[On('entregarReceta')]
    public function entregarReceta($data)
    {
        $revisionStockMedicamento = $this->verificarStockReceta($data);
        if ($revisionStockMedicamento['stock_suficiente']) {
            $this->idReceta = $data;
            $this->dispatch('preguntaEntregarReceta');
        } else {
            $this->dispatch('sinStockSuficiente', arraycitoMedicamento: $revisionStockMedicamento['medicamentos_observados']);
        }
    }

    #[On('recetaEntregada')]
    public function recetaEntregada()
    {
        try {
            $receta = Receta::find($this->idReceta);

            if ($receta) {
                $receta->id_usuario_dispensa = auth()->user()->id;
                $receta->fecha_dispensado = Carbon::now();
                $receta->fecha_entrega = Carbon::now();
                $receta->id_estado_validacion = 2;
                $receta->save();

                /*$datosActualizacionMovimiento = [
                    'id_corresponde' => $receta->id,
                    'id_identificador_tabla' => 3,
                    'nro_documento' => $receta->nro_receta,
                    'id_unidad' => 76,
                ];

                $this->actualizarEstadoValidacionMovimiento($datosActualizacionMovimiento);*/

                $datosNecesariosParaMovimientos = [
                    'id_corresponde' => $receta->id,
                    'id_unidad' => 76,
                    'nro_documento' => $receta->nro_receta,
                ];

                $medicamentosReceta = RecetaMedicamento::where('id_receta', $receta->id)->get();
                foreach ($medicamentosReceta as $key => $medicamentoReceta) {
                    $this->procesarLotesEntrega(
                        $medicamentoReceta->id_medicamento,
                        $medicamentoReceta->cantidad,
                        $datosNecesariosParaMovimientos,
                        $receta->id_especialista,
                        $receta->id_persona
                    );
                }
            }
            $this->dispatch('recetaEntregadaSatisfactoriamente');
            $this->dispatch('pg:eventRefresh-RecetasFarmaciaTable');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            $this->dispatch('recetaNoEntregada');
        }
    }

    public function actualizarEstadoValidacionMovimiento(array $datosActualizacionMovimiento)
    {
        $medicamentosPorReceta = RecetaMedicamento::where('id_receta', $datosActualizacionMovimiento['id_corresponde'])
            ->get();

        foreach ($medicamentosPorReceta as $medicamentoPorReceta) {
            $movimientos = Movimiento::where('id_corresponde', $datosActualizacionMovimiento['id_corresponde'])
                ->where('id_identificador_tabla', $datosActualizacionMovimiento['id_identificador_tabla'])
                ->where('nro_documento', $datosActualizacionMovimiento['nro_documento'])
                ->where('id_medicamento', $medicamentoPorReceta->id_medicamento)
                ->where('id_unidad', $datosActualizacionMovimiento['id_unidad'])
                ->get();

            foreach ($movimientos as $movimiento) {
                $movimiento->id_estado_validacion = 2;
                $movimiento->stock_real = true;
                $movimiento->id_user_updated = auth()->user()->id;
                $movimiento->save();
            }
        }
    }

    /*public static function obtenerIdFarmacia()
    {
        $datosUnidades = new ListaUnidades();
        return collect($datosUnidades->listaUnidades())->firstWhere('nombre', 'FARMACIA')['id'];
    }*/

    #[On('anularReceta')]
    public function anularReceta($data)
    {
        $this->idReceta = $data;
        $this->dispatch('preguntarAnularReceta');
    }

    #[On('recetaAnulada')]
    public function recetaAnulada($motivo)
    {
        $anularRecetasMovStock = new AnularRecetasService();
        //$anularRecetasMovStock->revertirStocksRecetasAnuladas($this->idReceta);

        //try {
        DB::beginTransaction();
        $receta = Receta::find($this->idReceta);
        if ($receta) {
            $medicamentos = RecetaMedicamento::where('id_receta', $receta->id)->get();
            //dd($medicamentos);
            if ($medicamentos->isNotEmpty()) {
                RecetaMedicamento::where('id_receta', $receta->id)
                    ->update([
                        'estado_validez' => false,
                    ]);
            }

            $anularRecetasMovStock->revertirStocksRecetasAnuladas($this->idReceta, true);

            $receta->id_usuario_anula = auth()->user()->id;
            $receta->fecha_anulado = Carbon::now();
            $receta->motivo_anulacion = $motivo;
            $receta->id_estado_validacion = 3;
            $receta->save();
        }
        DB::commit();
        $this->dispatch('recetaAnuladaSatisfactoriamente');
        $this->dispatch('pg:eventRefresh-RecetasFarmaciaTable');
        /*} catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('recetaNoAnulada');
        }*/
    }


    public function abrirRegistroReceta()
    {
        $this->dispatch('modal_registrar_receta');
        $this->dispatch('limpiarDatosJs');
    }

    #[On('recuperar_pdf')]
    public function recuperar_pdf($data)
    {
        $id_receta = $data;
        $this->dispatch('abrirPopupReceta', id: $id_receta);
    }

    public function funcionModalLista()
    {

        $this->dispatch('modalAbrirListaPersonas');
    }

    #[On('funcionEnviarRuta')]
    public function funcionEnviarRuta($data)
    {
        $id_persona = $data;
        $dato_inst = new ServicioPersonaInstitucion();
        $persona_institucion = $dato_inst->personaInstitucion($id_persona);

        if ($persona_institucion['estado_vigencia'] == 'NO AFILIADO') {
            $this->dispatch('cerrarPersonasListaHabilitarMed');
            $this->dispatch('alertaNoAfiliado');
        } else {
            $this->redirect(route('farmacia.farmacia.habilitar_medicamento.create', ['id' => $id_persona]));
        }
        //dd($persona_institucion);
    }

    //PARA GENERAR LOS DATOS DE REGISTRO EN MEDICAMENTO STOCK Y EN MOVIMIENTOS A LA HORA DE ENTREGAR

    private function procesarLotesEntrega(int $idMedicamento, int $cantidad, array $datosNecesarios, int $idEspecialista, int $idPersona)
    {
        $lotes = MedicamentoStock::where('id_medicamento', $idMedicamento)
            ->where('id_unidad', $datosNecesarios['id_unidad'])
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

            /*$datosEspecialista = collect($this->listaEspecialistas)
                ->firstWhere('id', $this->datoHistoriaClinica['id_especialista']);*/

            $datosEspecialista = Especialista::with('personaEspecialista')->find($idEspecialista);
            /*$nombreEspecialista = implode(' ', array_filter([
                $datosEspecialista['persona_especialista']['p_apellido'],
                $datosEspecialista['persona_especialista']['s_apellido'],
                $datosEspecialista['persona_especialista']['nombres'],
            ]));*/
            $nombreEspecialista = implode(' ', array_filter([
                $datosEspecialista->personaEspecialista->p_apellido,
                $datosEspecialista->personaEspecialista->s_apellido,
                $datosEspecialista->personaEspecialista->nombre,
            ]));

            $personaPaciente = Persona::find($idPersona);
            $nombrePaciente = implode(' ', array_filter([
                $personaPaciente->p_apellido,
                $personaPaciente->s_apellido,
                $personaPaciente->nombres,
            ]));

            $datosParaMovimientos = [
                'id_corresponde' => $datosNecesarios['id_corresponde'],
                'id_medicamento' => $idMedicamento,
                'id_unidad' => $datosNecesarios['id_unidad'],
                'cantidad' => $cantidadARestar,
                'dato_solicitante' => $nombrePaciente,
                'nro_documento' => $datosNecesarios['nro_documento'],
                'dato_interesado' => $nombreEspecialista,
                'nro_lote' => $lote->nro_lote,
                'precio_unitario_peps' => $lote->precio_unitario,
                'fecha_vencimiento' => $lote->fecha_vencimiento,
            ];

            $lote->stock_actualizado = $disponible - $cantidadARestar;
            $lote->save();

            $cantidad -= $cantidadARestar;

            $this->registrarMovimientoSolicitudUnidadAFarmacia($datosParaMovimientos);
        }
    }

    public function registrarMovimientoSolicitudUnidadAFarmacia($datosNecesarios)
    {
        //PARA LA TABLA DE MOVIMIENTOS
        /*$movimientoAnterior = Movimiento::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->orderBy('id', 'desc')
            ->first();*/

        $saldoMedStock = MedicamentoStock::where('id_medicamento', $datosNecesarios['id_medicamento'])
            ->where('id_unidad', $datosNecesarios['id_unidad'])
            ->sum('stock_actualizado');

        $nuevoSaldo = $saldoMedStock;

        $nuevoMovimiento = Movimiento::create([
            'id_corresponde' => $datosNecesarios['id_corresponde'],
            'id_identificador_tabla' => 3,
            'dato_solicitante' => $datosNecesarios['dato_solicitante'],
            'nro_documento' => $datosNecesarios['nro_documento'],
            'fecha' => Carbon::now(),
            'dato_interesado' => $datosNecesarios['dato_interesado'],
            'cantidad_ingreso' => 0,
            'cantidad_salida' => $datosNecesarios['cantidad'],
            'saldo' => $nuevoSaldo,
            'id_estado_validacion' => 2,
            'id_medicamento' => $datosNecesarios['id_medicamento'],
            'id_unidad' => $datosNecesarios['id_unidad'],
            'nro_lote' => $datosNecesarios['nro_lote'],
            'id_user_created' => auth()->user()->id,
            'precio_unitario_peps' => $datosNecesarios['precio_unitario_peps'],
            'fecha_vencimiento' => $datosNecesarios['fecha_vencimiento'],
            'stock_real' => true,

        ]);

        //FIN DE LA TABLA DE MOVIMIENTOS
    }
}
