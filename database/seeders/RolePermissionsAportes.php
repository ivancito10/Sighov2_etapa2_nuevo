<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role; // Usa el modelo personalizado
use App\Models\Administracion\Permission; // Usa el modelo personalizado

class RolePermissionsAportes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role1 = Role::create(['name' => 'Encargado Aportes']);
        $role2 = Role::create(['name' => 'Encargado Planillas Externas']);
    //ufv
        Permission::create([
            'name' => 'menu.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'lista.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
    
        Permission::create([
            'name' => 'registro.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'editar.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'habilitar_desabilitar.ufv',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
    //
    //Tasa de Interes
        Permission::create([
            'name' => 'menu.tasa_interes',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'lista.tasa_interes',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'registro.tasa_interes',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'editar.tasa_interes',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'habilitar_desabilitar.tasa_interes',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

            //Instituciones Aportes
        Permission::create([
            'name' => 'menu.instituciones',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'lista.instituciones',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'registro.instituciones',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'editar.instituciones',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'habilitar_desabilitar.instituciones',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'editar_representante.instituciones',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'ver.instituciones',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'validar.instituciones',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        //Aportes Institucionales 
        Permission::create([
            'name' => 'menu.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'lista.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'subir_planilla.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'ver_aportantes.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'eliminar_planilla.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'validar_planilla.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'forma_pago.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'preliquidacion.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'liquidacion.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'comprobante_pdf.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'comprobante_pdf_copia.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'registro_baja_medica.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'reporte_baja_medica.aportes_institucionales',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

            //Aportantes Voluntarios
        Permission::create([
            'name' => 'menu.aportantes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'lista.aportantes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'registrar.aportantes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'editar.aportantes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'ver.aportantes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'habilitar_desabilitar.aportantes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        //Aportes Voluntarios
        Permission::create([
            'name' => 'menu.aportes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'lista.aportes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'registrar.aportes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'ver.aportes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'comprobante_pdf.aportes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'comprobante_pdf_copia.aportes_voluntarios',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

       
        //Liquidaciones asuss
        Permission::create([
            'name' => 'menu.liquidaciones_asuss',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'lista.liquidaciones_asuss',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'registrar.liquidaciones_asuss',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'liquidacion_pdf.liquidaciones_asuss',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

      //Liquidaciones Ministerio de salud
      Permission::create([
        'name' => 'menu.liquidaciones_ministerio',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles($role1);

    Permission::create([
        'name' => 'lista.liquidaciones_ministerio',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles($role1);

    Permission::create([
        'name' => 'registrar.liquidaciones_ministerio',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles($role1);

    Permission::create([
        'name' => 'liquidacion_pdf.liquidaciones_ministerio',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles($role1);

        
      //Extractos Persona
      Permission::create([
        'name' => 'menu.extracto_persona',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles($role1);

    Permission::create([
        'name' => 'lista.extracto_persona',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles($role1);

    Permission::create([
        'name' => 'extracto.extracto_persona',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles($role1);

         //Extractos insitucion
         Permission::create([
            'name' => 'menu.extracto_institucion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
    
        Permission::create([
            'name' => 'lista.extracto_institucion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);
    
        Permission::create([
            'name' => 'extracto.extracto_institucion',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

            //Instituciones externas
            Permission::create([
                'name' => 'menu.instituciones_externas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'lista.instituciones_externas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'registro.instituciones_externas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'editar.instituciones_externas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'editar_representante.instituciones_externas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'ver.instituciones_externas',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);

              //aportes externos
              Permission::create([
                'name' => 'menu.aportes_externos',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'lista.aportes_externos',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'registro.aportes_externos',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'preliquidacion.aportes_externos',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
            Permission::create([
                'name' => 'ver_pdf_copia.aportes_externos',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
            Permission::create([
                'name' => 'ver.aportes_externos',
                'guard_name'=>'web',
                'permissions_enabled'=>true
                ])->syncRoles([$role2]);
    
          
    
    }
}
