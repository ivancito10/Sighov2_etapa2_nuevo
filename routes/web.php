<?php

use App\Http\Controllers\Administracion\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth; //se añadio
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\livewire\Pruebas;
use App\Models\Vigencia\TipoConvenio;
use App\Models\Aportes\ufv;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Administracion\VerificacionDocumentos\VerificarDocumento;
use App\Livewire\Administracion\CambioContrasenia\CambioContraseniaInicio;
use App\Http\Controllers\Aportes\planillaExternosPdf;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// RUTA PARA EL FOOTER
Route::get('/pdf-footer', function () {
    return view('Aportes.pdf.footer');
});

Route::get('ftp', function () {
    $files = Storage::disk('ftp')->allfiles();
    dd($files);
});
//ruta para vereficar documento
Route::get('/verificar-documento/{codigo_seguridad}', VerificarDocumento::class)->name('verificar.documento');
require __DIR__ . '/auth.php';
