<?php

namespace Database\Seeders;

use App\Models\Administracion\Permission;
use App\Models\Administracion\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleEnfermeriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roleEnfermeria = Role::firstOrCreate([
            'name' => 'Enfermeria',
            'guard_name' => 'web'
        ]);


        $permisosEnfermeria = [
            'menu.enfermeria',
            'registrar.enfermeria',
            'lista.agenda-enfermeria',
        ];


        foreach ($permisosEnfermeria as $nombrePermiso) {
            $permiso = Permission::firstOrCreate(
                [
                    'name' => $nombrePermiso,
                    'guard_name' => 'web'
                ],
                [
                    'permissions_enabled' => true
                ]
            );
            $permiso->syncRoles([$roleEnfermeria]);
        }
    }
}
