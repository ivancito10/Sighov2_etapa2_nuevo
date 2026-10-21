<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Farmacia\AlmacenFarmacia\pdfIngresoController;
use App\Http\Controllers\Farmacia\AlmacenFarmacia\PdfReingresoAlmacenMedController;
use App\Http\Controllers\Farmacia\CierreGestionController;
use App\Http\Controllers\Farmacia\AlmacenFarmacia\PdfSolicitudController;
use App\Http\Controllers\Farmacia\Farmacia\PdfReingresoController;
use App\Models\Farmacia\Medicamento;
use App\Http\Controllers\Farmacia\PDFBindcardController;



Route::get('almacen_farmacia/medicamentos/index', function () {
    //$medicamentos = Medicamento::all();
    //dd($medicamentos);
    return view('Farmacia.AlmacenFarmacia.Medicamentos.index');
})
    ->name('almacen_farmacia.medicamentos.index')->middleware(['auth', 'verified']);



Route::get('almacen_farmacia/proveedores/index', function () {
    return view('Farmacia.AlmacenFarmacia.Proveedores.index');
})
    ->name('almacen_farmacia.proveedores.index')->middleware(['auth', 'verified']);

//RUTAS PARA INGRESO DE MEDICAMENTOS

Route::get('almacen_farmacia/ingresos/index', function () {
    return view('Farmacia.AlmacenFarmacia.Ingresos.index');
})
    ->name('almacen_farmacia.ingresos.index')->middleware(['auth', 'verified']);

Route::get('almacen_farmacia/ingresos/create', function () {
    return view('Farmacia.AlmacenFarmacia.Ingresos.create');
})
    ->name('almacen_farmacia.ingresos.create')->middleware(['auth', 'verified']);

Route::get('almacen_farmacia/ingresos/edit/{id}', function ($id) {
    $id = $id;
    return view('Farmacia.AlmacenFarmacia.Ingresos.edit', compact('id'));
})
    ->name('almacen_farmacia.ingresos.edit')->middleware(['auth', 'verified']);

//PDF DE INGRESOS A ALMACEN DE FARMACIA
Route::get('farmacia/almacenfarmacia/ingresos/pdf/{modo}/{rowId}', [pdfIngresoController::class, 'generarPDFIngresoMedicamento'])
    ->name('farmacia.almacenfarmacia.ingresos.pdf')
    ->middleware(['auth', 'verified']);

//PDF DE SOLICITUD DE FARMACIA A ALMACEN DE FARMACIA
Route::get('farmacia/almacenfarmacia/solicitud-farmacia/pdf/{modo}/{rowId}', [PdfSolicitudController::class, 'generarPDFSolicitudFarmacia'])
    ->name('farmacia.almacenfarmacia.solicitud-farmacia.pdf')
    ->middleware(['auth', 'verified']);

//PDF DE SOLICITUD DE UNIDAD A ALMACEN DE FARMACIA
Route::get('farmacia/almacenfarmacia/solicitudes-unidades/pdf/{modo}/{rowId}', [PdfSolicitudController::class, 'generarPDFSolicitudUnidades'])
    ->name('farmacia.almacenfarmacia.solicitudes-unidades.pdf')
    ->middleware(['auth', 'verified']);


// para la ruta de descarga de excel 
Route::get('farmacia/almacenfarmacia/reporte_cierre_gestion', [CierreGestionController::class, 'reporteCierreGestion_AlmacenFarmacia'])
    ->name('pdf.generateCierreGestionAlmacenFarmacia')->middleware(['auth', 'verified']);

// Route::get('/admin',function(){
//     return "hola";
// });

Route::get('almacen_farmacia/solicitudes/index', function () {
    //$medicamentos = Medicamento::all();
    //dd($medicamentos);
    return view('Farmacia.AlmacenFarmacia.SolicitudesUnidades.index');
})
    ->name('almacen_farmacia.solicitudes.index')->middleware(['auth', 'verified']);

//------ ruta para entregar solicitud de unidades
Route::get('farmacia/almacen-farmacia/solicitudes-unidades/solicitudes-unidades-entregar/{id}', function ($id) {
    $id = $id;
    return view('Farmacia.AlmacenFarmacia.SolicitudesUnidades.entregar_solicitud', compact('id'));
})
    ->name('farmacia.almacen-farmacia.solicitudes-unidades-entregar')->middleware(['auth', 'verified']);

