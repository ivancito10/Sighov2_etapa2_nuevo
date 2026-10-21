<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role; 
use App\Models\Administracion\Permission; 
use App\Models\Administracion\Persona; 
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
class RolePermissionsVVDD extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role1 = Role::create(['name' => 'Encargado VVDD']);
        $role2 = Role::create(['name' => 'Secretaria GS - Prestaciones']);
        $role3 = Role::create(['name' => 'Encargado Externo Convenios']);
        $role4 = Role::create(['name' => 'Poblacion Asegurada QR']);
        $role5 = Role::create(['name' => 'Encargado Convenios Emergencias']);

    // instituciones de convenios
        Permission::create([
            'name' => 'menu.institucion_convenio',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.institucion_convenio',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.institucion_convenio',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);
    
        Permission::create([
            'name' => 'ver.institucion_convenio',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'editar.institucion_convenio',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'eliminar.institucion_convenio',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
    //
        // seguros del interior
        Permission::create([
            'name' => 'menu.seguros_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.seguros_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.seguros_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'editar.seguros_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'responsable.seguros_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);


    //
            // prestaciones medicas al interior
        Permission::create([
            'name' => 'menu.prestaciones_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([[$role1,$role2]]);

        Permission::create([
            'name' => 'lista.prestaciones_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([[$role1,$role2]]);

        Permission::create([
            'name' => 'registrar.prestaciones_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([[$role1]]);

        Permission::create([
            'name' => 'ver.prestaciones_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([[$role1,$role2]]);

        Permission::create([
            'name' => 'editar.prestaciones_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'pdf.prestaciones_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role2]);

        Permission::create([
            'name' => 'firma_digital.prestaciones_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role2]);

        Permission::create([
            'name' => 'anular.prestaciones_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);


        // bajas medicas hologacion
        Permission::create([
            'name' => 'menu.baja_medica_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.baja_medica_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.baja_medica_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.baja_medica_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'validar.baja_medica_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'entregar.baja_medica_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'anular.baja_medica_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

    //
        // ordenes de servicio hologacion
        Permission::create([
            'name' => 'menu.ordenes_servicio_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.ordenes_servicio_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.ordenes_servicio_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.ordenes_servicio_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);


        Permission::create([
            'name' => 'entregar.ordenes_servicio_hologacion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

    // convenios insterior
        Permission::create([
            'name' => 'menu.convenios_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.convenios_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.convenios_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.convenios_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);


        Permission::create([
            'name' => 'editar.convenios_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'validar.convenios_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'anular.convenios_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'carnet.convenios_interior',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

    // convenios emergencias
        Permission::create([
            'name' => 'menu.convenios_emergencias',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role5]);

        Permission::create([
            'name' => 'lista.convenios_emergencias',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role5]);

        Permission::create([
            'name' => 'registrar.convenios_emergencias',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role5]);

        Permission::create([
            'name' => 'ver.convenios_emergencias',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role5]);


        Permission::create([
            'name' => 'pdf.convenios_emergencias',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1,$role5);

        Permission::create([
            'name' => 'anular.convenios_emergencias',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1,$role5);

        // poblacion asegurada
        Permission::create([
            'name' => 'menu.poblacion_asegurada',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role4,$role5]);

        // bajas institucionales
        Permission::create([
            'name' => 'menu.bajas_asegurados',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.bajas_asegurados',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);
            
        Permission::create([
            'name' => 'registrar.bajas_asegurados',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.bajas_asegurados',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);
            
        Permission::create([
            'name' => 'editar.bajas_asegurados',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1]);

        // Prestaciones medicas interior
        Permission::create([
            'name' => 'menu.personal_externo_prestaciones_medicas',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role3]);

        Permission::create([
            'name' => 'lista.personal_externo_prestaciones_medicas',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role3]);
            
        Permission::create([
            'name' => 'registrar.personal_externo_prestaciones_medicas',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role3]);

        Permission::create([
            'name' => 'ver.personal_externo_prestaciones_medicas',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role3]);
            
        Permission::create([
            'name' => 'editar.personal_externo_prestaciones_medicas',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role3]);

        Permission::create([
            'name' => 'pdf.personal_externo_prestaciones_medicas',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role3]);
    

    //
    }
}
