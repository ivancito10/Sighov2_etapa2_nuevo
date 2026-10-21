<?php

namespace App\Livewire\Administracion\RolesPermisos;

use App\Models\Administracion\User;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Administracion\Role;
use App\Models\Administracion\Permission;
use App\Models\Administracion\RoleHasPermission;

class RolesPermisosMain extends Component
{
    #[Locked]
    public $rol_id , $permiso_id, $name, $p_id;
    public $id_rol, $id_per;
    public $lista_permisos=[];
    public $permiso_asignado = [];
    public $mensaje = 'aquiiii';
    protected $listeners = ['guardarPermisos', 'addPermiso', 'quitPermiso', 'saveAddPermiso'];


    

    public function render()
    {
        $this->permiso_asignado = RoleHasPermission::where('role_id', $this->rol_id)->pluck('permission_id')->toArray();
        
        $datosRol =$this->mostrarRol($this->rol_id);
        return view('livewire.Administracion.roles-permisos.roles-permisos-main', ['datosRol'=> $datosRol]);
    }

    public function mount(){
        $this->permiso_asignado = RoleHasPermission::where('role_id', $this->rol_id)->pluck('permission_id')->toArray();
        
    }
    /* public function getPermisoAsignadoProperty()
    {
        return RoleHasPermission::where('role_id', $this->rol_id)->pluck('permission_id')->toArray();
    } */

    public function getPermisoAsignadoProperty(): array
    {
        // Fetch assigned permission IDs for the current role
        $assignedPermissionIds = RoleHasPermission::where('role_id', $this->rol_id)
                                                ->pluck('permission_id')
                                                ->toArray();

        return $assignedPermissionIds;
    }
    

    public function getRol()
    {
        return Role::find($this->rol_id);
    }

    public function mostrarRol($rol_id){
        if($rol_id){
            $rol = Role::find($rol_id);
            $this->name = $rol->name;

            //$this->permiso_asignado = RoleHasPermission::find($rol_id);

            return $rol;
        }
    }

    public function permisosAsignados($rol_id){
        
        $this->permisos = Permission::all();

        $permisosRol = RoleHasPermission::where('role_id', $idRol)->get();
        foreach ($permisosRol as $key => $value) {
            array_push($this->permisosPrueba, $value['permission_id']);
        }
        dump($this->permisosPrueba);



        $rol_permiso = RoleHasPermission::find($rol_id);

        
    }

    #[On('addPermiso')]
    public function addPermiso($rowId){
        
        $this->permiso_id = $rowId;
        $existeRolPermiso = RoleHasPermission::where('role_id', $this->rol_id)
        ->where('permission_id', $this->permiso_id)
        ->first();

        if($existeRolPermiso!=null){
            $rol_perId = $existeRolPermiso->id;
        }
        else
        {
            $role = Role::where('id', $this->rol_id)->first();
            if ($role) {
                $role->givePermissionTo($this->permiso_id);
            }
        }
        $this->dispatch('asignar');
        $this->skipRender();
    }

    #[On('quitPermiso')]
    public function quitPermiso($rowId){
        $this->permiso_id = $rowId;
        RoleHasPermission::where('role_id', $this->rol_id)
        ->where('permission_id', $this->permiso_id)
        ->delete();
        //return dd('borrado');
        $this->dispatch('quitar');
        $this->skipRender();
     
    }

    #[On('rutamodalPermisoLista')]
    public function rutamodalPermisoLista($data)
    {
        $this->rol_id = $data;
        $this->permissions = Permission::all();
        $this->dispatch('modalPermisoLista');
    }


    public function verSelecionado()
    {
        dd($this->permisosSelecionado);
    }

    // para ASGINAR ROL 
    #[On('verPermisos')]
    public function verPermisos($data){
        $this->rol_id = $data;
        $role = Role::find($this->rol_id);
        if( $role){
            $this->lista_permisos = $role->permissions;
        }
        
       // $this->lista_permisos=Permission::all();
        //dd($this->lista_permisos);
        $this->dispatch('asignarPermisosModal');
    }





























    public $saludo = "hola bro";
    public $usuarios;
    public $permissions = [];
    public $permisosSelecionado = [];
    public $permisosPrueba = [];
    public $alert = '';

    public function assignPermission($data)
    {
        $permissionId = $data;
        $roleId = $this->rowId;
        $role = Role::find($roleId);
        if ($role) {
            $role->givePermissionTo($permissionId);
        }
    }

    public function asignarPermiso($idPermisos)
    {
        array_push($this->permisosSelecionado, $idPermisos);
        $this->skipRender();
    }

    public function verPermiso($idRol)
    {
        $permisosRol = RoleHasPermission::where('role_id', $idRol)->get();
        foreach ($permisosRol as $key => $value) {
            array_push($this->permisosPrueba, $value['permission_id']);
        }
        dd($this->permisosPrueba);
    }

    public function guardarPermisos()
    {
        try {
            foreach ($this->permisosSelecionado as $idPermiso) {
                $role = Role::where('id', $this->rowId)->first();
                if ($role) {
                    $role->givePermissionTo($idPermiso);
                }
            }
            $this->dispatch('CreadaAsignarPermiso');
        } catch (\Throwable $th) {
            $this->dispatch('CanceladoAsignarPermiso');
        } finally {
            $this->permisosSelecionado = [];
        }
    }



    #[On('delete')] 
    public function refreshPost($rowId)
    {
        $this->rowId = $rowId;
        $this->dispatch('confirmacioneliminarRol', id_rol: $this->rowId);
    }

    #[On('EliminarRol')] 
    public function eliminarRol()
    {
        $role = Role::find($this->rowId);

        if ($role) {
            $role->delete();
            $this->dispatch('pg:eventRefresh-AsignacionRoles');
            $this->dispatch('roleDeleted');
        } else {
            $this->dispatch('roleNotDeleted');
        }
    }

    #[On('openPermissionsModal')] 
    public function openPermissionsModal($rowId)
    {
        $this->rowId = $rowId;
        $this->rol_id =$rowId;
        $this->permissions = Permission::all();
        $this->dispatch('open-permissions-modal');
    }

    
}
