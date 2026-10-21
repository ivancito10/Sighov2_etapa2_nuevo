<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role;
use App\Models\Administracion\Permission;
use App\Models\Administracion\Persona;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionsPlataforma extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Encargado Plataforma']);


        // agendamiento plataforma
        Permission::create([
            'name' => 'menu.agenda_plataforma',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.agenda_plataforma',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'agendar.agenda_plataforma',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.agenda_plataforma',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'imprimir_ficha.agenda_plataforma',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'eliminar.agenda_plataforma',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);
        //


        // agenda especialista
        Permission::create([
            'name' => 'menu.agenda_especialista',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.agenda_especialista',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.agenda_especialista',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);
        //

        // administracion especialidades
        Permission::create([
            'name' => 'menu.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'editar.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'asignar_servicio.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'hab_des_servicio.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'deshabilitar.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'habilitar.especialidades',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);
        //

        // administracion consultorios
        Permission::create([
            'name' => 'menu.consultorios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.consultorios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.consultorios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.consultorios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'editar.consultorios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'deshabilitar.consultorios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'habilitar.consultorios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);
        //

        // administracion horarios
        Permission::create([
            'name' => 'menu.horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'editar.horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'deshabilitar.horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);

        Permission::create([
            'name' => 'habilitar.horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles($role1);
        //

        // administracion Anulacion de Fichas
        Permission::create([
            'name' => 'menu.anulacion_fichas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.anulacion_fichas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.anulacion_fichas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.anulacion_fichas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        //

        // administracion especialistas
        Permission::create([
            'name' => 'menu.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'editar.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'asignar_servicio.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'asignar_horario.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver_horario.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'desabilitar.especialistas',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        //

        // administracion suspencion de vacaciones
        Permission::create([
            'name' => 'menu.suspencion_vacaciones',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.suspencion_vacaciones',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.suspencion_vacaciones',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.suspencion_vacaciones',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'desabilitar.suspencion_vacaciones',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        //

        // administracion suspencion de horarios
        Permission::create([
            'name' => 'menu.suspencion_horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'lista.suspencion_horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'registrar.suspencion_horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'ver.suspencion_horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        Permission::create([
            'name' => 'desabilitar.suspencion_horarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1]);

        //
    }
}
