<?php

use App\Http\Controllers\AlmacenGeneral\CierreGestionController;
use App\Http\Controllers\AlmacenGeneral\PDFBindcardController;
use App\Http\Controllers\AlmacenGeneral\PdfIngresoMaterialesController;
use App\Http\Controllers\AlmacenGeneral\PdfReingresoAlmacenGeneralController;
use App\Http\Controllers\AlmacenGeneral\pdfSolicitud;
use App\Http\Controllers\AlmacenGeneral\PdfSolicitudController;
use Illuminate\Support\Facades\Route;

// RUTAS PARA ALMACEN GENERAL ---------------
Route::get('almacen_general/materiales/index', function () {
    return view('AlmacenGeneral.Materiales.index');
})->name('almacen_general.materiales.index')->middleware(['auth', 'verified']);

Route::get('almacen_general/proveedores/index', function () {
    return view('AlmacenGeneral.Proveedores.index');
})->name('almacen_general.proveedores.index')->middleware(['auth', 'verified']);

//PARA LOS INGRESOS
Route::get('almacen_general/ingresos/index', function () {
    return view('AlmacenGeneral.Ingresos.index');
})->name('almacen_general.ingresos.index')->middleware(['auth', 'verified']);

Route::get('almacen_general/ingresos/create', function () {
    return view('AlmacenGeneral.Ingresos.create');
})->name('almacen_general.ingresos.create')->middleware(['auth', 'verified']);

Route::get('almacen_general/ingresos/edit/{id}', function ($id) {
    $id = $id;

    return view('AlmacenGeneral.Ingresos.edit', compact('id'));
})
    ->name('almacen_general.ingresos.edit')->middleware(['auth', 'verified']);

//PARA PDF DE INGRESO DE MATERIALES DE ALMACEN GENERAL
Route::get('almacengeneral/ingresosmateriales/pdf/{modo}/{rowId}', [PdfIngresoMaterialesController::class, 'generarPDFIngresoMateriales'])
    ->name('almacengeneral.ingresosmateriales.pdf')
    ->middleware(['auth', 'verified']);

//PARA LAS SOLICITUDES

Route::get('almacen_general/solicitudes_unidades/index', function () {
    return view('AlmacenGeneral.SolicitudesUnidades.index');
})->name('almacen_general.solicitudes_unidades.index')->middleware(['auth', 'verified']);

Route::get('almacen-general/solicitudes_unidades/solicitudes-unidades-revisar/{id}', function ($id) {
    $id = $id;

    return view('AlmacenGeneral.SolicitudesUnidades.revisar_solicitud', compact('id'));
})
    ->name('almacen-general.solicitudes-unidades-revisar')->middleware(['auth', 'verified']);

Route::get('almacen-general/solicitudes_unidades/solicitudes-unidades-entregar/{id}', function ($id) {
    $id = $id;

    return view('AlmacenGeneral.SolicitudesUnidades.entregar_solicitud', compact('id'));
})
    ->name('almacen-general.solicitudes-unidades-entregar')->middleware(['auth', 'verified']);

Route::get('almacenfarmacia/solicitudes-unidades/pdf/{modo}/{rowId}', [PdfSolicitudController::class, 'generarPDFSolicitudUnidades'])
    ->name('almacen_general.solicitud_unidades.pdf') 
    ->middleware(['auth', 'verified']);

//PARA APROBAR LA SOLICITUD
Route::get('almacen-general/solicitudes_unidades/solicitudes-unidades-aprobar/{id}', function ($id) {
    $id = $id;

    return view('SolicitudesPendientesMateriales.aprobar_solicitud', compact('id'));
})
    ->name('almacen-general.solicitudes-unidades-aprobar')->middleware(['auth', 'verified']);

// PARA CIERRE DE GESTION
Route::get('almacen_general/cierre_gestion/index', function () {
    return view('AlmacenGeneral.CierreGestion.index');
})->name('almacen_general.cierre_gestion.index')->middleware(['auth', 'verified']);

//PDF CIERRE DE GESTION 
Route::get('almacen_general/cierre_gestion/pdf', [CierreGestionController::class, 'pdfCierreGestionAlmacenGeneral'])
    ->name('almacen_general.cierre_gestion.pdf')
    ->middleware(['auth', 'verified']);

//EXCEL CIERRE DE GESTION 
Route::get('almacen_general/cierre_gestion/excel', [CierreGestionController::class, 'excelCierreGestionAlmacenGeneral'])
    ->name('almacen_general.cierre_gestion.excel')
    ->middleware(['auth', 'verified']);

    
// PARA EL BIND CARD
Route::get('almacen_general/bin_card/index', function () {
    return view('AlmacenGeneral.BinCard.index');
})->name('almacen_general.bin_card.index')->middleware(['auth', 'verified']);
// PARA EL STOCK
Route::get('almacen_general/stock/index', function () {
    return view('AlmacenGeneral.Stock.index');
})->name('almacen_general.stock.index')->middleware(['auth', 'verified']);

//RUTAS PARA REINGRESOS ALMACEN
Route::get('almacen_general/reingresos/index', function () {
    return view('AlmacenGeneral.Reingresos.index');
})
    ->name('almacen-general.reingresos.index')->middleware(['auth', 'verified']);

//Ruta pra nuevo reingreso de medicamento
Route::get('almacen_general/reingresos/create', function () {
    return view('AlmacenGeneral.Reingresos.create');
})
    ->name('almacen-general.reingresos.create')->middleware(['auth', 'verified']);

// PDF PARA REINGRESO DE MATERIALES
Route::get('almacen_general/reingresos/pdf/{modo}/{id_reingreso}', [PdfReingresoAlmacenGeneralController::class, 'generarPDFReingreso'])
    ->name('almacen_general.reingresos.pdf')
    ->middleware(['auth', 'verified']);

//PDF DE BIND CARD DE ALMACEN DE FARMACIA
Route::get('almacen_general/bind-card/pdf/{fecha_inicio}/{fecha_fin}/{id_material}/{unidad}', [PDFBindcardController::class, 'generarPDFBindcard'])
    ->name('almacen_general.bind-card.pdf')
    ->middleware(['auth', 'verified']);

//EXCEL DE BIND CARD ALAMACEN DE FARMACIA
Route::get('alamcen_general/bind-card/excel/{fecha_inicio}/{fecha_fin}/{id_material}/{unidad}', [PDFBindcardController::class, 'exportarExcel'])
    ->name('almacen_general.bind-card.excel')
    ->middleware(['auth', 'verified']);

