<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administracion\Role; 
use App\Models\Administracion\Permission; 
use App\Models\Administracion\Persona; 
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
class RolePermissionsAfiliacion extends Seeder
{

    public function run(): void
    {
        //
        $persona = Persona::create([
            'ci' => 10911813,
            'nombres' => 'YURI FRANK',
            'p_apellido' => 'ANARA',
            's_apellido' => 'MICHUA',
            'fecha_nacimiento' => '1993-10-30',
            'estado_asuss' => True,
            'sexo' => 'M',
            'afiliado' => false,
            'id_tipo_asegurado' => 0,
            'es_extranjero' => False,
            'matricula_seguro' => '931030AMY',
            'id_dept_exp' => 1,
            'clave_unica' => 10911813,
        ]);
        $user = User::create([
            'name' => $persona->nombres.' '.$persona->p_apellido.' '.$persona->s_apellido,
            'email' => 'yanaram@ssulapaz.org',
            'id_persona' => $persona->id,
            'id_user_created' => 1,
            'estado' =>true,
            'password' => Hash::make($persona->ci),
        ]);

        $role1 = Role::create(['name' => 'Encargado Afiliacion']);
        $role2 = Role::create(['name' => 'Auxiliar Afiliacion']);
        $role3 = Role::create(['name' => 'Auxiliar Afiliacion SSUE']);
        $role4 = Role::create(['name' => 'Estudiante SSUE']);
        $role5 = Role::create(['name' => 'Contabilidad']);
        $role6 = Role::create(['name' => 'Asegurado Afiliado']);
        $role7 = Role::create(['name' => 'Super Admin']);
        
        $user->assignRole('Super Admin');
    // codigo  institucional
        Permission::create([
            'name' => 'menu.codigo_institucional',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role2]);

        Permission::create([
            'name' => 'lista.codigo_institucional',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role2]);
    
        Permission::create([
            'name' => 'ver.codigo_institucional',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles([$role1,$role2]);

        Permission::create([
            'name' => 'asignar.codigo_institucional',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

        Permission::create([
            'name' => 'eliminar.codigo_institucional',
            'guard_name'=>'web',
            'permissions_enabled'=>true
            ])->syncRoles($role1);

    
    //
    // titulares
    Permission::create([
        'name' => 'menu.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'registrar.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'lista.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'ver.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'descargar_file.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'editar.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'agregar_institucion.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'ver_grupo_familiar.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'ver_registro_asuss.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'registrar_tipo_sangre.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'reafiliacion.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'editar_institucion.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'carntizacion.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'file_digital.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'aportes.titulares',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);



//


    // beneficiarios
    Permission::create([
        'name' => 'menu.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'registrar.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'lista.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'ver.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'descargar_file.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'editar.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'registrar_tipo_sangre.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'reafiliacion.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

    Permission::create([
        'name' => 'carntizacion.beneficiarios',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2]);

//
    // estudiantes SSUE
    Permission::create([
        'name' => 'menu.afiliacion_estudiantes_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role3]);

    Permission::create([
        'name' => 'lista.afiliacion_estudiantes_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role3]);

    Permission::create([
        'name' => 'ver.afiliacion_estudiantes_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role3]);

    Permission::create([
        'name' => 'afiliar.afiliacion_estudiantes_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role3]);

    Permission::create([
        'name' => 'observar.afiliacion_estudiantes_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role3]);

   
  

//
    // afiliados
    Permission::create([
        'name' => 'menu.afiliados',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3]);



    //
    // certificados
    Permission::create([
        'name' => 'menu.certificados_afiliacion',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3]);

    Permission::create([
        'name' => 'lista.certificados_afiliacion',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3]);

    Permission::create([
        'name' => 'ver.certificados_afiliacion',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3]);

    Permission::create([
        'name' => 'registrar.certificados_afiliacion',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3]);

    //
    // certificado afiliado
    Permission::create([
        'name' => 'menu.certificado_afiliado',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3,$role6]);

    Permission::create([
        'name' => 'lista.certificado_afiliado',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3,$role6]);

    Permission::create([
        'name' => 'ver.certificado_afiliado',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3,$role6]);

    Permission::create([
        'name' => 'registrar.certificado_afiliado',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1,$role2,$role3,$role6]);

    //
     // afiliacion ssue
     Permission::create([
        'name' => 'menu.afiliacion_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role4]);

    Permission::create([
        'name' => 'registro.afiliacion_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role4]);


     // afiliacion seguimiento ssue
    Permission::create([
        'name' => 'menu.afiliacion_seguimiento_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role4]);
    
    Permission::create([
        'name' => 'ver.afiliacion_seguimiento_ssue',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role4]);

    //Examenes medicos
    Permission::create([
        'name' => 'menu.examen_preocupacional',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1]);
    
    Permission::create([
        'name' => 'ver.examen_medico',
        'guard_name'=>'web',
        'permissions_enabled'=>true
        ])->syncRoles([$role1]);


    //

    }
}
