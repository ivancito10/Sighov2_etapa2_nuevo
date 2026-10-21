<?php

use Illuminate\Support\Facades\Route;


// RUTAS PARA ALMACEN GENERAL ---------------
Route::get('bioestadistica/administracion_cie10/index', function () {
    return view('Bioestadistica.AdministracionCie10.index');
})->name('bioestadistica.administracion_cie10.index')->middleware(['auth', 'verified']);
Route::get('bioestadistica/administracion_cie10/create', function () {
    return view('Bioestadistica.AdministracionCie10.create');
})->name('bioestadistica.administracion_cie10.create')->middleware(['auth', 'verified']);
Route::get('bioestadistica/administracion_cie10/edit', function () {
    return view('Bioestadistica.AdministracionCie10.edit');
})->name('bioestadistica.administracion_cie10.edit')->middleware(['auth', 'verified']);