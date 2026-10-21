<?php

namespace App\Livewire\Administracion\Dashboard\BarraDerecha;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class BarraDerechaMain extends Component
{
    public function render()
    {
        return view('livewire.Administracion.dashboard.barra-derecha.barra-derecha-main');
    }

    public function actualizarCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('schedule:run');
        $output = Artisan::output();
        $this->dispatch('mostrarSalida', message: $output);
    }

  
    
}
