<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role; // Usa el modelo personalizado
use App\Models\Administracion\Permission; // Usa el modelo personalizado
class RolePermissionsEncargadaSegurosInspeccionEmpresas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        //
        $role1 = Role::create(['name' => 'Jefe de Seguros']);
        $role2 = Role::create(['name' => 'Encargado de Inspeccion de Empresas']);
    //ufv
        Permission::create([
            'name' => 'menu.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'lista.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
    
        Permission::create([
            'name' => 'registro.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'editar.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'habilitar_desabilitar.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
    //
    }
}
