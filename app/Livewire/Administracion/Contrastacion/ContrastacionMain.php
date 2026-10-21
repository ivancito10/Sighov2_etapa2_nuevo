<?php

namespace App\Livewire\Administracion\Contrastacion;

use App\Models\Administracion\ContrastacionDatos;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use App\Models\Vigencia\AutorizacionInterior;
use App\Services\ActualizacionDatosSegipS1;
use App\Services\GrupoFamiliarPersonaContrastacion;
use App\Services\ObtenerPoblacion;
use App\Services\VerificacionDatosPersonaAsussIOV2;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

use function Pest\Laravel\json;

class ContrastacionMain extends Component
{
    public $tipo_formulario = 1;
    public $grupoFamiliar, $primerDato;

    public $idPersona;
    public $complemento;
    #[Validate]
    public $ci, $nombres, $p_apellido, $s_apellido, $fecha_nacimiento, $sexo, $matricula_seguro;
    #[Validate]
    public $telefono, $telefono_referencia, $nombre_referencia;
    // public $es_titular = false, $es_beneficiario = false;
    public $idTablaCorresponde;
    public $deshabilitarEspacios;
    public $errorAsuss;
    public $referencialesActualizados;
    public $fechaActualizacionDatosReferenciales;
    public $horaActualizacionDatosReferenciales;


    public function render()
    {
        return view('livewire.Administracion.contrastacion.contrastacion-main');
    }

    public function rules()
    {
        return [
            'nombres' => 'required|min:2| max:50',
            'sexo' =>  'required|min:1',
            'ci' =>  'required|min:2| max:15',
            'complemento' =>  'required|min:1| max:3',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . now()->endOfYear()->toDateString(),
            'telefono' => 'required|min:6| max:8',
            'nombre_referencia' => 'required|min:2',
            'telefono_referencia' => 'required|min:6',
        ];
    }

    public function mount()
    {
        $this->errorAsuss = false;
        $this->referencialesActualizados = false;
        $this->deshabilitarEspacios = false;
        $datosGrupoFamiliar = new GrupoFamiliarPersonaContrastacion();
        $this->grupoFamiliar = $datosGrupoFamiliar->encontrarPersona(auth()->user()->id_persona);

        $encontrarPosicionUsuario = array_search(auth()->user()->id_persona, array_column($this->grupoFamiliar, 'id'));

        if ($encontrarPosicionUsuario !== false) {
            $datosUsuario = $this->grupoFamiliar[$encontrarPosicionUsuario];

            unset($this->grupoFamiliar[$encontrarPosicionUsuario]);
            $this->grupoFamiliar = array_values($this->grupoFamiliar);
            array_unshift($this->grupoFamiliar, $datosUsuario);

            if (Str::contains($datosUsuario['tipo_asegurado'], 'BENEFICIARIO')) {
                $this->grupoFamiliar = [$datosUsuario];
            }
        }

        $this->primerDato = (object) $this->grupoFamiliar[0];

        //dd($this->titular);
    }

    public function verDatos($idPersona)
    {
        $this->obtenerDatosPersonales($idPersona);
        $this->dispatch('modalVerActualizacionDatos');
    }

    public function formularioActualizacion($idPersona)
    {
        $this->tipo_formulario = 2;
        $this->idPersona = $idPersona;
        $this->obtenerDatosPersona($idPersona);
    }

    public function actualizarDatosPersona($idPersona)
    {
        $servicioObtenerPoblacion = new ObtenerPoblacion();
        $datosAfiliado = $servicioObtenerPoblacion->obtenerDatosAfiliacionId($idPersona);
        //dd($datosAfiliado);
        $this->ci = $datosAfiliado['ci'];
        $this->complemento = $datosAfiliado['complemento'];
        $this->nombres = $datosAfiliado['nombres'];
        $this->p_apellido = $datosAfiliado['p_apellido'];
        $this->s_apellido = $datosAfiliado['s_apellido'];
        $this->fecha_nacimiento = $datosAfiliado['fecha_nacimiento'];
        $this->sexo = $datosAfiliado['sexo'];
        $this->matricula_seguro = $datosAfiliado['matricula_seguro'];
    }

    public function obtenerDatosPersona($idPersona)
    {
        $this->revisionEstadosFormulario($idPersona);
        $this->obtenerDatosPersonales($idPersona);
    }

