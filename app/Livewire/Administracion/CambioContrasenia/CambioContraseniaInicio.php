<?php

namespace App\Livewire\Administracion\CambioContrasenia;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CambioContraseniaInicio extends Component
{
    public $password;
    public $password_confirmation;
    public $password_strength_message = ''; // Mostrar mensaje después de guardar

    public function mount()
    {
        if (Auth::user() && is_null(Auth::user()->primer_inicio_sesion)) {
            $this->dispatch('verModalCambioContrasenia');
        }
    }

    public function save()
    {
        try {
            $this->password_strength_message = $this->checkPasswordStrength($this->password);
            //dd($this->password);
            if($this->password_strength_message !== 'Contraseña segura.'){
                //dd('dentra',$this->password_strength_message);
                $this->dispatch('errorContrasenia', ['message' => $this->password_strength_message]);
            }
            else{
            // Actualizar la contraseña del usuario
            //dd('no dentra :(',$this->password_strength_message);
            if($this->password == $this->password_confirmation){
                $user = Auth::user();
                $user->password = Hash::make($this->password);
                $user->primer_inicio_sesion = now();
                $user->save();

                $this->dispatch('password-updated'); // Evento de éxito
            }else{
                $this->dispatch('errorContrasenia', ['message' => 'El campo nueva contraseña y Confirmar contraseña no coinciden']);
            }          
            }
        } catch (\Exception $e) {
            $this->dispatch('errorContrasenia'); // Evento de error
        }
        $this->skipRender();
    }

    public function checkPasswordStrength($password)
    {
        if (empty($password)) {
            return '';
        }

        $recommendations = [];

        if (strlen($password) < 8) {
            $recommendations[] = "Usa al menos 8 caracteres.";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $recommendations[] = "Incluye al menos una letra mayúscula.";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $recommendations[] = "Incluye al menos una letra minúscula.";
        }

        if (!preg_match('/\d/', $password)) {
            $recommendations[] = "Agrega al menos un número.";
        }

        return count($recommendations) > 0 ? implode(' ', $recommendations) : 'Contraseña segura.';
    }

    #[On('logoutNow')]
    public function logoutNow()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return $this->redirectRoute('login');
    }

    public function render()
    {
        return view('livewire.Administracion.cambio-contrasenia.cambio-contrasenia-inicio');
    }
}
