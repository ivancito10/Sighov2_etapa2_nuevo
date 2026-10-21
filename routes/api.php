<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\personaAsusController;
use App\Http\Controllers\Administracion\NotificacionUsuarioController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ExcelController;
use App\Services\ConexionAsuss;
use App\Services\Fotos\UserService;
use App\Services\Fotos\EspecialistaService;
use App\Services\Fotos\PersonaService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('private.routes')->group(function () {
        Route::get('/obtener_imagen_usuario/{id_user}', [UserService::class, 'obtener_imagen_usuario'])
            ->name('obtener_imagen_usuario');
        Route::get('/obtener_imagen_especialista/{id_especialista}', [EspecialistaService::class, 'obtener_imagen_especialista'])
            ->name('obtener_imagen_especialista');
        Route::get('/obtener_imagen_persona/{id_persona}', [PersonaService::class, 'obtener_imagen_persona'])
            ->name('obtener_imagen_persona');
    }
);

Route::middleware('verifyRoutes')->group(function () {
    Route::get('url_imagen_usuario/{id_user}', [UserService::class, 'url_imagen_usuario'])
        ->name('url_imagen_usuario');
    Route::get('url_imagen_especialista/{id_especialista}', [EspecialistaService::class, 'url_imagen_especialista'])
        ->name('url_imagen_especialista');
    Route::get('url_imagen_persona/{id_persona}', [PersonaService::class, 'url_imagen_persona'])
        ->name('url_imagen_persona');
});
Route::get('/testUser', [ConexionAsuss::class, 'login2']);
// * ruta para probar el envio de correos electronicos
Route::post('email', [EmailController::class, 'envioEmail']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// [personaAsusController::class,'listarPersonaAsus']
Route::post('excel', [ExcelController::class, 'archivo']);
Route::post('notificacion', [NotificacionUsuarioController::class, 'registrarNotificacion']);
// });
Route::get('/personaASUS', [personaAsusController::class, 'listarPersonaAsus']);

Route::get('/personaASUS/{id}', [personaAsusController::class, 'obtenerPersonaAsus']);
Route::post('/personaASUS', [personaAsusController::class, 'crearPersonaAsus']);
Route::put('/personaASUS/{id}', [personaAsusController::class, 'actualizarPersonaAsus']);
Route::delete('/personaASUS', function (Request $request) {
    return "este metodo es delete";
});