    public function revisionEstadosFormulario($idPersona)
    {
        $this->deshabilitarEspacios = $this->verificarExistenciaRegistroContrastacion($idPersona);
        $this->referencialesActualizados = $this->verificarExistenciaRegistroConstrastacionReferenciales($idPersona)['existe'];
        $this->fechaActualizacionDatosReferenciales = $this->verificarExistenciaRegistroConstrastacionReferenciales($idPersona)['fecha'];
        $this->horaActualizacionDatosReferenciales = $this->verificarExistenciaRegistroConstrastacionReferenciales($idPersona)['hora'];
    }

    public function obtenerDatosPersonales($idPersona)
    {
        $servicioObtenerPoblacion = new ObtenerPoblacion();
        $datosAfiliado = $servicioObtenerPoblacion->obtenerDatosAfiliacionId($idPersona);
        //dd($datosAfiliado);
        $this->ci = $datosAfiliado['ci'];
        $this->complemento = $datosAfiliado['complemento'];
        $this->nombres = $datosAfiliado['nombres'];
        $this->p_apellido = $datosAfiliado['p_apellido'];
        $this->s_apellido = $datosAfiliado['s_apellido'];
        $this->fecha_nacimiento = $datosAfiliado['fecha_nacimiento'];
        $this->sexo = $datosAfiliado['sexo'];
        $this->matricula_seguro = $datosAfiliado['matricula_seguro'];

        if ($datosAfiliado['tipo_asegurado'] === 'TITULAR') {
            $this->telefono = $datosAfiliado['datos_afiliacion']['telefono'];
            $this->telefono_referencia = $datosAfiliado['datos_afiliacion']['telefono_referencia'];
            $this->nombre_referencia = $datosAfiliado['datos_afiliacion']['nombre_referencia'];
            $this->idTablaCorresponde = $datosAfiliado['datos_afiliacion']['id_titular'];
        }

        if (Str::contains($datosAfiliado['tipo_asegurado'], 'BENEFICIARIO')) {
            if ($datosAfiliado['tipo_asegurado'] === 'BENEFICIARIO DEL INTERIOR') {
                $this->telefono = $datosAfiliado['datos_afiliacion']['telefono'];
                $this->telefono_referencia = $datosAfiliado['datos_afiliacion']['telefono_referencia'];
                $this->nombre_referencia = $datosAfiliado['datos_afiliacion']['datos_referencia'];
                $this->idTablaCorresponde = $datosAfiliado['datos_afiliacion']['id_convenio_interior'];
            } else {
                $this->telefono = $datosAfiliado['datos_afiliacion']['telefono'];
                $this->telefono_referencia = $datosAfiliado['datos_afiliacion']['telefono_referencia'];
                $this->nombre_referencia = $datosAfiliado['datos_afiliacion']['nombre_referencia'];
                $this->idTablaCorresponde = $datosAfiliado['datos_afiliacion']['id_beneficiario'];
            }
        }

        if ($datosAfiliado['tipo_asegurado'] === 'TITULAR CONVENIO') {
            $this->telefono = $datosAfiliado['datos_afiliacion']['telefono'];
            $this->telefono_referencia = $datosAfiliado['datos_afiliacion']['telefono_referencia'];
            $this->nombre_referencia = $datosAfiliado['datos_afiliacion']['nombre_referencia'];
            $this->idTablaCorresponde = $datosAfiliado['datos_afiliacion']['id_convenio_emergencia'];
        }

        if ($datosAfiliado['tipo_asegurado'] === 'TITULAR DEL INTERIOR') {
            $this->telefono = $datosAfiliado['datos_afiliacion']['telefono'];
            $this->telefono_referencia = $datosAfiliado['datos_afiliacion']['telefono_referencia'];
            $this->nombre_referencia = $datosAfiliado['datos_afiliacion']['datos_referencia'];
            $this->idTablaCorresponde = $datosAfiliado['datos_afiliacion']['id_convenio_interior'];
        }

        if ($datosAfiliado['tipo_asegurado'] === 'ESTUDIANTE') {
            $this->telefono = $datosAfiliado['datos_afiliacion']['telefono'];
            $this->telefono_referencia = $datosAfiliado['datos_afiliacion']['telefono_referencia'];
            $this->nombre_referencia = $datosAfiliado['datos_afiliacion']['nombre_referencia'];
            $this->idTablaCorresponde = $datosAfiliado['datos_afiliacion']['id_estudiante'];
        }
    }

