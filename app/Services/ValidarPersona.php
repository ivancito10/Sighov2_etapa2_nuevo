<?php

namespace App\Services;

use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Http;
use App\Models\Administracion\AccesoAsussToken;
use App\Services\ConexionAsuss;
use App\Models\Administracion\LinkServicio;
use Carbon\Carbon;

class ValidarPersona
{
    public $datosPersona;

    public function buscarPersona($ciPersona, $complementoPersona)
    {
        try {
            $parametros = [
                'ci' => $ciPersona,
                'complemento' => $complementoPersona,
            ];

            $link_servicio = LinkServicio::where('nombre_servicio', 'CONSULTAR DATOS SEGIP')->first();
            $asuss_url = $link_servicio->link_servicio;

            $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
            //dd($tokenBd);
            if ($tokenBd) {
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->post($asuss_url, $parametros);
                $this->datosPersona = $response->json();
                //dd($this->datosPersona);

                //dd(isset($this->datosPersona['error']));
                if (isset($this->datosPersona['error'])) {
                    $logeo = new ConexionAsuss();
                    $logueado = $logeo->login();
                    $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                    $response = Http::withToken($tokenBd->token)
                        ->withoutVerifying()
                        ->post($asuss_url, $parametros);
                    $this->datosPersona = $response->json();

                    if (isset($this->datosPersona['codigoRespuesta'])) {
                        if ($this->datosPersona['codigoRespuesta'] == 0) {
                            //dd($this->datosPersona);
                            return $this->datosPersona; // se introdujeron datos erroneos == 0
                        } elseif ($this->datosPersona['codigoRespuesta'] == 1) {
                            return $this->datosPersona; // el registro no existe == 1
                        } elseif ($this->datosPersona['codigoRespuesta'] == 2) {


                            if ($this->datosPersona['datosPersonaEnFormatoJson']['complemento'] == ''  || $this->datosPersona['datosPersonaEnFormatoJson']['complemento'] == 'NULL') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['complemento'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['Complemento'] = null;
                            }
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['primerApellido'] == '--') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['primerApellido'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'] = null;
                            }
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['segundoApellido'] == '--') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['segundoApellido'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'] = null;
                            }
                            $this->datosPersona['datosPersonaEnFormatoJson']['fechaNacimiento'] = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['fechaNacimiento'])->format('Y-m-d');
                            $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'] = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'])->format('Y-m-d');
                            //dd($this->datosPersona);
                            return $this->datosPersona; // el registro existe se devuelve a la persona == 2
                        } elseif ($this->datosPersona['codigoRespuesta'] == 3) {
                            return $this->datosPersona; // el registro devuelve mas de un valor == 3
                        } else {
                            return $this->datosPersona; // registro con observacion fallecido = 4
                        }
                    } else {
                        //dd($this->datosPersona);
                        $this->datosPersona = ['codigoRespuesta' => 5,];
                        return $this->datosPersona;
                    }
                } else {
                    if (isset($this->datosPersona['codigoRespuesta'])) {
                        if ($this->datosPersona['codigoRespuesta'] == 0) {
                            //dd($this->datosPersona);
                            return $this->datosPersona; // se introdujeron datos erroneos == 0
                        } elseif ($this->datosPersona['codigoRespuesta'] == 1) {
                            return $this->datosPersona; // el registro no existe == 1
                        } elseif ($this->datosPersona['codigoRespuesta'] == 2) {


                            if ($this->datosPersona['datosPersonaEnFormatoJson']['complemento'] == ''  || $this->datosPersona['datosPersonaEnFormatoJson']['complemento'] == 'NULL') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['complemento'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['Complemento'] = null;
                            }
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['primerApellido'] == '--') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['primerApellido'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'] = null;
                            }
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['segundoApellido'] == '--') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['segundoApellido'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'] = null;
                            }
                            $this->datosPersona['datosPersonaEnFormatoJson']['fechaNacimiento'] = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['fechaNacimiento'])->format('Y-m-d');
                            $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'] = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'])->format('Y-m-d');
                            //dd($this->datosPersona);
                            return $this->datosPersona; // el registro existe se devuelve a la persona == 2
                        } elseif ($this->datosPersona['codigoRespuesta'] == 3) {
                            return $this->datosPersona; // el registro devuelve mas de un valor == 3
                        } else {
                            return $this->datosPersona; // registro con observacion fallecido = 4
                        }
                    } else {
                        $this->datosPersona = ['codigoRespuesta' => 5,];
                        return $this->datosPersona;
                    }
                    // No se Introdujeron datos
                }
            } else {
                $logeo = new ConexionAsuss();
                $logueado = $logeo->login();
                $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                $response = Http::withToken($tokenBd->token)
                    ->withoutVerifying()
                    ->post($asuss_url, $parametros);
                $this->datosPersona = $response->json();
                if (isset($this->datosPersona['error'])) {
                    $logeo = new ConexionAsuss();
                    $logueado = $logeo->login();
                    $tokenBd = AccesoAsussToken::orderby('id', 'desc')->first();
                    $response = Http::withToken($tokenBd->token)
                        ->withoutVerifying()
                        ->post($asuss_url, $parametros);
                    $this->datosPersona = $response->json();
                    //dd($this->datosPersona['codigoRespuesta']);
                    if (isset($this->datosPersona['codigoRespuesta'])) {
                        if ($this->datosPersona['codigoRespuesta'] == 0) {
                            //dd($this->datosPersona);
                            return $this->datosPersona; // se introdujeron datos erroneos == 0
                        } elseif ($this->datosPersona['codigoRespuesta'] == 1) {
                            return $this->datosPersona; // el registro no existe == 1
                        } elseif ($this->datosPersona['codigoRespuesta'] == 2) {


                            if ($this->datosPersona['datosPersonaEnFormatoJson']['complemento'] == '' || $this->datosPersona['datosPersonaEnFormatoJson']['complemento'] == 'NULL') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['complemento'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['Complemento'] = null;
                            }
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['primerApellido'] == '--') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['primerApellido'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'] = null;
                            }
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['segundoApellido'] == '--') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['segundoApellido'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'] = null;
                            }
                            $this->datosPersona['datosPersonaEnFormatoJson']['fechaNacimiento'] = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['fechaNacimiento'])->format('Y-m-d');
                            $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'] = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'])->format('Y-m-d');
                            //dd($this->datosPersona);
                            return $this->datosPersona; // el registro existe se devuelve a la persona == 2
                        } elseif ($this->datosPersona['codigoRespuesta'] == 3) {
                            return $this->datosPersona; // el registro devuelve mas de un valor == 3
                        } else {
                            return $this->datosPersona; // registro con observacion fallecido = 4
                        }
                    } else {
                        $this->datosPersona = ['codigoRespuesta' => 5,];
                        return $this->datosPersona;
                    }
                } else {
                    if (isset($this->datosPersona['codigoRespuesta'])) {
                        if ($this->datosPersona['codigoRespuesta'] == 0) {
                            //dd($this->datosPersona);
                            return $this->datosPersona; // se introdujeron datos erroneos == 0
                        } elseif ($this->datosPersona['codigoRespuesta'] == 1) {
                            return $this->datosPersona; // el registro no existe == 1
                        } elseif ($this->datosPersona['codigoRespuesta'] == 2) {
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['complemento'] == '' || $this->datosPersona['datosPersonaEnFormatoJson']['complemento'] == 'NULL') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['complemento'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['Complemento'] = null;
                            }
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['primerApellido'] == '--') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['primerApellido'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'] = null;
                            }
                            if ($this->datosPersona['datosPersonaEnFormatoJson']['segundoApellido'] == '--') {
                                $this->datosPersona['datosPersonaEnFormatoJson']['segundoApellido'] = null;
                                $this->datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'] = null;
                            }
                            $this->datosPersona['datosPersonaEnFormatoJson']['fechaNacimiento'] = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['fechaNacimiento'])->format('Y-m-d');
                            $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'] = Carbon::createFromFormat('d/m/Y', $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'])->format('Y-m-d');
                            //dd($this->datosPersona);

                            return $this->datosPersona; // el registro existe se devuelve a la persona == 2
                        } elseif ($this->datosPersona['codigoRespuesta'] == 3) {
                            return $this->datosPersona; // el registro devuelve mas de un valor == 3
                        } else {
                            return $this->datosPersona; // registro con observacion fallecido = 4
                        }
                    } else {
                        $this->datosPersona = ['codigoRespuesta' => 5,];
                        return $this->datosPersona;
                    }
                }
            }
        } catch (\Throwable $th) {
            $this->datosPersona = ['codigoRespuesta' => 5,];
            return $this->datosPersona;
        }

        /*$this->datosPersona = ['codigoRespuesta' => 5,];
        return $this->datosPersona;*/
    }
}
