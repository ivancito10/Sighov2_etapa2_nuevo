
<?php

use App\Http\Controllers\Administracion\PruebasFormales\PruebaPdfController;
use App\Http\Controllers\Administracion\UserPassword;
use App\Http\Controllers\ImagenProxyController;
use Illuminate\Support\Facades\Route;

Route::get('admin/Apo/ClaseAportante/index', function () {
    return view('Administracion.Aportes.ClaseAportante.index');
})->name('adm.apo.claseaportante.index');
Route::get('admin/ValidarPersonasAsuss/index', function () {
    return view('Administracion.Aportes.ValidarPersona.index');
})->name('adm.apo.validarpersona.index');

Route::get('admin/zonas/index', function () {
    return view('Administracion.Afiliacion.Zonas.index');
})
    ->name('Administracion.zonas.index')->middleware(['auth']);

// Ruta para las zonas

Route::get('admin/zonas/create', function () {

    return view('Administracion.Afiliacion.Zonas.create');
})->name('administracion.zonas.create')->middleware(['auth']);

Route::get('admin/RolesPermisos/index', function () {
    return view('Administracion.RolesPermisos.index');
})
    ->name('administracion.rolespermisos.index')->middleware(['auth', 'verified']);

Route::get('admin/Usuarios/index', function () {
    return view('Administracion.Usuarios.index');
})
    ->name('administracion.usuarios.index')->middleware(['auth', 'verified']);

//para Unidades
Route::get('admin/unidades/create', function () {
    return view('Administracion.Unidades.create');
})->name('administracion.unidades.create')->middleware(['auth']);

Route::get('admin/unidades/index', function () {
    return view('Administracion.Unidades.index');
})
    ->name('administracion.unidades.index')->middleware(['auth']);
// para el usuario
Route::get('admin/Usuarios/create', function () {

    return view('Administracion.Usuarios.create');
})->name('administracion.usuarios.create')->middleware(['auth']);

Route::get('Administracion/usuario/resetPassword/{id_usuario}/{password}', [UserPassword::class, 'pdf_reset'])
    ->name('administracion.usuario.pdf_reset')->middleware(['auth']);

Route::get('Administracion/usuario/nuevo/{id_usuario}', [UserPassword::class, 'pdf_user_new'])
    ->name('administracion.usuario.nuevo')->middleware(['auth']);

Route::get('Administracion/usuario/nuevo_afiliado/{id_usuario}', [UserPassword::class, 'pdf_user_afiliacion'])
    ->name('administracion.usuario.nuevo_afiliado')->middleware(['auth']);

// recordar usuario
Route::get('Administracion/usuario/repetir/reafiliacion/{id_usuario}', [UserPassword::class, 'pdf_user_reafiliacion'])
    ->name('administracion.usuario.reafiliacion')->middleware(['auth']);

// recordar usuario beneficiario
Route::get('Administracion/usuario/repetir/reafiliacion/beneficiario/{id_usuario}', [UserPassword::class, 'pdf_user_reafiliacion_beneficiario'])
    ->name('administracion.usuario.reafiliacion_beneficiario')->middleware(['auth']);
// Vista Notificaciones
Route::get('admin/notificaciones/index', function () {
    return view('livewire.Administracion.notificaciones.notificaciones-listado');
});

// cambio de contraseña
Route::get('admin/cambiocontrasenia/index', function () {
    return view('Administracion.CambioContrasenia.index');
});

// dashboard
Route::get('admin/dashboard/index', function () {
    return view('Administracion.Dashboard.index');
});

Route::get('admin/unidades/index', function () {
    return view('Administracion.Unidades.index');
})
    ->name('administracion.unidades.index')->middleware(['auth', 'verified']);

Route::get('admin/personal_ssu/index', function () {
    return view('Administracion.PersonalSSU.index');
})
    ->name('administracion.personal_ssu.index')->middleware(['auth', 'verified']);

// editar usuario
Route::get('admin/usuarios/edit/{id}', function ($id) {
    return view('Administracion.Usuarios.edit', compact('id'));
})->name('administracion.usuarios.edit')->middleware(['auth']);

// ver credenciales
// resources/views/Administracion/Usuarios/pdfCredencialesUsuario.blade.php
Route::get('Administracion/Usuarios/pdfCredencialesUsuario/{id}', [UserPassword::class, 'credencialesInicio'])->name('administracion.usuarios.credenciales')->middleware(['auth']);

// PARA LOS VIDEOS

Route::get('admin/videos/index', function () {
    return view('Administracion.VideosFile.index');
})->name('administracion.videos.index')->middleware(['auth']);

Route::get('admin/videos/create', function () {
    return view('Administracion.VideosFile.create');
})->name('administracion.videos.create')->middleware(['auth']);

Route::get('admin/videos/edit/{id}', function ($id) {
    return view('Administracion.VideosFile.edit', compact('id'));
})->name('administracion.videos.edit')->middleware(['auth']);

Route::get('admin/videos/ver/{id}', function ($id) {
    return view('Administracion.VideosFile.ver', compact('id'));
})->name('administracion.videos.ver')->middleware(['auth']);

// PARA LA CONTRASTACION DE DATOS
Route::get('admin/contrastacion/index', function () {
    return view('Administracion.Contrastacion.index');
})->name('administracion.contrastacion.index')->middleware(['auth']);

// PARA LAS IMAGENES
Route::get('admin/imagenes/index', function () {
    return view('Administracion.ImagenesFile.index');
})->name('administracion.imagenes.index')->middleware(['auth']);

//PROXY PARA RECIBIR IMAGENES HTTP
Route::get('/imagen/proxy', [ImagenProxyController::class, 'mostrar'])
    ->name('imagen.proxy');

//RUTA PARA LAS PRUEBAS QUE TENGAMOS QUE USAR
Route::get('admin/formalizacion/codigo', function () {
    return view('Administracion.PruebasFormalesEjecucionCodigo.index');
})->name('administracion.formalizacion.codigo')->middleware(['auth']);

//RUTA PARA LA ADMINISTRACION DE LA VISTA DE LABORATORIO PARA PONER O NO CHECKS

Route::get('admin/laboratorio/vista_laboratorio', function () {
    return view('Administracion.AdministracionVistaLaboratorio.index');
})->name('administracion.vistalaboratorio.index')->middleware(['auth']);

// PRUEBAS FORMALES
Route::get('/administracion/pruebas-formales/ver-pdf', [PruebaPdfController::class, 'verPdfTest'])
    ->name('pruebas.pdf.posicion');