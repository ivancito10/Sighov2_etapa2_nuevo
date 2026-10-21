<?php

namespace Database\Seeders;

use App\Models\Administracion\Permission;
use App\Models\Administracion\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionsAlmacenGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // 1. Definición de roles y sus respectivos permisos
        $rolesWithPermissions = [
            'Encargado de Almacen' => [
                // Menu
                'menu.materiales_almacen_general',
                'menu.proveedores_almacen_general',
                'menu.ingresos_almacen_general',
                'menu.solicitudes_almacen_general',
                'menu.cierre_gestion_almacen_general',
                'menu.stock_almacen_general',
                'menu.reingresos_almacen_general',

                // Materiales
                'registrar.materiales_almacen_general',
                'ver.materiales_almacen_general',
                'editar.materiales_almacen_general',
                'deshabilitar.materiales_almacen_general',
                'habilitar.materiales_almacen_general',
                'lista.materiales_almacen_general',

                // Proveedores
                'registrar.proveedores_almacen_general',
                'ver.proveedores_almacen_general',
                'habilitar.proveedores_almacen_general',
                'editar.proveedores_almacen_general',
                'deshabilitar.proveedores_almacen_general',
                'lista.proveedores_almacen_general',

                // Ingresos
                'registrar.ingresos_almacen_general',
                'ver.ingresos_almacen_general',
                'editar.ingresos_almacen_general',
                'validar.ingresos_almacen_general',
                'facturas.ingresos_almacen_general',
                'pdf.ingresos_almacen_general',
                'lista.ingresos_almacen_general',

                // Solicitudes
                'ver.solicitudes_unidades_almacen_general',
                'editar.solicitudes_unidades_almacen_general',
                'pdf.solicitudes_unidades_almacen_general',
                'aprobar.solicitudes_unidades_almacen_general',
                'anular.solicitudes_unidades_almacen_general',
                'lista.solicitudes_unidades_almacen_general',
                'entregar.solicitudes_unidades_almacen_general',

                // Cierre de gestión
                'ver.cierre_gestion_almacen_general',
                'editar.cierre_gestion_almacen_general',
                'reporte.cierre_gestion_almacen_general',
                'excel.cierre_gestion_almacen_general',
                'registrar.cierre_gestion_almacen_general',
                'añadir_precio.cierre_gestion_almacen_general',
                'lista.cierre_gestion_almacen_general',
                'lista_materiales.cierre_gestion_almacen_general',

                // Stock
                'ver.stock_almacen_general',
                'bindcard.stock_almacen_general',
                'lista.stock.almacen_general',

                // Reingresos
                'ver.reingresos_almacen_general',
                'pdf.reingresos_almacen_general',
                'registrar.reingresos_almacen_general',
                'lista.reingresos_almacen_general',
            ],

            'Solicitudes Almacen General Unidades' => [
                //Menu
                'menu.solicitudes_materiales_almacen_general',

                //Solicitudes unidades
                'ver.solicitudes_materiales_almacen_general',
                'editar.solicitudes_materiales_almacen_general',
                'revisar.solicitudes_materiales_almacen_general',
                'anular.solicitudes_materiales_almacen_general',
                'pdf.solicitudes_materiales_almacen_general',
                'registrar.solicitudes_materiales_almacen_general',
                'lista.solicitudes_materiales_almacen_general',
            ],

            'Encargado Bienes Servicio' => [
                //Menu
                'menu.proveedores_almacen_general',
                'menu.solicitudes_pendientes_almacen_general',

                // Proveedores
                'registrar.proveedores_almacen_general',
                'ver.proveedores_almacen_general',
                'habilitar.proveedores_almacen_general',
                'editar.proveedores_almacen_general',
                'deshabilitar.proveedores_almacen_general',
                'lista.proveedores_almacen_general',

                // Solicitudes pendientes               
                'ver.solicitudes_pendientes_almacen_general',
                'aprobar.solicitudes_pendientes_almacen_general',
                'anular.solicitudes_pendientes_almacen_general',
                'lista.solicitudes_pendientes_almacen_general',
            ],
        ];

        // 2. Iterar sobre los roles y crear/asignar permisos
        foreach ($rolesWithPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);

                if (!$role->hasPermissionTo($permissionName)) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
