<?php

namespace App\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    public $count;

    public function mount()
    {
        // Inicializar el conteo de notificaciones
        $this->count = Notification::where('user_id', auth()->id())->count(); // Ajusta la lógica según tu modelo
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
