<?php

namespace App\Livewire\Administracion\Usuarios;

use Livewire\Component;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Municipio;
use App\Services\ValidarPersona;
use App\Services\ConexionAsuss;
use App\Models\Administracion\Role; // Usa el modelo personalizado
use App\Models\Administracion\Permission; // Usa el modelo personalizado
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class UsuariosRegister extends Component
{
    use WithFileUploads;
    public $ci_b, $complemento_b;
    //#[locked]
    public $departamento, $provincias = [], $municipios = [], $id_persona, $validacion_usuario = 1;
    #[Validate]
    public $ci, $complemento, $tipo_doc, $expedido_ci, $nombre, $ap_paterno, $ap_materno, $fecha_nacimiento, $dep_nacimiento, $pro_nacimiento, $mun_nacimiento,
        $sexo = false, $encontrado, $matricula, $roles, $roles_select = [], $fecha_nacimiento_des, $foto;

    public function rules()
    {
        return [

            'tipo_doc' => 'required',
            // 'expedido_ci' => 'required',
            'nombre' => 'required',
            'dep_nacimiento' => 'required',
            'pro_nacimiento' => 'required',
            'mun_nacimiento' => 'required',
            'matricula' => 'required',
        ];
    }
    public function ObtenerProvincia()
    {
        $this->provincias = Provincia::where('id_departamento', '=', $this->dep_nacimiento)->get();
    }
    public function ObtenerMunicipio()
    {
        $this->municipios = Municipio::where('id_provincia', '=', $this->pro_nacimiento)->get();
    }
    public function render()
    {
        $this->departamento = Departamento::get();
        $this->roles = Role::get();
        // dd($this->roles);
        return view('livewire.Administracion.usuarios.usuarios-register');
    }
    #[On('registrarUsuario')]
    public function registrarUsuario()
    {
        $validated = $this->validate();
        if ((Carbon::parse($this->fecha_nacimiento)->age) > 20 and (Carbon::parse($this->fecha_nacimiento)->age) < 90) {
            try {
                //code...
                if ($this->sexo == true) {
                    $this->sexo = 'F';
                } else {
                    $this->sexo = 'M';
                }
                if ($this->id_persona == 0) {
                    if ($this->encontrado === 2) {
                        $estado_asuss = 1;
                    } else {
                        $estado_asuss = 0;
                    }
                    $verificarPersona = Persona::where('ci', $this->ci)->where('complemento', $this->complemento)->first();
                    if ($verificarPersona == null) {
                        if ($this->complemento == null) {
                            $clave_unica = $this->ci;
                        } else {
                            $clave_unica = $this->ci . $this->complemento;
                        }

                        $persona = Persona::create([
                            'ci' => $this->ci,
                            'complemento' => $this->complemento,
                            'nombres' => $this->nombre,
                            'p_apellido' => $this->ap_paterno,
                            's_apellido' => $this->ap_materno,
                            'fecha_nacimiento' => $this->fecha_nacimiento,
                            'estado_asuss' => $estado_asuss,
                            'sexo' => $this->sexo,
                            'id_nacimiento_municipio' => $this->mun_nacimiento,
                            'afiliado' => false,
                            'id_tipo_asegurado' => 0,
                            'es_extranjero' => $this->tipo_doc,
                            'matricula_seguro' => $this->matricula,
                            'id_dept_exp' => $this->expedido_ci,
                            'clave_unica' => $clave_unica,
                            'id_user_created' => auth()->user()->id,
                        ]);
                        $this->id_persona = $persona->id;
                    } else {
                        $this->id_persona = $verificarPersona->id;
                    }
                }

                $usuario = User::where('id_persona', $this->id_persona)->count();
                $persona_encontrada = Persona::find($this->id_persona);
                //  dd($persona_encontrada);
                //
                $this->usuario = $this->GenerarUsuario($persona_encontrada);
                //   dd($this->usuario,$usuario);
                if ($usuario == 0) {
                    $numeroInvertido = (int)strrev((string)$this->id_persona);
                    $resultado = $numeroInvertido + 3;
                    $user = User::create([
                        'name' => $this->nombre . ' ' . $this->ap_paterno . ' ' . $this->ap_materno,
                        'email' => $this->usuario . '-ext-' . $resultado,
                        'id_persona' => $this->id_persona,
                        'id_usu_create' => $this->id_persona,
                        'id_user_created' => auth()->user()->id,
                        'estado' => true,
                        'password' => Hash::make($this->ci),
                    ]);
                } else {
                    $user = User::where('id_persona', $this->id_persona)->first();
                }
                $nombre_foto = $user->id . '- U.jpg';
                $user->update([
                    'imagen' => $nombre_foto
                ]);
                if (!is_null($this->foto)) {
                    $imagePath = $this->foto->getRealPath();
                    $imageData = file_get_contents($imagePath);
                    $this->foto->storeAs('Administracion/Usuarios/Perfil', $nombre_foto, 'ftp');
                }

                foreach ($this->roles_select as $roles) {
                    $user->assignRole($roles);
                }
                $this->js('window.open("/Administracion/usuario/nuevo/' . $user->id . '", "_blank")');
                return redirect()->route('administracion.usuarios.index')->with('evento', 'UsuarioCreado');
            } catch (\Throwable $th) {
                //throw $th;
                return redirect()->route('administracion.usuarios.index')->with('evento', 'UsuarioCreadoError');
            }
        } else {
            $this->dispatch('error_fecha_nacimiento');
        }
    }
    public function GenerarUsuario($persona_encontrada)
    {

        // Convertir a mayúsculas para asegurar consistencia
        $nombres = str_replace(' ', '', mb_strtolower($persona_encontrada->nombres));
        $p_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->p_apellido));
        $s_apellido = str_replace(' ', '', mb_strtolower($persona_encontrada->s_apellido));

        // Inicializar el email
        $email = '';

        if (!empty($p_apellido) == true && !empty($s_apellido) == true) {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($s_apellido, 0, 1);
        }

        if (!empty($p_apellido) == false && !empty($s_apellido) == true) {
            $email = substr($nombres, 0, 1) . $s_apellido . substr($s_apellido, 0, 1);
        }

        if (!empty($p_apellido) == true && !empty($s_apellido) == false) {
            $email = substr($nombres, 0, 1) . $p_apellido . substr($p_apellido, 0, 1);
        }

        return $email;
    }
    public function cancel()
    {
        return redirect()->route('administracion.usuarios.index')->with('evento', 'UsuarioCreadoCancelado');
    }
    public function BuscarPersona()
    {

        if (is_null($this->ci_b)) {
            $this->dispatch('sin_ci');
        } else {
            if ($this->complemento_b == '') {
                $this->complemento_b = null;
            }

            $verificarPersona = Persona::where('ci', $this->ci_b)->where('complemento', $this->complemento_b)->first();
            //  dd($verificarPersona,$this->sexo);
            if ($verificarPersona) {
                $this->id_persona = $verificarPersona->id;
                $this->ci = $verificarPersona->ci;
                $this->complemento = $verificarPersona->complemento;
                $this->nombre = $verificarPersona->nombres;
                $this->ap_paterno = $verificarPersona->p_apellido;
                $this->ap_materno = $verificarPersona->s_apellido;
                $this->fecha_nacimiento = $verificarPersona->fecha_nacimiento;
                $this->fecha_nacimiento_des = Carbon::parse($verificarPersona->fecha_nacimiento)->translatedFormat('d \d\e F \d\e Y');
                $this->encontrado = 1;
                $verificar_usuario = User::where('id_persona', $this->id_persona)->first();
                if ($verificar_usuario) {
                    $this->dispatch('ErrorYaExisteUsuario');
                    $this->validacion_usuario = 1;
                } else {
                    $this->validacion_usuario = 0;
                }
            } else {
                $validacionAsuss = new ValidarPersona();
                $this->datosPersona = $validacionAsuss->buscarPersona($this->ci_b, $this->complemento_b);
                // dd($this->datosPersona);
                //dd($this->datosPersona);
                if (isset($this->datosPersona['datosPersonaEnFormatoJson'])) {
                    $this->nombre = $this->datosPersona['datosPersonaEnFormatoJson']['Nombres'];
                    $this->ap_paterno = $this->datosPersona['datosPersonaEnFormatoJson']['PrimerApellido'];
                    $this->ap_materno = $this->datosPersona['datosPersonaEnFormatoJson']['SegundoApellido'];
                    $this->fecha_nacimiento = $this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'];
                    $this->fecha_nacimiento_des = Carbon::parse($this->datosPersona['datosPersonaEnFormatoJson']['FechaNacimiento'])->translatedFormat('d \d\e F \d\e Y');
                    $this->complemento = $this->datosPersona['datosPersonaEnFormatoJson']['complemento'];
                    $this->ci = $this->datosPersona['datosPersonaEnFormatoJson']['numeroDocumento'];
                    $this->estado_asuss = 1;
                    $this->estadoBusqueda = 2;
                    $this->encontrado = 2;
                    $this->id_persona = 0;
                } else {
                    $this->ci = $this->ci_b;
                    $this->complemento = $this->complemento_b;
                    $this->encontrado = 0;
                    $this->id_persona = 0;
                    $this->reset('nombre', 'ap_paterno', 'ap_materno', 'fecha_nacimiento');
                }

                $this->validacion_usuario = 0;
            }
        }
    }

    public function GenerarMatricula()
    {
        $nombre       = $this->nombre ?? '';
        $p_apellido   = $this->ap_paterno ?? '';
        $s_apellido   = $this->ap_materno ?? '';
        $fecha_nac    = $this->fecha_nacimiento ?? null;
        $sexo         = $this->sexo;

        if (!$fecha_nac) {
            $this->matricula = '';
            return;
        }

        // Ajusta el formato si tu input viene como 30/10/1993:
        // $fecha = Carbon::createFromFormat('d/m/Y', $fecha_nac);
        $fecha = Carbon::parse($fecha_nac);

        $yy = $fecha->format('y');
        $mm = (int)$fecha->format('m');
        $dd = $fecha->format('d');

        // Normalizar (ñ, tildes, espacios, símbolos)
        $ap  = $this->sanitizeName($p_apellido);
        $am  = $this->sanitizeName($s_apellido);
        $nom = $this->sanitizeName($nombre);

        // Iniciales de apellidos según disponibilidad
        $apellido1 = '';
        $apellido2 = '';

        if ($ap === '' && $am !== '') {
            $apellido1 = mb_substr($am, 0, 1, 'UTF-8');
            $apellido2 = mb_substr($am, 1, 1, 'UTF-8');
        } elseif ($am === '' && $ap !== '') {
            $apellido1 = mb_substr($ap, 0, 1, 'UTF-8');
            $apellido2 = mb_substr($ap, 1, 1, 'UTF-8');
        } else {
            $apellido1 = mb_substr($ap, 0, 1, 'UTF-8');
            $apellido2 = mb_substr($am, 0, 1, 'UTF-8');
        }

        $nombre1 = mb_substr($nom, 0, 1, 'UTF-8');

        // Mes codificado por sexo
        $mesCod = ($sexo === true || $sexo === 'femenino') ? (50 + $mm) : $mm;
        $mesCod = str_pad((string)$mesCod, 2, '0', STR_PAD_LEFT);

        $this->matricula = $yy . $mesCod . $dd . $apellido1 . $apellido2 . $nombre1;
    }

    private function sanitizeName(string $str): string
    {
        // ñ→n, á→a, etc.; mayúsculas; deja sólo A–Z
        $str = Str::ascii($str);
        $str = Str::upper($str);
        $str = preg_replace('/[^A-Z]/u', '', $str);
        return $str ?? '';
    }

    public function cancelarUsuario()
    {
        return redirect()->route('administracion.usuarios.index');
    }
}