//------ ruta para entregar solicitud de farmacia
Route::get('farmacia/almacen-farmacia/solicitudes-farmacia/solicitudes-farmacia-entregar/{id}', function ($id) {
    $id = $id;
    return view('Farmacia.AlmacenFarmacia.SolicitudesFarmacia.entregar_solicitud_far', compact('id'));
})
    ->name('farmacia.almacen-farmacia.solicitudes-farmacia-entregar')->middleware(['auth', 'verified']);


//Rutas para solicitud de medicamentos a farmacia
Route::get('almacen_farmacia/solicitudes/farmacia/index', function () {
    return view('Farmacia.AlmacenFarmacia.SolicitudesFarmacia.index');
})
    ->name('farmacia.almacen-farmacia.solicitudes-farmacia.index')->middleware(['auth', 'verified']);

//Rutas para entregaer solicitud de medicamentos a farmacia
Route::get('almacen_farmacia/solicitudes/farmacia/entregar', function () {
    return view('Farmacia.AlmacenFarmacia.SolicitudesFarmacia.entregar');
})
    ->name('farmacia.almacen-farmacia.solicitudes-farmacia.entregar')->middleware(['auth', 'verified']);


//Rutas para cierre de gestion almacen de farmacia
Route::get('almacen_farmacia/farmacia/cierre-gestion/index', function () {
    return view('Farmacia.AlmacenFarmacia.CierreGestion.index');
})
    ->name('farmacia.almacen-farmacia.cierre-gestion.index')
    ->middleware(['auth', 'verified']);

//PDF CIERRE DE GESTION DE ALMACEN DE FARMACIA
Route::get('farmacia/almacenfarmacia/cierre-gestion/pdf/{unidad}', [CierreGestionController::class, 'reporteCierreGestionFarmacia'])
    ->name('farmacia.almacenfarmacia.cierre-gestion.pdf')
    ->middleware(['auth', 'verified']);

//EXCEL CIERRE DE GESTION DE FARMACIA
Route::get('farmacia/almacenfarmacia/cierre-gestion/excel/{unidad}', [CierreGestionController::class, 'reporteCierreGestionExcel'])
    ->name('farmacia.alamacenfarmacia.cierre-gestion.excel')
    ->middleware(['auth', 'verified']);


//Rutas para stock
Route::get('almacen_farmacia/stock/index', function () {
    return view('Farmacia.AlmacenFarmacia.Stock.index');
})
    ->name('farmacia.almacen-farmacia.stock.index')
    ->middleware(['auth', 'verified']);

//PDF DE BIND CARD DE ALMACEN DE FARMACIA
Route::get('farmacia/almacenfarmacia/bind-card/pdf/{fecha_inicio}/{fecha_fin}/{id_medicamento}/{unidad}', [PDFBindcardController::class, 'generarPDFBindcard'])
    ->name('farmacia.almacenfarmacia.bind-card.pdf')
    ->middleware(['auth', 'verified']);

//EXCEL DE BIND CARD ALAMACEN DE FARMACIA
Route::get('farmacia/alamcenfarmacia/bind-card/excel/{fecha_inicio}/{fecha_fin}/{id_medicamento}/{unidad}', [PDFBindcardController::class, 'exportarExcel'])
    ->name('farmacia.almacenfarmacia.bind-card.excel')
    ->middleware(['auth', 'verified']);


//RUTAS PARA REINGRESOS ALMACEN
Route::get('farmacia/almacenfarmacia/reingresos/index', function () {
    return view('Farmacia.AlmacenFarmacia.Reingresos.index');
})
    ->name('almacen-farmacia.reingresos.index')->middleware(['auth', 'verified']);

//Ruta pra nuevo reingreso de medicamento
Route::get('farmacia/almacenfarmacia/reingresos/create', function () {
    return view('Farmacia.AlmacenFarmacia.Reingresos.create');
})
    ->name('almacen-farmacia.reingresos.create')->middleware(['auth', 'verified']);

// PDF PARA REINGRESO DE MEDICAMENTOS
Route::get('farmacia/almacenfarmacia/reingresos/pdf/{modo}/{id_reingreso}', [PdfReingresoAlmacenMedController::class, 'generarPDFReingreso'])
    ->name('farmacia.almacen-farmacia.reingresos.pdf')
    ->middleware(['auth', 'verified']);
