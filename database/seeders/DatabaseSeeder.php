<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(RoleSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(RolePermissionsAportes::class);
        // $this->call(RolePermissionsAfiliacion::class);
        // $this->call(RolePermissionsAsegurados::class);
        // $this->call(RolePermissionsAfiliacionReportes::class);
        // $this->call(RolePermissionsVVDD::class);
        // $this->call(RolePermissionsPlataforma::class);
        //$this->call(RolePermissionsAdmZonas::class);
        $this->call(RoleReimprimirCambiarCredencialesUsuarios::class);

        // $this->call(RolePermissionsAdministrador::class);

        $this->call(RoleFarmacia::class);
        // \App\Models\PersonaAsus::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        //$this->call([InstitucionExternaSeeder::class,]);
        $this->call([RoleEnfermeriaSeeder::class]);
        $this->call(RolePermissionsAlmacenGeneralSeeder::class);
        $this->call([MaterialesSeeder::class]);
    }
}
