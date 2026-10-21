<?php

namespace App\Livewire\Administracion\Usuarios;

use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\Titular;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;

class UsuariosPerfilEdit extends Component
{   
    use WithFileUploads;

    public $user,$name,$email,$ci,$matricula,$imagen,$correo,$tipo_asegurado;
    private $titulares = [1, 9, 8];
    private $beneficiarios = [2, 3, 4, 5, 6, 7, 10, 12, 13, 14];
    
    protected $rules = [
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048' // Solo imágenes, máximo 2MB
    ];
    
    public function updatedImagen()
    {  
        if (!$this->imagen) {
            return;
        }
        
        // Obtener la extensión del archivo subido
        $extension = $this->imagen->getClientOriginalExtension();
    
        // Lista de extensiones permitidas
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $extensionesPermitidas)) {
            $this->imagen = null;
            $this->dispatch('imagenInvalida');
        }
        
    }

    public function mount()
    {
        $this->mostrarPerfil();
    }

    #[On('actualizarPerfil')]
    public function actualizarPerfil()
    {   
        $user = Auth::user();

        //     try {
        //     if($this->correo){
        //         $tipo_asegurado1 = $user->personaUser->id_tipo_asegurado;
        //         if(in_array($tipo_asegurado1,$this->titulares)){
        //             $titular = Titular::where('id_persona', $this->user->personaUser->id)->first();
        //             $titular->correo = $this->correo;
        //             $titular->save();
        //         }elseif(in_array($tipo_asegurado1,$this->beneficiarios)){
        //             $beneficiario = Beneficiario::where('id_persona',$this->user->personaUser->id)->first();
        //             $beneficiario->correo = $this->correo;
        //             $beneficiario->save();
        //         }elseif($tipo_asegurado1 == 11){
        //             $estudiante = Estudiante::where('id_persona',$this->user->personaUser->id)->first();
        //             $estudiante->correo_institucional = $this->correo;
        //             $estudiante->save();
        //         }
        //     }

        //     // Si el usuario ha subido una nueva imagen, procesarla
        //     if ($this->imagen) {
        //         $this->guardarFotoPerfil($user);
        //     }

        //     return redirect()->route('home')->with('success', 'Perfil actualizado correctamente.');

        // } catch (\Exception $e) {
        //     return redirect()->route('home')->with('error', 'Hubo un problema al actualizar el perfil: ' . $e->getMessage());
        // }
    }

    public function guardarFotoPerfil($user)
    {
        try {
            // Nombre de la nueva imagen
            $nombre_foto = $user->id . '-U.jpg';
            $rutaImagenAnterior = "Administracion/Usuarios/Perfil/{$user->imagen}";

            if (!empty($user->imagen) && Storage::disk('ftp')->exists($rutaImagenAnterior)) {
                // Eliminar la imagen anterior antes de subir la nueva
                Storage::disk('ftp')->delete($rutaImagenAnterior);
            }

            $this->imagen->storeAs('Administracion/Usuarios/Perfil', $nombre_foto, 'ftp');

            $user->update([
                'imagen' => $nombre_foto
            ]);

        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Error al actualizar la foto: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.Administracion.usuarios.usuarios-perfil-edit');
    }

    public function mostrarPerfil(){

        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->ci = $this->user->personaUser->ci ?? '';
        $this->matricula = $this->user->personaUser->matricula_seguro ?? '';
        $tipo_asegurado1 = $this->user->personaUser->id_tipo_asegurado;
        if (!is_null($tipo_asegurado1)) { 
            if(in_array($tipo_asegurado1,$this->titulares)){
                $link = LinkServicio::where('nombre_servicio','DATOS TITULAR')->pluck('link_servicio')->first();
                $token = session('access_token', '');
                //dd($link); 
                $response = Http::withToken($token)->get($link.'/'.$this->user->personaUser->id);
                //dd($response->json());
                if($response){
                    $titularArray = $response->json()['data'];
                    $titular = json_decode(json_encode($titularArray));
                    $this->correo = $titular->correo ?? 'SIN DATOS';
                    $this->tipo_asegurado = 1;
                }
            }elseif(in_array($tipo_asegurado1,$this->beneficiarios)){
                $link = LinkServicio::where('nombre_servicio','DATOS BENEFICIARIO')->pluck('link_servicio')->first();
                $token = session('access_token', '');
                $response = Http::withToken($token)->get($link.'/'.$this->user->personaUser->id);
                if($response){
                    $beneficiarioArray = $response->json()['data'];
                    $beneficiario = json_decode(json_encode($beneficiarioArray));
                    $this->correo = $beneficiario->correo;
                    $this->tipo_asegurado = 2;
                }
                
            }elseif($tipo_asegurado1 == 11){
                $link = LinkServicio::where('nombre_servicio','DATOS ESTUDIANTE')->pluck('link_servicio')->first();
                $token = session('access_token', '');
                $response = Http::withToken($token)->get($link.'/'.$this->user->personaUser->id);
                if($response){
                    $estudianteArray = $response->json()['data'];
                    $estudiante = json_decode(json_encode($estudianteArray));
                    $this->correo = $estudiante->correo_institucional;
                    $this->tipo_asegurado = 3;
                }
                
            }elseif($tipo_asegurado1 == 0){
                $this->tipo_asegurado = 0;
                //dd('El asegurado no esta afiliado');
            }
        }

    }
    #[On('cancelarPerfil')]
    public function cancelarPerfil()
    {
        return redirect()->route('dashboard');
    }

}
