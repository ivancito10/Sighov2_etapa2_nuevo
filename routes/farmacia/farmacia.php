<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administracion\UserPassword;
use App\Http\Controllers\Farmacia\PdfSolicitudUnidadesController;
use App\Http\Controllers\Farmacia\CierreGestionController;
use App\Http\Controllers\Farmacia\Farmacia\PdfSolicitudController;
use App\Http\Controllers\Farmacia\PDFBindcardController;
use App\Http\Controllers\Farmacia\Farmacia\PdfReingresoController;
use App\Http\Controllers\AtencionMedica\ConsultaExterna\pdfConsultaExternaController;

Route::get('farmacia/solicitudes/index', function () {
    return view('Farmacia.Farmacia.SolicitudesUnidades.index');
})
    ->name('farmacia.solicitudes.index')
    ->middleware(['auth', 'verified']);

//------ ruta para entregar solicitud de unidades
Route::get('farmacia/farmacia/solicitudes-unidades/solicitudes-unidades-entregar/{id}', function ($id) {
    $id = $id;
    return view('Farmacia.Farmacia.SolicitudesUnidades.entregar_solicitud', compact('id'));
})
    ->name('farmacia.solicitudes-unidades.solicitudes-unidades-entregar')->middleware(['auth', 'verified']);


//Rutas para solicitud de medicamentos a farmacia
Route::get('farmacia/solicitudes/almacen/index', function () {
    return view('Farmacia.Farmacia.SolicitudesAlmacen.index');
})
    ->name('farmacia.solicitudes.almacen.index')->middleware(['auth', 'verified']);

//Rutas para solicitud de medicamentos a farmacia create
Route::get('farmacia/solicitudes/almacen/create', function () {
    return view('Farmacia.Farmacia.SolicitudesAlmacen.create');
})
    ->name('farmacia.solicitudes.almacen.create')->middleware(['auth', 'verified']);

//PDF DE SOLICITUD DE FARMACIA A ALMACEN DE FARMACIA
Route::get('farmacia/farmacia/solicitudes-almacen/pdf/{modo}/{id}', [PdfSolicitudController::class, 'generarPDFSolicitudAlmacen'])
    ->name('farmacia.farmacia.solicitudes-almacen.pdf')
    ->middleware(['auth', 'verified']);

//PDF PARA SOLICITUD DE UNIDADES A FARMACIA
Route::get('farmacia/farmacia/solicitudes-unidades/pdf/{modo}/{id}', [PdfSolicitudController::class, 'generarPDFSolicitudUnidades'])
    ->name('farmacia.farmacia.solicitud-unidad.pdf')
    ->middleware(['auth', 'verified']);

//Rutas para solicitud de medicamentos a farmacia edit
Route::get('farmacia/farmacia/solicitudes-almacen/solicitudes-almacen-edit/{id}', function ($id) {
    return view('Farmacia.Farmacia.SolicitudesAlmacen.edit', compact('id'));
})
    ->name('farmacia.solicitudes-almacen.solicitudes-almacen-edit')->middleware(['auth', 'verified']);

//Rutas para cierre de gestion farmacia
Route::get('farmacia/farmacia/cierre-gestion/index', function () {
    return view('Farmacia.Farmacia.CierreGestion.index');
})
    ->name('farmacia.cierre-gestion.index')->middleware(['auth', 'verified']);

// para registrar cierre de gestion
Route::get('farmacia/farmacia/cierre-gestion/create/{id}', function ($id) {
    return view('Farmacia.Farmacia.CierreGestion.create', compact('id'));
})
    ->name('farmacia.cierre-gestion.create')->middleware(['auth', 'verified']);

//PDF CIERRE DE GESTION DE FARMACIA
Route::get('farmacia/farmacia/cierre-gestion/pdf/{unidad}', [CierreGestionController::class, 'reporteCierreGestionFarmacia'])
    ->name('farmacia.farmacia.cierre-gestion.pdf')
    ->middleware(['auth', 'verified']);

//EXCEL CIERRE DE GESTION DE FARMACIA
Route::get('farmacia/farmacia/cierre-gestion/excel/{unidad}', [CierreGestionController::class, 'reporteCierreGestionExcel'])
    ->name('farmacia.farmacia.cierre-gestion.excel')
    ->middleware(['auth', 'verified']);

//Rutas para stock
Route::get('farmacia/farmacia/stock/index', function () {
    return view('Farmacia.Farmacia.Stock.index');
})
    ->name('farmacia.stock.index')->middleware(['auth', 'verified']);


//PDF PARA BIND CARD FARMACIA
Route::get('farmacia/farmacia/bind-card/pdf/{fecha_inicio}/{fecha_fin}/{id_medicamento}/{unidad}', [PDFBindcardController::class, 'generarPDFBindcard'])
    ->name('farmacia.farmacia.bind-card.pdf')
    ->middleware(['auth', 'verified']);

//EXCEL DE BIND CARD FARMACIA
Route::get('farmacia/farmacia/bind-card/excel/{fecha_inicio}/{fecha_fin}/{id_medicamento}/{unidad}', [PDFBindcardController::class, 'exportarExcel'])
    ->name('farmacia.farmacia.bind-card.excel')
    ->middleware(['auth', 'verified']);

//Rutas para reingresos
Route::get('farmacia/farmacia/reingresos/index', function () {
    return view('Farmacia.Farmacia.Reingresos.index');
})
    ->name('farmacia.reingresos.index')->middleware(['auth', 'verified']);

//Ruta pra nuevo reingreso de medicamento
Route::get('farmacia/reingresos/create', function () {
    return view('Farmacia.Farmacia.Reingresos.create');
})
    ->name('farmacia.reingresos.create')->middleware(['auth', 'verified']);

// PDF PARA REINGRESO DE MEDICAMENTOS
Route::get('farmacia/farmacia/reingresos/pdf/{modo}/{id_reingreso}', [PdfReingresoController::class, 'generarPDFReingreso'])
    ->name('farmacia.farmacia.reingresos.pdf')
    ->middleware(['auth', 'verified']);


//Rutas para Recetas
Route::get('farmacia/farmacia/receta/index', function () {
    return view('Farmacia.Farmacia.Receta.index');
})
    ->name('farmacia.receta.index')->middleware(['auth', 'verified']);

//------ ruta para editar institucion
Route::get('farmacia/farmacia/habilitar_medicamento/{id}', function ($id) {
    $id = $id;
    return view('Farmacia.Farmacia.Receta.habilitar_medicamento', compact('id'));
})
    ->name('farmacia.farmacia.habilitar_medicamento.create')->middleware(['auth', 'verified']);


// Pdf Recetas Manual
Route::get('farmacia/pdf/receta_manual/{rowId}', [pdfConsultaExternaController::class, 'pdfRecetaManual'])
    ->name('pdf.farmacia.pdf_receta_manual');
