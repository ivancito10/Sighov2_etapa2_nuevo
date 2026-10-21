<?php

namespace Database\Seeders;

use App\Models\Administracion\Permission;
use App\Models\Administracion\Role;
use Illuminate\Database\Seeder;

class RoleConsultaExterna extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role1 = Role::create(['name' => 'Med. Consulta Externa']);
        $role2 = Role::create(['name' => 'Med. Emergencias']);
        $role3 = Role::create(['name' => 'Med. CCR']);
        $role4 = Role::create(['name' => 'Med. Hospitalización']);
        $role5 = Role::create(['name' => 'Med. Terapia Intensiva']);
        $role6 = Role::create(['name' => 'Med. Quirófano']);
        $role7 = Role::create(['name' => 'Med. Atención Externa']);
        $role8 = Role::create(['name' => 'Med. Hemodialisis']);
        $role9 = Role::create(['name' => 'Dirección del Hospital']);
        $role10 = Role::create(['name' => 'Resultados Imagenología']);
        $role11 = Role::create(['name' => 'Registro de Placas Imagenología']);
        $rolBioestadistica = Role::where('name', 'BIOESTADISTICA')->first();

        // CONSULTA EXTERNA
        Permission::create([
            'name' => 'menu.consulta_externa',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.consulta_externa',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ficha_extra.consulta_externa',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'reporte_atencion_medica.consulta_externa',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver_agendas_anteriores.agenda_ecografia_etapa2',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'atender.consulta_externa',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'agenda_anticipada.consulta_externa',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'cuadernos.consulta_externa',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'falta.consulta_externa',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1);

        // Emergencias
        Permission::create([
            'name' => 'menu.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role2, $role3, $role4, $role5, $role6, $role7, $role8]);

        Permission::create([
            'name' => 'lista.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role2, $role3, $role4, $role5, $role6, $role7, $role8]);

        Permission::create([
            'name' => 'nueva_atencion.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role2, $role3, $role4, $role5, $role6, $role7, $role8]);

        Permission::create([
            'name' => 'ver.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role2, $role3, $role4, $role5, $role6, $role7, $role8]);

        Permission::create([
            'name' => 'emergencias.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role2]);

        Permission::create([
            'name' => 'ccr.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role3]);

        Permission::create([
            'name' => 'hospitalizacion.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role4]);

        Permission::create([
            'name' => 'terapia_intensiva.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role5]);

        Permission::create([
            'name' => 'quirofano.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role6]);

        Permission::create([
            'name' => 'atencion_externa.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role7]);

        Permission::create([
            'name' => 'hemodialisis.otras_consultas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role8]);

        //epidemiologia
        Permission::create([
            'name' => 'menu.epidemiologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role3, $role4, $role5, $role6, $role7, $role8]);

        Permission::create([
            'name' => 'ver_fichas.epidemiologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role3, $role4, $role5, $role6, $role7, $role8]);

        //bajas medicas
        Permission::create([
            'name' => 'menu.bajas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role9]);

        Permission::create([
            'name' => 'lista.bajas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role9]);

        Permission::create([
            'name' => 'ver.bajas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role9]);

        Permission::create([
            'name' => 'anular.bajas_medicas',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role9]);

        //variables
        Permission::create([
            'name' => 'menu.variables',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        Permission::create([
            'name' => 'lista.variables',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        Permission::create([
            'name' => 'ver.variables',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        Permission::create([
            'name' => 'editar.variables',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        Permission::create([
            'name' => 'habilitar_deshabilitar.variables',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        // ADMINISTRACION DE VARIABLES CON ESPECIALIDADES
        Permission::create([
            'name' => 'menu.variables_especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        Permission::create([
            'name' => 'lista.variables_especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        Permission::create([
            'name' => 'ver.variables_especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        Permission::create([
            'name' => 'editar.variables_especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$rolBioestadistica]);

        // ADMINISTRACION REGISTRO DE RESULTADOS DE RX
        Permission::create([
            'name' => 'menu.resultados_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role10]);

        Permission::create([
            'name' => 'lista.resultados_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role10]);

        Permission::create([
            'name' => 'ver.resultados_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role10]);

        Permission::create([
            'name' => 'registrar.resultados_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role10]);

        // ADMINISTRACION DE PLACAS IMAGENOLGIA
        Permission::create([
            'name' => 'menu.placas_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role11]);

        Permission::create([
            'name' => 'lista.placas_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role11]);

        Permission::create([
            'name' => 'ver.placas_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role11]);

        Permission::create([
            'name' => 'registrar.placas_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role11]);

        Permission::create([
            'name' => 'archivo.placas_imagenologia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role11]);
    }
}
