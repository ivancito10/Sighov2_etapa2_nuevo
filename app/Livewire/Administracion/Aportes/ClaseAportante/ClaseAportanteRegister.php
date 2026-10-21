<?php

namespace App\Livewire\Administracion\Aportes\ClaseAportante;

use Livewire\Component;
use App\Models\Aportes\ClaseAportante;
class ClaseAportanteRegister extends Component
{
        
    public $nombre;
    public $tipo='';
    public $porcentaje;
    public $alert = '';
    protected $listeners = ['crearClaseAportante'];

    public function render()
    {
        return view('livewire.Administracion.aportes.clase-aportante.clase-aportante-register');
    }
    
    public function crearClaseAportante()
    {
        try{
            // Verifica si los campos obligatorios están llenos
            $this->validate([
                'nombre' => 'required',
                'tipo' => 'required',
                'porcentaje' => 'required',
            ]);

            // Crea un nuevo registro de ClaseAportante
            $claseAportante =ClaseAportante::create([
                'nombre' => $this->nombre,
                'tipo' => $this->tipo,
                'porcentaje' => $this->porcentaje,
                'id_user_created' => auth()->user()->id,
                'id_user_updated' => auth()->user()->id,
            ]);
            if ($claseAportante) {
                $this->dispatch('CreadaRegisterCA');
            } 
            $this->reset(['nombre', 'tipo','porcentaje']);
            $this->dispatch('crearClase');
            $this->dispatch('alert');
         }
         catch (\Throwable $th) {
            $this->reset(['nombre', 'tipo','porcentaje']);
            $this->dispatch('CanceladoRegisterCA');            

       }
    }    
}
