<?php

use App\Http\Controllers\Liquidaciones\ReportesAgendasController;
use Illuminate\Support\Facades\Route;


// RUTAS PARA FARMACIA  ---------------
Route::get('liquidaciones/farmacia/index', function () {
    return view('Liquidaciones.Farmacia.index');
})->name('liquidaciones.farmacia.index')
    ->middleware(['auth', 'verified']);

Route::get('liquidaciones/agendas/index', function () {
    return view('Liquidaciones.Agendas.index');
})->name('liquidaciones.agendas.index')
    ->middleware(['auth', 'verified']);

Route::get('liquidaciones/rayosx/index', function () {
    return view('Liquidaciones.RayosX.index');
})->name('liquidaciones.rayosx.index')
    ->middleware(['auth', 'verified']);

Route::get('liquidaciones/laboratorio/index', function () {
    return view('Liquidaciones.Laboratorio.index');
})->name('liquidaciones.laboratorio.index')
    ->middleware(['auth', 'verified']);

// reportes de liquidaciones
Route::get(
    'liquidacion/reportes/{tipo_asegurado}/{id_institucion}/{fecha_inicio}/{fecha_fin}',
    [ReportesAgendasController::class, 'reportesAgendasLiquidacion']
)->name('pdf.generateReporteAgendas')->middleware(['auth', 'verified']);
