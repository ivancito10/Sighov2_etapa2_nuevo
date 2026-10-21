<?php

namespace App\Livewire\Administracion\Usuarios;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Administracion\Role;
use App\Models\Administracion\UserSession;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class UsuariosMain extends Component
{
    public $idUsuario,$name
    ,$email
    ,$datos
    ,$new_password
    ,$fecha_creacion
    ,$usuario_creador
    ,$estado,$roles_asignados=[],
    $lista_roles=[],$nuevos_roles=[],$fecha_registro,$usuario;
    #[locked]
    public $datos_usuario_ver,
    $v_name
    ,$v_email
    ,$v_fecha_registro
    ,$v_usuario_registrador
    ,$v_ci
    ,$v_complemento
    ,$v_fecha_nacimiento,$v_usuario_personar,$v_roles_asignados=[],$fotografia,$v_edad,$sesiones,$sessiones_c,$v_matricula;
    public function render()
    {
        return view('livewire.Administracion.usuarios.usuarios-main');
    }
    //  PARA REDIRECCIONAR LO DEL USUARIO 
    public function redirectToForm()
    { 
        return redirect()->route('administracion.usuarios.create');
    }
    // PARA ESCUCHAR MODALS ----
      #[On('verUsuario')]
      public function verUsuario($data)
      {
          $this->idUsuario= $data;
          $this->datos_usuario_ver=$this->datosUsuario($data);
          $this->v_name=$this->datos_usuario_ver->name;
          $this->v_email=$this->datos_usuario_ver->email;
          $this->v_fecha_registro=$this->datos_usuario_ver->created_at;
          $datos_usuario_registro=User::with('personaUser')->find($this->datos_usuario_ver->id_user_created);
          $this->v_usuario_registrador=$datos_usuario_registro->personaUser->p_apellido.' '.$datos_usuario_registro->personaUser->s_apellido.' '.$datos_usuario_registro->personaUser->nombres;
          $this->v_ci=$this->datos_usuario_ver->personaUser->ci;
          $this->v_complemento=$this->datos_usuario_ver->personaUser->complemento;
          $this->v_fecha_nacimiento=Carbon::parse($this->datos_usuario_ver->personaUser->fecha_nacimiento)->format('d/m/y');
          $this->v_matricula=$this->datos_usuario_ver->personaUser->matricula_seguro;
          $this->v_edad=Carbon::parse($this->datos_usuario_ver->personaUser->fecha_nacimiento)->age;
          $this->v_usuario_personar=$this->datos_usuario_ver->personaUser->p_apellido.' '.$this->datos_usuario_ver->personaUser->s_apellido.' '.$this->datos_usuario_ver->personaUser->nombres;
          $this->fotografia =$this->obtenerImagenBase64($this->datos_usuario_ver->imagen);
          $this->v_roles_asignados=$this->datos_usuario_ver->getRoleNames();
          $this->sesiones=UserSession::where('user_id',$this->idUsuario)->orderby('id','desc')->first();
          $this->sessiones_c=UserSession::where('user_id',$this->idUsuario)->orderby('id','desc')->count();
        //   dd($sessiones_c);
          $this->dispatch('modalVerUsuarios');
      }

      #[On('editarUsuario')]
      public function editarUsuario($data)
      {
          $this->idUsuario= $data;
          $this->datos=$this->datosUsuario($data);
          $this->name=$this->datos->name;
          $this->email=$this->datos->email;
          $this->fecha_creacion=$this->datos->created_at->format('d/m/Y');
          $datos_usuario_registro=User::with('personaUser')->find($this->datos->id_user_created);

          $this->fotografia =$this->obtenerImagenBase64($this->datos->imagen);
          $this->usuario_creador=$datos_usuario_registro->personaUser->p_apellido.' '.$datos_usuario_registro->personaUser->s_apellido.' '.$datos_usuario_registro->personaUser->nombres;
          $this->estado=$this->datos->estado;
          $this->dispatch('modalEditarUsuarios');
      }
      #[On('actualizar')]
      public function actualizar()
      {
            try {
                $usuario = User::find($this->idUsuario);
                if ($usuario) {
                    $usuario->name = $this->name;
                    $usuario->email = $this->email;
                    $usuario->estado = $this->estado;
                    $usuario->id_user_updated = auth()->user()->id;
                    $usuario->save();
                }

            $this->dispatch('EditadoCorrectamente');
            $this->dispatch('pg:eventRefresh-UsuariosTable');
            } catch (\Throwable $th) {
                $this->dispatch('EditadoIncorrectamente');
            }

         

      }
    #[On('verCredencialesPdf')]
      public function verCredencialesPdf($data){
        $this->idUsuario=$data;
        // dd('ya casi bro',$this->idUsuario);
        $this->js('window.open("/Administracion/Usuarios/pdfCredencialesUsuario/' . $this->idUsuario . '", "_blank")');

      }
      
      #[On('cambiarContraseña')]
      public function cambiarContraseña($data)
      {
          $this->idUsuario= $data;
          $this->dispatch('modalCambiarContraseña');
      }
      #[On('resetear')]
      public function resetear(){
        try {
            $this->new_password=$this->generatePassword(9);
        // dd($this->idUsuario,$this->new_password);
        $usuario = User::find($this->idUsuario);
        if ($usuario) {
            $usuario->password = Hash::make($this->new_password);
            $usuario->session_token = null;
            $usuario->id_user_updated = auth()->user()->id;
            $usuario->save();
        }
        // dd($this->idUsuario,$this->new_password);
        $this->dispatch('ContraseñaReseteada');
        $this->js('window.open("/Administracion/usuario/resetPassword/' . $this->idUsuario .'/'. $this->new_password .'", "_blank")');
        } catch (\Throwable $th) {
            $this->dispatch('ContraseñaReseteadaError');
        }
        
        
      }
      public function generatePassword($length)
    {
        $characters = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $password;
    }
      public function datosUsuario($data){
        $datos_usuario=User::with('personaUser')->find($data);
        return $datos_usuario;
      }
      #[On('asignarRol')]
    public function asignarRol($data){
        $this->idUsuario= $data;
        $this->datos=$this->datosUsuario($data);
        $this->name= $this->datos->name;
        $this->email= $this->datos->email;
        $this->fecha_registro= $this->datos->created_at->translatedFormat('d \d\e F \d\e Y - H:i');;
        $datos_usuario_registro=User::with('personaUser')->find($this->datos->id_user_created);
        $this->usuario=$datos_usuario_registro->personaUser->p_apellido.' '.$datos_usuario_registro->personaUser->s_apellido.' '.$datos_usuario_registro->personaUser->nombres;

        $roles_usuario=User::find($data);

        $this->roles_asignados=$roles_usuario->getRoleNames();
        $this->lista_roles=Role::all();
        $this->dispatch('modalAsignarrol');
    }

    #[On('asignarRoles')]
    public function asignarRoles(){
        try {
            $user=User::find($this->idUsuario);
            $user->syncRoles($this->roles_asignados);
            $this->dispatch('pg:eventRefresh-UsuariosTable');
            $this->dispatch('AsignacionRolesCorrectamente');
            } catch (\Throwable $th) {
                $this->dispatch('AsignacionRolesIncorrectamente');
            }   
    }

    private function obtenerImagenBase64($filename)
    {
        try {
            if ($filename) {
                $path = 'Administracion/Usuarios/Perfil/' . $filename;
                $imageData = Storage::disk('ftp')->get($path);
                
                $fotito = base64_encode($imageData);
                return $fotito;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    #[On('cambiarEstado')]
    public function cambiarEstado($data)
    {
        try{
            $esp = User::find($data);
            if ($esp) {
                $estadoActual = $esp->estado;

                $esp->estado = !$estadoActual;
                $esp->save();

                $this->dispatch('pg:eventRefresh-UsuariosTable');
                if ($esp->estado == true) {
                    $this->dispatch('activo');
                } else {
                    $this->dispatch('inactivo');
                }
            }

        
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
        
    }
   
   
}
