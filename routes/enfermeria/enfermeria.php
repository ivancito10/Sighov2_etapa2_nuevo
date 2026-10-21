<?php

use Illuminate\Support\Facades\Route;

Route::get('enfermeria/inyectables/index', function () {
    return view('Enfermeria.Inyectables.index');
})->name('enfermeria.inyectables.index')->middleware(['auth', 'verified']);

Route::get('enfermeria/inyectables/create', function () {
    return view('Enfermeria.Inyectables.create');
})->name('enfermeria.inyectables.create')->middleware(['auth']);