    public function actualizarMatricula()
    {
        $this->matricula_seguro = $this->generarMatricula($this->nombres, $this->p_apellido, $this->s_apellido, $this->fecha_nacimiento, $this->sexo);
    }

    public function verificarDatosIntroducidos()
    {
        if ($this->verificarExistenciaRegistroContrastacion($this->idPersona)) {
            $this->deshabilitarEspacios = true;
        } else {
            $this->ci = trim($this->ci);
            $this->complemento = trim($this->complemento) === "" ? null : trim($this->complemento);
            $this->nombres = trim($this->nombres);
            $this->p_apellido = trim($this->p_apellido);
            $this->s_apellido = trim($this->s_apellido);
            $this->fecha_nacimiento = trim($this->fecha_nacimiento);

            $servicioContrastar = new VerificacionDatosPersonaAsussIOV2();
            $datosPersona = [
                'ci' => $this->ci,
                'complemento' => $this->complemento,
                'tipoPersona' => "1",
                'nombres' => $this->nombres,
                'apellidoPaterno' => $this->p_apellido,
                'apellidoMaterno' => $this->s_apellido,
                'fechaNacimiento' => Carbon::parse($this->fecha_nacimiento)->format('d/m/Y'),
            ];
            //dd($datosPersona);
            $respuestaServicioConstrastacion = $servicioContrastar->verificarPersonaSegip($datosPersona);
            /*dd(implode('', array_filter([
            $this->ci,
            $this->complemento
        ])));*/
            //dd($respuestaServicioConstrastacion);
            //$contrastacionExitosa = 0;

            if ($respuestaServicioConstrastacion['respuestaAsuss']) {
                //dd($respuestaServicioConstrastacion);
                if ($respuestaServicioConstrastacion['numeroDocumento'] !== "1") {
                    if ($respuestaServicioConstrastacion['numeroDocumento'] === "0") {
                        $this->dispatch('datoPersonalErroneo', campo: 'CARNET IDENTIDAD');
                        return;
                    }

                    if ($respuestaServicioConstrastacion['numeroDocumento'] === "2") {
                        $this->dispatch('datoPersonalVacio', campo: 'CARNET IDENTIDAD');
                        return;
                    }
                    //$contrastacionExitosa++;
                }

                if ($respuestaServicioConstrastacion['complemento'] !== "1") {

                    if ($respuestaServicioConstrastacion['complemento'] === "0") {
                        $this->dispatch('datoPersonalErroneo', campo: 'COMPLEMENTO');
                        return;
                    }

                    if ($respuestaServicioConstrastacion['complemento'] === "2") {
                        $this->dispatch('datoPersonalVacio', campo: 'COMPLEMENTO');
                        return;
                    }
                    //$contrastacionExitosa++;
                }

                if ($respuestaServicioConstrastacion['primerApellido'] !== "1") {

                    if ($respuestaServicioConstrastacion['primerApellido'] === "0") {
                        $this->dispatch('datoPersonalErroneo', campo: 'PRIMER APELLIDO');
                        return;
                    }

                    if ($respuestaServicioConstrastacion['primerApellido'] === "2") {
                        $this->dispatch('datoPersonalVacio', campo: 'PRIMER APELLIDO');
                        return;
                    }
                    //$contrastacionExitosa++;
                }

                if ($respuestaServicioConstrastacion['segundoApellido'] !== "1") {

                    if ($respuestaServicioConstrastacion['segundoApellido'] === "0") {
                        $this->dispatch('datoPersonalErroneo', campo: 'SEGUNDO APELLIDO');
                        return;
                    }

                    if ($respuestaServicioConstrastacion['segundoApellido'] === "2") {
                        $this->dispatch('datoPersonalVacio', campo: 'SEGUNDO APELLIDO');
                        return;
                    }
                    //$contrastacionExitosa++;
                }

                if ($respuestaServicioConstrastacion['nombres'] !== "1") {

                    if ($respuestaServicioConstrastacion['nombres'] === "0") {
                        $this->dispatch('datoPersonalErroneo', campo: 'NOMBRES');
                        return;
                    }

                    if ($respuestaServicioConstrastacion['nombres'] === "2") {
                        $this->dispatch('datoPersonalVacio', campo: 'NOMBRES');
                        return;
                    }
                    //$contrastacionExitosa++;
                }

                if ($respuestaServicioConstrastacion['fechaNacimiento'] !== "1") {

                    if ($respuestaServicioConstrastacion['fechaNacimiento'] === "0") {
                        $this->dispatch('datoPersonalErroneo', campo: 'FECHA NACIMIENTO');
                        return;
                    }

                    if ($respuestaServicioConstrastacion['fechaNacimiento'] === "2") {
                        $this->dispatch('datoPersonalVacio', campo: 'FECHA NACIMIENTO');
                        return;
                    }
                    //$contrastacionExitosa++;
                }

                if (trim($this->sexo) !== "F" && trim($this->sexo) !== "M") {
                    $this->dispatch('datoPersonalErroneo', campo: 'SEXO');
                    return;
                }

                /*if ($respuestaServicioConstrastacion['fechaNacimiento'] === "2") {
                    $this->dispatch('datoPersonalVacio', campo: 'FECHA NACIMIENTO');
                    return;
                }*/

                if (!$this->verificarExistenciaRegistroContrastacion($this->idPersona)) {
                    $personaActualizada = Persona::with(['tipoAseguradoPersona'])->find($this->idPersona);

                    $jsonPersonaActualizada = json_encode($personaActualizada->toArray());

                    //$jsonTablaCorresponde = '';


                    if ($personaActualizada) {
                        $complementoTratado = trim(mb_strtoupper($this->complemento)) === '' ? null : trim(mb_strtoupper($this->complemento));
                        $nombresTratado = trim(mb_strtoupper($this->nombres)) === '' ? null : trim(mb_strtoupper($this->nombres));
                        $p_apellidoTratado = trim(mb_strtoupper($this->p_apellido)) === '' ? null : trim(mb_strtoupper($this->p_apellido));
                        $s_apellidoTratado = trim(mb_strtoupper($this->s_apellido)) === '' ? null : trim(mb_strtoupper($this->s_apellido));
                        $carnetAnterior = implode('-', array_filter([$personaActualizada->ci, $personaActualizada->complemento]));
                        $carnetNuevo = implode('-', array_filter([$this->ci, $complementoTratado]));
                        $matriculaNueva = $this->generarMatricula($nombresTratado, $p_apellidoTratado, $s_apellidoTratado, $this->fecha_nacimiento, $this->sexo);

                        $personaActualizada->ci = $this->ci;
                        $personaActualizada->complemento = trim(mb_strtoupper($this->complemento)) === '' ? null : trim(mb_strtoupper($this->complemento));
                        $personaActualizada->nombres = $nombresTratado;
                        $personaActualizada->p_apellido = $p_apellidoTratado;
                        $personaActualizada->s_apellido = $s_apellidoTratado;
                        $personaActualizada->fecha_nacimiento = $this->fecha_nacimiento;
                        $personaActualizada->sexo = $this->sexo;
                        $personaActualizada->clave_unica = implode('', array_filter([
                            $this->ci,
                            $complementoTratado
                        ]));
                        $personaActualizada->matricula_seguro = $matriculaNueva;
                        $personaActualizada->id_user_updated = auth()->user()->id;
                        $personaActualizada->save();

                        $datosContrastadosSegip = [
                            'ciAntiguo' => $carnetAnterior,
                            'ciNuevo' => $carnetNuevo,
                            'nombres' => $nombresTratado,
                            'p_apellido' => $p_apellidoTratado,
                            's_apellido' => $s_apellidoTratado,
                            'fecha_nacimiento' => $this->fecha_nacimiento,
                            'sexo' => $this->sexo,
                            'matricula_seguro' => $matriculaNueva,
                        ];
                        //dd($datosContrastadosSegip);
                        $dato = new ActualizacionDatosSegipS1();
                        $dato->enviarDatosActualizados($datosContrastadosSegip);

                        $contrastacionDatosRegistrados = ContrastacionDatos::create([
                            'id_persona' => $this->idPersona,
                            'id_tipo_asegurado' => $personaActualizada->id_tipo_asegurado,
                            'id_corresponde' => $this->idTablaCorresponde,
                            'datos_personales_old' => $jsonPersonaActualizada,
                            //'datos_referenciales_old' => $jsonTablaCorresponde,
                            'id_user_created' => auth()->user()->id,
                        ]);
                    }
                }

                $this->deshabilitarEspacios = $this->verificarExistenciaRegistroContrastacion($this->idPersona);
                $this->actualizarDatosPersona($this->idPersona);
                $this->dispatch('registroModificado');
            } else {
                $this->deshabilitarEspacios = true;
                $this->errorAsuss = true;
            }
        }
    }

