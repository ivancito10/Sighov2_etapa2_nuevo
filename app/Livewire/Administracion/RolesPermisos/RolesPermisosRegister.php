<?php

namespace App\Livewire\Administracion\RolesPermisos;

use Livewire\Component;
use App\Models\Administracion\Role;
class RolesPermisosRegister extends Component
{
    public $nombre;
    public $email;
    public $name;
    public $guard_name='web';
    public $alert = '';
    protected $listeners = ['save'];

    public function render()
    {
        return view('livewire.Administracion.roles-permisos.roles-permisos-register');
    }
    public function save()
    {
       
        // dd([$this->name,$this->guard_name]);
        try{
            // Verifica si los campos obligatorios están llenos
            $this->validate([
                'name' => 'required',
                'guard_name' => 'required',
            ]);
            // Crea un nuevo registro 
            $existeRol=Role::where('name','=',$this->name)->exists();
            // dd($existeRol);
            if ($existeRol==null) {
                # code...
                $claseRolesPermisos =Role::create([
                    'name' => $this->name,
                    'guard_name' => $this->guard_name,
                    'role_enabled'=> true,
                    // 'id_user_created' => auth()->user()->id,
                    // 'id_user_updated' => auth()->user()->id,
                ]);
                
                if ($claseRolesPermisos) {
                    $this->dispatch('CreadaRegisterRoles');
                }
            } else {
                # code...
                $this->reset(['name']);
                $this->dispatch('RolExiste');
            }
        $this->reset(['name']);
            // $this->dispatch('crearRol');
            // $this->dispatch('alert');
         }
         catch (\Throwable $th) {
            $this->reset(['name'/* , 'guard_name' */]);
            $this->dispatch('CanceladoRegisterRoles');            

       }
    }    
}
