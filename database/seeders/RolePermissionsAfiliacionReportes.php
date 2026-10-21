<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role; 
use App\Models\Administracion\Permission; 
use App\Models\Administracion\Persona; 

class RolePermissionsAfiliacionReportes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $role1 = Role::create(['name' => 'Reportes Afiliacion']);


    // agendamiento plataforma
        Permission::create([
            'name' => 'menu.afiliado_historial',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.afiliado_historial',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.afiliado_historial',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);
    
        Permission::create([
            'name' => 'pdf.afiliado_historial',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ficha_afiliacion.afiliado_historial',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'grupo_familiar.afiliado_historial',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
//REPORTES
        Permission::create([
            'name' => 'reporte.afiliado_titular',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'reporte.afiliado_beneficiario',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'reporte.afiliado_estudiante',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
    


            
    //
    }
}
