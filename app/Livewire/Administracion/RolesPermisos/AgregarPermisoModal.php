<?php

namespace App\Livewire\Administracion\RolesPermisos;

use Livewire\Component;
use App\Models\Administracion\Permission;
use Livewire\Attributes\On;

class AgregarPermisoModal extends Component
{
    public $name;
    public $guard_name='web';
    public $alert = '';



    public function render()
    {
        return view('livewire.Administracion.roles-permisos.agregar-permiso-modal');
    }
    #[On('crearpermiso')]
     public function crearpermiso()
    {
        try{
            // Verifica si los campos obligatorios están llenos
            $this->validate([
                'name' => 'required',
                'guard_name' => 'required',
            ]);
            $existeRol=Permission::where('name','=',$this->name)->exists();
            if ($existeRol==null) {
                # code...
                $claseRolesPermisos =Permission::create([
                    'name' => $this->name,
                    'guard_name' => $this->guard_name,
                    'permissions_enabled'=> true,
                    // 'id_user_created' => auth()->user()->id,
                    // 'id_user_updated' => auth()->user()->id,
                ]);
                if ($claseRolesPermisos) {
                    $this->dispatch('CreadaRegisterPermiso');
                } 
            } else {
                # code...
                $this->reset(['name']);
                $this->dispatch('PermisoExiste');
            }
        $this->reset(['name']);
            // $this->dispatch('crearRol');
            // $this->dispatch('alert');
         }
         catch (\Throwable $th) {
            $this->reset(['name'/* , 'guard_name' */]);
            $this->dispatch('CanceladoRegisterPermiso');            

       }
    }  
}
