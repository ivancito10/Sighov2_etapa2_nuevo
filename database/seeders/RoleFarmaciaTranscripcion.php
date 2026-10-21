<?php

namespace Database\Seeders;

use App\Models\Administracion\Permission;
use App\Models\Administracion\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleFarmaciaTranscripcion extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roleRegente = Role::where('name', 'Regente de Farmacia')->first();
        $roleFarmaceutica = Role::where('name', 'Farmaceutica')->first();

        // $role1 = Role::create(['name' => 'Solicitudes Farmacia Unidades']);
        // $role2 = Role::create(['name' => 'Aprobacion Solicitudes Farmacia']);
        $role3 = Role::create(['name' => 'Transcriptora de medicamentos Farmacia']);

        // FARMACIA RECETAS MEDICAS
        Permission::create([
            'name' => 'menu.recetas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$roleRegente, $roleFarmaceutica, $role3]);

        Permission::create([
            'name' => 'lista.recetas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$roleRegente, $roleFarmaceutica, $role3]);

        Permission::create([
            'name' => 'registrar.recetas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$roleRegente, $roleFarmaceutica, $role3]);


        Permission::create([
            'name' => 'ver.recetas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$roleRegente, $roleFarmaceutica, $role3]);


        Permission::create([
            'name' => 'despachar.recetas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$roleRegente, $roleFarmaceutica, $role3]);


        Permission::create([
            'name' => 'anular.recetas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$roleRegente, $roleFarmaceutica, $role3]);

        Permission::create([
            'name' => 'habilitar.recetas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$roleRegente, $roleFarmaceutica, $role3]);
        

    }
}
