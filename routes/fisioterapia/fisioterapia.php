<?php

use App\Http\Controllers\Administracion\PruebasFormales\PruebaPdfController;
use App\Http\Controllers\Administracion\UserPassword;
use App\Http\Controllers\ImagenProxyController;
use Illuminate\Support\Facades\Route;


Route::get('fisioterapia/administracion/fisioterapeutas/index', function () {
    return view('Fisioterapia.Administracion.Fisioterapeutas.index');
    })->name('fisioterapia.administracion.fisioterapeutas.index')->middleware(['auth', 'verified', 'can:fisioterapia.administrar']);

Route::get('fisioterapia/administracion/politicas-agenda/index', function () {
    return view('Fisioterapia.Administracion.PoliticaAgenda.index');
})->name('fisioterapia.administracion.politica-agenda.index')->middleware(['auth', 'verified', 'can:fisioterapia.administrar']);

Route::get('fisioterapia/administracion/tecnicas/index', function () {
    return view('Fisioterapia.Administracion.Tecnicas.index');
})->name('fisioterapia.administracion.tecnicas.index')->middleware(['auth', 'verified', 'can:fisioterapia.tecnicas.ver']);

Route::get('fisioterapia/ordenes/index', function () {
    return view('Fisioterapia.Ordenes.index');
})->name('fisioterapia.ordenes.index')->middleware(['auth', 'verified', 'can:fisioterapia.ordenes.ver']);

Route::get('fisioterapia/agendamiento/index', function () {
    return view('Fisioterapia.Agendamiento.index');
})->name('fisioterapia.agendamiento.index')->middleware(['auth', 'verified', 'can:fisioterapia.administrar']);

Route::get('fisioterapia/atencion/index', function () {
    return view('Fisioterapia.Atencion.index');
})->name('fisioterapia.atencion.index')->middleware(['auth', 'verified', 'can:fisioterapia.administrar']);

// Route::get('fisioterapia/index', function () {
//     return view('Fisioterapia.index');
// })->name('fisioterapia.index');