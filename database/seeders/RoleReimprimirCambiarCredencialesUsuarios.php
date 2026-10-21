<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role; 
use App\Models\Administracion\Permission; 
class RoleReimprimirCambiarCredencialesUsuarios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
                $role1 = Role::create(['name' => 'Reimprimir y Cambiar Credenciales Usuario']);
                $role2 = Role::create(['name'=> 'Administracion Usuarios']);

        // menú usuarios
        Permission::create([
            'name' => 'menu.usuarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1,$role2]);

        // registrar usuario
        Permission::create([
            'name' => 'admin.registro_usuario',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role2]);

        // lista usuarios
        Permission::create([
            'name' => 'admin.lista_usuarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1,$role2]);

        // ver usuario
        Permission::create([
            'name' => 'admin.ver_usuario',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role2]);

        // editar usuario
        Permission::create([
            'name' => 'admin.editar_usuario',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role2]);

        // cambiar contraseña usuario
        Permission::create([
            'name' => 'admin.cambiar_contraseña_usuario',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1,$role2]);

        // asignar roles
        Permission::create([
            'name' => 'admin.asignar_rol_usuarios',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role2]);

                // asignar roles
        Permission::create([
            'name' => 'admin.pdf_credenciales',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role1,$role2]);

        Permission::create([
            'name' => 'admin.deshabilitar_usuario',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role2]);

        Permission::create([
            'name' => 'admin.habilitar_usuario',
            'guard_name' => 'web',
            'permissions_enabled' => true
        ])->syncRoles([$role2]);


        
    }
}