    public function generarMatricula($nombre, $p_apellido, $s_apellido, $fecha_nacimiento, $sexo)
    {
        $nombre = trim($nombre);
        $p_apellido = trim($p_apellido);
        $s_apellido = trim($s_apellido);
        $fecha_nacimiento = Carbon::parse($fecha_nacimiento);
        //dd($nombre, $p_apellido, $s_apellido, $fecha_nacimiento, $sexo);            
        $anioNacimiento = $fecha_nacimiento->format('Y');
        $mesNacimiento = $fecha_nacimiento->format('m');
        $diaNacimiento = $fecha_nacimiento->format('d');
        $sexoEsMasculino = $sexo === false || $sexo === 'M';
        $sexoEsFemenino = $sexo === true || $sexo === 'F';

        if (strlen($p_apellido) == 0) {
            $apellido1 = substr($s_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 1, 1);
        }
        if (strlen($s_apellido) == 0) {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($p_apellido, 1, 1);
        }
        if (strlen($p_apellido) != 0 && strlen($s_apellido) != 0) {
            $apellido1 = substr($p_apellido, 0, 1);
            $apellido2 = substr($s_apellido, 0, 1);
        }
        $nombre1 = substr($nombre, 0, 1);
        $matricula = '';
        $matricula .= substr($anioNacimiento, -2);
        if ($sexoEsMasculino) {
            $matricula .= str_pad($mesNacimiento, 2, '0', STR_PAD_LEFT);
        } elseif ($sexoEsFemenino) {
            $matricula .= str_pad(50 + $mesNacimiento, 2, '0', STR_PAD_LEFT);
        }
        $matricula .= $diaNacimiento;
        $matricula .= $apellido1;
        $matricula .= $apellido2;
        $matricula .= $nombre1;

        return $matricula;
    }

