<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role; 
use App\Models\Administracion\Permission; 
use App\Models\Administracion\Persona; 
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
class RolePermissionsAsegurados extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role1 = Role::create(['name' => 'Asegurado']);
        Permission::create([
            'name' => 'menu.certificado_afiliacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

                // agendamiento web
        Permission::create([
            'name' => 'menu.agenda_web',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'datos.agenda_web',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);
    
    //
        // Permission::create([
        //     'name' => 'lista.ufv',
        //     'guard_name'=>'web',
        //     'permissions_enabled'=>true
        //     ])->syncRoles($role1);
    
        // Permission::create([
        //     'name' => 'registro.ufv',
        //     'guard_name'=>'web',
        //     'permissions_enabled'=>true
        //     ])->syncRoles($role1);

        // Permission::create([
        //     'name' => 'editar.ufv',
        //     'guard_name'=>'web',
        //     'permissions_enabled'=>true
        //     ])->syncRoles($role1);

        // Permission::create([
        //     'name' => 'habilitar_desabilitar.ufv',
        //     'guard_name'=>'web',
        //     'permissions_enabled'=>true
        //     ])->syncRoles($role1);
    }
}
