<?php

use Illuminate\Support\Facades\Route;


// RUTAS PARA AGENDA GENERAL ---------------
Route::get('agendas/agenda_general/index', function () {
    return view('Agendas.AgendaGeneral.index');
})->name('agendas.agenda_general.index')->middleware(['auth', 'verified']);

//RUTAS PARA LA AGENDA DE IMAGENOLOGIA/ ECOGRAFIA
Route::get('imagenologia/agenda_ecografia/index', function () {
    return view('Imagenologia.AgendaEcografia.index');
})->name('imagenologia.agenda_ecografia.index')->middleware(['auth', 'verified']);
