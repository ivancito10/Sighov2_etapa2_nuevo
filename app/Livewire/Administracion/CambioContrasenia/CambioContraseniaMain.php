<?php

namespace App\Livewire\Administracion\CambioContrasenia;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CambioContraseniaMain extends Component
{
    public $current_password;
    public $new_password;
    public $confirm_password;
    public $password_strength_message = '';

        
    public function render()
    {
        return view('livewire.Administracion.cambio-contrasenia.cambio-contrasenia-main');
    }

    // Método que se llama cada vez que el campo new_password se actualiza
    public function updatedNewPassword($value)
    {
        $this->password_strength_message = $this->checkPasswordStrength($value);
    }

    // Función que revisa la seguridad de la contraseña
    public function checkPasswordStrength($password)
    {
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

        //if (!preg_match('/[\W_]/', $password)) {
        //    $recommendations[] = "Incluye al menos un carácter especial (como !, @, #, etc.).";
        //}

        return count($recommendations) > 0 ? implode(' ', $recommendations) : 'Contraseña segura.';
    }

    // Método para actualizar la contraseña
    public function updatePassword()
    {
        // Validar los datos
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ], [
            'confirm_password.same' => 'El campo "Contraseña nueva" debe coincidir con "Confirmar nueva contraseña".',
            'new_password' => 'El campo "Contraseña nueva" es obligatorio.',
            'current_password' => 'El campo "Contraseña actual" es obligatorio.',
        ]);

        // Validar si la nueva contraseña cumple con los requisitos
        if (!$this->validatePasswordStrength($this->new_password)) {
            $this->addError('new_password', 'La contraseña no cumple con los requisitos de seguridad.');
            return;
        }

        // Obtener el usuario autenticado
        $user = Auth::user(); // Esto asume que estás usando la autenticación estándar de Laravel

        // Verificar si la contraseña actual coincide
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'La contraseña actual no es correcta.');
            return;
        }

        // Actualizar la contraseña
        $user->password = Hash::make($this->new_password);
        $user->save();

        // Reiniciar los campos del formulario
        $this->reset(['current_password', 'new_password', 'confirm_password','password_strength_message']);

        // Disparar el evento 'cambiado' en el navegador
        $recommendations[] = '';
        $this->dispatch('cambiado');
        //$password_strength_message = '';
    }
    public function validatePasswordStrength($password)
    {
        if (
            strlen($password) >= 7 && // Mínimo 7 caracteres
            preg_match('/[A-Z]/', $password) && // Al menos una letra mayúscula
            preg_match('/[a-z]/', $password) && // Al menos una letra minúscula
            preg_match('/\d/', $password)  // Al menos un número
            //preg_match('/[\W_]/', $password) // Al menos un carácter especial
        ) {
            return true;
        }
        return false;
    }   

    #[On('logoutNow')]
    public function logoutNow()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return $this->redirectRoute('login');
    }

}
