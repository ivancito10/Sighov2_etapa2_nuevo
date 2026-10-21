<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administracion\UserPassword;
use App\Http\Controllers\SolicitudInsumos\PdfSolicitudInsumosController;


// ------------------------------------------------------------------------------------------------- PARA LAS SOLICITUDES DE ALMACEN DE FARMACIA -------------------------------------------------------------------------------------------------

Route::get('solicitud_insumos/index', function () {
    return view('SolicitudInsumos.index');
})
    ->name('solicitud_insumos.index')->middleware(['auth', 'verified']);


Route::get('solicitud_insumos/create', function () {
    return view('SolicitudInsumos.create');
})
    ->name('solicitud_insumos.create')->middleware(['auth', 'verified']);


Route::get('solicitud_insumos/edit/{id}', function ($id) {
    $id = $id;
    return view('SolicitudInsumos.edit', compact('id'));
})
    ->name('solicitudInsumos.edit')->middleware(['auth', 'verified']);


//PDF DE SOLICITUD DE INSUMOS
Route::get('/farmacia/solicitudinsumos/pdf/{modo}/{id}', [PdfSolicitudInsumosController::class, 'generarPDFSolicitudInsumos'])
    ->name('farmacia.solicitudinsumos.pdf')
    ->middleware(['auth', 'verified']);


//RUTA PARA LA PANTALLA DE SOLICITUDES PENDIENTES - SECRETARIA DE GERENCIA DE SALUD

Route::get('solicitudes_pendientes/index', function () {
    return view('SolicitudesPendientes.index');
})
    ->name('solicitudes_pendientes.index')->middleware(['auth', 'verified']);



// ------------------------------------------------------------------------------------------------- PARA LAS SOLICITUDES DE ALMACEN GENERAL -------------------------------------------------------------------------------------------------

Route::get('solicitud_materiales/index', function () {
    return view('SolicitudMateriales.index');
})
    ->name('solicitud_materiales.index')->middleware(['auth', 'verified']);


Route::get('solicitud_materiales/create', function () {
    return view('SolicitudMateriales.create');
})
    ->name('solicitud_materiales.create')->middleware(['auth', 'verified']);


Route::get('solicitud_materiales/edit/{id}', function ($id) {
    $id = $id;
    return view('SolicitudMateriales.edit', compact('id'));
})
    ->name('solicitud_materiales.edit')->middleware(['auth', 'verified']);


//PDF DE SOLICITUD DE INSUMOS
/*Route::get('/farmacia/solicitudinsumos/pdf/{modo}/{id}', [PdfSolicitudInsumosController::class, 'generarPDFSolicitudInsumos'])
    ->name('farmacia.solicitudinsumos.pdf')
    ->middleware(['auth', 'verified']);*/


//RUTA PARA LA PANTALLA DE SOLICITUDES PENDIENTES - ALMACEN GENERAL AHORA PARA QUE BIENES Y SERVICIOS APROBADOS

Route::get('solicitudes_pendientes_materiales/index', function () {
    return view('SolicitudesPendientesMateriales.index');
})
    ->name('solicitudes_pendientes_materiales.index')->middleware(['auth', 'verified']);


// ------------------------------------------------------------------------------------------------- PARA LAS SOLICITUDES RAPIDAS -------------------------------------------------------------------------------------------------

Route::get('solicitud_materiales_directa/index', function () {
    return view('SolicitudMaterialesDirecta.index');
})
    ->name('solicitud_materiales_directa.index')->middleware(['auth', 'verified']);


Route::get('solicitud_materiales_directa/create', function () {
    return view('SolicitudMaterialesDirecta.create');
})
    ->name('solicitud_materiales_directa.create')->middleware(['auth', 'verified']);