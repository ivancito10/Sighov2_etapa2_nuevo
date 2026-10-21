<?php

namespace Database\Seeders;

use App\Models\Administracion\Permission;
use App\Models\Administracion\Role;
use Illuminate\Database\Seeder;

class RoleFarmacia extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role1 = Role::create(['name' => 'Regente de Farmacia']);
        $role2 = Role::create(['name' => 'Encargado de Almacen de Farmacia']);
        $role3 = Role::create(['name' => 'Farmaceutica']);
        $role4 = Role::create(['name' => 'Auxiliar de Almacen de Farmacia']);
        $role5 = Role::create(['name' => 'Solicitudes Farmacia Unidades']);
        $role6 = Role::create(['name' => 'Aprobacion Solicitudes Farmacia']);
        $role7 = Role::create(['name' => 'Inventariador Cierre de Gestion']);

        // FARMACIA SOLICITUDES A ALMACEN DE FARMACIA
        Permission::create([
            'name' => 'menu.solicitud_almacen',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'lista.solicitud_almacen',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'registrar.solicitud_almacen',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'ver.solicitud_almacen',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'pdf.solicitud_almacen',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'editar.solicitud_almacen',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role3);

        Permission::create([
            'name' => 'anular.solicitud_almacen',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role3);

        // FARMACIA SOLICITUDES PENDIENTES A UNIDADES
        Permission::create([
            'name' => 'menu.farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'lista.farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'ver.farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'pdf.farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'entregar.farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role3);

        Permission::create([
            'name' => 'anular.farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role3);

        // FARMACIA CIERRE DE GESTION
        Permission::create([
            'name' => 'menu.farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role7]);

        Permission::create([
            'name' => 'lista.farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role7]);

        Permission::create([
            'name' => 'registrar.farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role7]);

        Permission::create([
            'name' => 'ver.farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role7]);

        Permission::create([
            'name' => 'editar.farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role7]);

        // FARMACIA BIN CARD
        Permission::create([
            'name' => 'menu.farmacia_bind_card',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'pdf.farmacia_bind_card',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'excel.farmacia_bind_card',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        // FARMACIA STOCK ACTUALIZADO
        Permission::create([
            'name' => 'menu.farmacia_stock',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'lista.farmacia_stock',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        Permission::create([
            'name' => 'ver.farmacia_stock',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3]);

        // ALMACEN DE FARMACIA ADMINISTRACION DE MEDICAMENTOS
        Permission::create([
            'name' => 'menu.medicamentos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'lista.medicamentos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'registrar.medicamentos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'ver.medicamentos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'editar.medicamentos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'habilitar.medicamentos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'deshabilitar.medicamentos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'ver_cuentas.medicamentos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        // ALMACEN DE FARMACIA ADMINISTRACION DE PROVEEDORES
        Permission::create([
            'name' => 'menu.proveedores',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'lista.proveedores',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'registrar.proveedores',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'ver.proveedores',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'editar.proveedores',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'habilitar.proveedores',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'deshabilitar.proveedores',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        // ALMACEN DE FARMACIA ADMINISTRACION DE INGRESO DE INSUMOS Y MEDICAMENTOS
        Permission::create([
            'name' => 'menu.ingresos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'lista.ingresos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'registrar.ingresos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'ver.ingresos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'editar.ingresos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'habilitar.ingresos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'deshabilitar.ingresos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'pdf.ingresos',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        // ALMACEN DE FARMACIA ENTREGA DE SOLICITUDES DE UNIDADES
        Permission::create([
            'name' => 'menu.almacen_farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'lista.almacen_farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'registrar.almacen_farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'ver.almacen_farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'editar.almacen_farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2]);

        Permission::create([
            'name' => 'habilitar.almacen_farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'deshabilitar.almacen_farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        Permission::create([
            'name' => 'pdf.almacen_farmacia_solicitudes_pendientes_unidades',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2);

        // ALMACEN DE FARMACIA ENTREGA DE SOLICITUDES A FARMACIA
        Permission::create([
            'name' => 'menu.almacen_farmacia_solicitudes_pendientes_farmacia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'lista.almacen_farmacia_solicitudes_pendientes_farmacia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'entregar.almacen_farmacia_solicitudes_pendientes_farmacia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'ver.almacen_farmacia_solicitudes_pendientes_farmacia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'anular.almacen_farmacia_solicitudes_pendientes_farmacia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'pdf.almacen_farmacia_solicitudes_pendientes_farmacia',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles($role1, $role2, $role4);

        // ALMACEN FARMACIA CIERRE DE GESTION
        Permission::create([
            'name' => 'menu.almacen_farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role7]);

        Permission::create([
            'name' => 'lista.almacen_farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role7]);

        Permission::create([
            'name' => 'registrar.almacen_farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role7]);

        Permission::create([
            'name' => 'ver.almacen_farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role7]);

        Permission::create([
            'name' => 'editar.almacen_farmacia_cierre_gestion',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role7]);

        // ALMACEN FARMACIA BIN CARD
        Permission::create([
            'name' => 'menu.almacen_farmacia_bind_card',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'pdf.almacen_farmacia_bind_card',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'excel.almacen_farmacia_bind_card',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        // ALMACEN FARMACIA STOCK ACTUALIZADO
        Permission::create([
            'name' => 'menu.almacen_farmacia_stock',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'lista.almacen_farmacia_stock',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'ver.almacen_farmacia_stock',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        // SOLICITUDES UNIDADES
        Permission::create([
            'name' => 'menu.solicitudes_unidades_farmacias',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'lista.solicitudes_unidades_farmacias',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'registrar.solicitudes_unidades_farmacias',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'ver.solicitudes_unidades_farmacias',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'editar.solicitudes_unidades_farmacias',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'pdf.solicitudes_unidades_farmacias',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'anular.solicitudes_unidades_farmacias',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        // SOLICITUDES FARMACIAS PENDIENTES UNIDADES
        Permission::create([
            'name' => 'menu.solicitudes_unidades_farmacias_gs',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role2, $role4]);

        Permission::create([
            'name' => 'lista.solicitudes_unidades_farmacias_gs',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'registrar.solicitudes_unidades_farmacias_gs',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'ver.solicitudes_unidades_farmacias_gs',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'editar.solicitudes_unidades_farmacias_gs',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'pdf.solicitudes_unidades_farmacias_gs',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

        Permission::create([
            'name' => 'anular.solicitudes_unidades_farmacias_gs',
            'guard_name' => 'web',
            'permissions_enabled' => true,
        ])->syncRoles([$role1, $role3, $role4]);

    }
}