    public function atras()
    {
        $this->resetearDatos();
        $this->tipo_formulario = 1;
    }

    public function atrasConAlerta()
    {
        $this->atras();
        $this->dispatch('registroModificado');
    }

    public function verificarExistenciaRegistroContrastacion($idPersona)
    {
        return ContrastacionDatos::where('id_persona', $idPersona)
            ->whereNot('datos_personales_old', null)
            ->exists();
    }

    public function verificarExistenciaRegistroConstrastacionReferenciales($idPersona)
    {
        $existePreviamente = ContrastacionDatos::where('id_persona', $idPersona)
            ->whereNot('datos_referenciales_old', null)
            ->orderBy('id', 'desc')
            ->first();
        //dd($existePreviamente);
        $respuesta = [
            'existe' => false,
            'fecha' => null,
            'hora' => null,
        ];

        if ($existePreviamente) {
            $respuesta = [
                'existe' => true,
                'fecha' => Carbon::parse($existePreviamente->created_at)->format('d/m/Y'),
                'hora' => Carbon::parse($existePreviamente->created_at)->format('H:i:s'),
            ];
        }

        return $respuesta;
    }

    public function resetearDatos()
    {
        $this->idPersona = null;
        $this->complemento = null;
        $this->ci = null;
        $this->nombres = null;
        $this->p_apellido = null;
        $this->s_apellido = null;
        $this->fecha_nacimiento = null;
        $this->sexo = null;
        $this->matricula_seguro = null;
        $this->telefono = null;
        $this->telefono_referencia = null;
        $this->nombre_referencia = null;
        $this->idTablaCorresponde = null;
        $this->deshabilitarEspacios = false;
        $this->errorAsuss = false;
        $this->referencialesActualizados = false;
        $this->fechaActualizacionDatosReferenciales = null;
        $this->horaActualizacionDatosReferenciales = null;
    }

