<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role; // Usa el modelo personalizado
use App\Models\Administracion\Permission; // Usa el modelo personalizado
class RolePermissionsAdministrador extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $role1 = Role::create(['name' => 'Administrador']);
        //zonas
            Permission::create([
                'name' => 'menu.zonas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles($role1);
    
            Permission::create([
                'name' => 'lista.zonas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles($role1);
        
            Permission::create([
                'name' => 'registro.zonas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles($role1);
    
            Permission::create([
                'name' => 'editar.zonas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles($role1);
    
            Permission::create([
                'name' => 'habilitar_desabilitar.zonas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles($role1);

    }
}