    public function actualizarSoloDatosReferenciales()
    {
        $personaActualizada = Persona::with(['tipoAseguradoPersona'])->find($this->idPersona);

        $jsonTablaCorresponde = '';

        if ($personaActualizada) {

            if ($personaActualizada->tipoAseguradoPersona->tipo_asegurado === 'TITULAR') {
                $titular = Titular::find($this->idTablaCorresponde);
                if ($titular) {
                    $jsonTablaCorresponde = json_encode($titular->toArray());
                    $titular->telefono = $this->telefono;
                    $titular->telefono_referencia = $this->telefono_referencia;
                    $titular->nombre_referencia = strtoupper($this->nombre_referencia);
                    $titular->id_user_updated = auth()->user()->id;
                    $titular->save();
                }
            }

            if (Str::contains($personaActualizada->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
                if ($personaActualizada->tipoAseguradoPersona->tipo_asegurado === 'BENEFICIARIO DEL INTERIOR') {
                    $autorizacionInterior = AutorizacionInterior::find($this->idTablaCorresponde);
                    if ($autorizacionInterior) {
                        $jsonTablaCorresponde = json_encode($autorizacionInterior->toArray());
                        $autorizacionInterior->telefono = $this->telefono;
                        $autorizacionInterior->telefono_referencia = $this->telefono_referencia;
                        $autorizacionInterior->datos_referencia = strtoupper($this->nombre_referencia);
                        $autorizacionInterior->id_user_updated = auth()->user()->id;
                        $autorizacionInterior->save();
                    }
                } else {
                    $beneficiario = Beneficiario::find($this->idTablaCorresponde);
                    if ($beneficiario) {
                        $jsonTablaCorresponde = json_encode($beneficiario->toArray());
                        $beneficiario->telefono = $this->telefono;
                        $beneficiario->telefono_referencia = $this->telefono_referencia;
                        $beneficiario->nombre_referencia = strtoupper($this->nombre_referencia);
                        $beneficiario->id_user_updated = auth()->user()->id;
                        $beneficiario->save();
                    }
                }
            }

            if ($personaActualizada->tipoAseguradoPersona->tipo_asegurado === 'TITULAR CONVENIO') {
                //por el momento nada
            }

            if ($personaActualizada->tipoAseguradoPersona->tipo_asegurado === 'TITULAR DEL INTERIOR') {
                $autorizacionInterior = AutorizacionInterior::find($this->idTablaCorresponde);
                if ($autorizacionInterior) {
                    $jsonTablaCorresponde = json_encode($autorizacionInterior->toArray());
                    $autorizacionInterior->telefono = $this->telefono;
                    $autorizacionInterior->telefono_referencia = $this->telefono_referencia;
                    $autorizacionInterior->datos_referencia = $this->nombre_referencia;
                    $autorizacionInterior->id_user_updated = auth()->user()->id;
                    $autorizacionInterior->save();
                }
            }

            if ($personaActualizada->tipoAseguradoPersona->tipo_asegurado === 'ESTUDIANTE') {
                $estudiante = Estudiante::find($this->idTablaCorresponde);
                if ($estudiante) {
                    $jsonTablaCorresponde = json_encode($estudiante->toArray());
                    $estudiante->telefono = $this->telefono;
                    $estudiante->telefono_referencia = $this->telefono_referencia;
                    $estudiante->nombre_referencia = strtoupper($this->nombre_referencia);
                    $estudiante->id_user_updated = auth()->user()->id;
                    $estudiante->save();
                }
            }

            $contrastacionDatosRegistrados = ContrastacionDatos::create([
                'id_persona' => $this->idPersona,
                'id_tipo_asegurado' => $personaActualizada->id_tipo_asegurado,
                'id_corresponde' => $this->idTablaCorresponde,
                'datos_referenciales_old' => $jsonTablaCorresponde,
                'id_user_created' => auth()->user()->id,
            ]);

            if ($contrastacionDatosRegistrados) {
                $this->referencialesActualizados = $this->verificarExistenciaRegistroConstrastacionReferenciales($this->idPersona)['existe'];
                $this->fechaActualizacionDatosReferenciales = $this->verificarExistenciaRegistroConstrastacionReferenciales($this->idPersona)['fecha'];
                $this->horaActualizacionDatosReferenciales = $this->verificarExistenciaRegistroConstrastacionReferenciales($this->idPersona)['hora'];
                $this->dispatch('registroDatosReferencialesModificado');
            }
        }
    }
}
