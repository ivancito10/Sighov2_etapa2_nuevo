<?php

use App\Http\Controllers\AtencionMedica\ConsultaExterna\pdfConsultaExternaController;
use App\Http\Controllers\AtencionMedica\ConsultaExterna\pdfFichaAnticipadaController;
use App\Http\Controllers\AtencionMedica\ConsultaExterna\PdfRecetaController;
use App\Http\Controllers\AtencionMedica\ConsultaExterna\pdfReporteAtencionMedController;
use App\Http\Controllers\AtencionMedica\Laboratorio\LaboratorioResultController;
use Illuminate\Support\Facades\Route;

// RUTAS PARA CONSULTA EXTERNA ---------------
Route::get('atencion_medica/consulta_externa/index', function () {
    return view('AtencionMedica.ConsultaExterna.index');
})->name('atencion_medica.consulta_externa.index')->middleware(['auth', 'verified']);

// añadir id  27/03/25 , sin id porque no tenemos la tabla todavia, pero despues debemos añadir jiji
Route::get('atencion_medica/consulta_externa/create', function () {
    return view('AtencionMedica.ConsultaExterna.create');
})->name('atencion_medica.consulta_externa.create')->middleware(['auth', 'verified']);

// Primera Vista Historial Clinico completo
// Route::get('atencion_medica/historia_clinica/index', function () {
//     return view('AtencionMedica.HistoriaClinica.index');
// })->name('atencion_medica.historia_clinica.index')->middleware(['auth', 'verified']);

Route::get('atencion_medica/historia_clinica/index', function () {

    $datos_agenda = request()->query('datos_agenda');

    return view('AtencionMedica.HistoriaClinica.index', [
        'datos_agenda' => $datos_agenda
    ]);

})->name('atencion_medica.historia_clinica.index')->middleware(['auth', 'verified']);

//Registrar Agenda Anticipada
Route::get('atencion_medica/historia_clinica/anticipada', function () {
    return view('AtencionMedica.HistoriaClinica.anticipada');
})->name('atencion_medica.historia_clinica.anticipada')->middleware(['auth', 'verified']);

// Pdf Consulta Externa - Registro en Gral.
Route::get('consulta_externa/pdf/c_e/{rowId}', [pdfConsultaExternaController::class, 'pdfConsultaExterna'])
    ->name('pdf.consulta_externa.pdf_ce');

// Pdf Consulta Externa - Registro en Gral.
Route::get('consulta_externa/pdf/c_e_descarga/{rowId}', [pdfConsultaExternaController::class, 'pdfConsultaExternaDescarga'])
    ->name('pdf.consulta_externa.pdf_ce_descarga');

// Pdf Consulta Externa - PDF COPIA.
Route::get('consulta_externa/pdf/c_e_copia/{rowId}', [pdfConsultaExternaController::class, 'pdfConsultaExternaCopia'])
    ->name('pdf.consulta_externa.pdf_ce_copia');
// Recuperar dek FTP
Route::get('consulta_externa/pdf/copia_recuperada/{rowId}', [pdfConsultaExternaController::class, 'recuperarHistoriaFtp'])
    ->name('pdf.consulta_externa.copia_recuperada');

// Pdf Recetas de Consulta Externa
Route::get('consulta_externa/pdf/receta/{rowId}', [pdfConsultaExternaController::class, 'pdfReceta'])
    ->name('pdf.consulta_externa.pdf_receta');

// Pdf Recetas de Consulta Externa en FTP
Route::get('consulta_externa/pdf/receta_ftp/{rowId}', [pdfConsultaExternaController::class, 'pdfRecetaFtp'])
    ->name('pdf.consulta_externa.pdf_receta_ftp');

// Pdf Imagenologia de Consulta Externa
Route::get('consulta_externa/pdf/imagenologia/{rowId}', [pdfConsultaExternaController::class, 'pdfImagenologia'])
    ->name('pdf.consulta_externa.pdf_imagenologia');
// Pdf Baja Medica
Route::get('consulta_externa/pdf/baja_medica/{rowId}', [pdfConsultaExternaController::class, 'pdfBajaMedica'])
    ->name('pdf.consulta_externa.pdf_baja_medica');

// Pdf Laboratorio
Route::get('consulta_externa/pdf/laboratorio/{rowId}', [pdfConsultaExternaController::class, 'pdfLaboratorio'])
    ->name('pdf.consulta_externa.pdf_laboratorio');

// Pdf Fisioterapia
Route::get('consulta_externa/pdf/fisioterapia/{rowId}', [pdfConsultaExternaController::class, 'pdfFisioterapia'])
    ->name('pdf.consulta_externa.pdf_fisioterapia');
// Pdf Internacion
Route::get('consulta_externa/pdf/internacion/{rowId}', [pdfConsultaExternaController::class, 'pdfInternacion'])
    ->name('pdf.consulta_externa.pdf_internacion');
// Pdf Referencia
Route::get('consulta_externa/pdf/referencia/{rowId}', [pdfConsultaExternaController::class, 'pdfReferencia'])
    ->name('pdf.consulta_externa.pdf_referencia');
// Pdf Interconsulta
Route::get('consulta_externa/pdf/interconsulta/{rowId}', [pdfConsultaExternaController::class, 'pdfInterconsulta'])
    ->name('pdf.consulta_externa.pdf_interconsulta');

//Administracion de medicamentos por receta
Route::get('atencion_medica/adm_med_receta/index', function () {
    return view('AtencionMedica.AdmMedReceta.index');
})->name('atencion_medica.adm_med_receta.index')->middleware(['auth', 'verified']);

// RUTAS PARA EPIDEMIOLOGIA -------------------------
Route::get('atencion_medica/epidemiologia/index', function () {
    return view('AtencionMedica.Epidemiologia.index');
})->name('atencion_medica.epidemiologia.index')->middleware(['auth', 'verified']);

// RUTA PARA RAYOS X
Route::get('rayos_x/index', function () {
    return view('RayosX.index');
})->name('rayos_x.index')->middleware(['auth', 'verified']);

Route::get('rayos_x/create/{id}', function ($id) {
    return view('RayosX.create', compact('id'));
})
    ->name('rayos_x.create-RayosX')->middleware(['auth', 'verified']);

Route::get('rayos_x/solicitudes', function () {
    return view('RayosX.Solicitudes.index');
})->name('rayos_x.solicitudes.index')->middleware(['auth', 'verified']);

// RUTA OTRAS CONSULTAS
Route::get('atencion_medica/otras_consultas/index', function () {
    return view('AtencionMedica.OtrasConsultas.index');
})->name('atencion_medica.otras_consultas.index')->middleware(['auth', 'verified']);
// registrar
Route::get('atencion_medica/otras_consultas/create', function () {
    return view('AtencionMedica.OtrasConsultas.create');
})->name('atencion_medica.otras_consultas.create')->middleware(['auth', 'verified']);
// registrar soap
Route::get('atencion_medica/otras_consultas/soap', function () {
    return view('AtencionMedica.OtrasConsultas.soap');
})->name('atencion_medica.otras_consultas.soap')->middleware(['auth', 'verified']);
// Anular bajas medicas
Route::get('atencion_medica/bajas_medicas/index', function () {
    return view('AtencionMedica.AdmBajasMedicas.index');
})->name('atencion_medica.bajas_medicas.index')->middleware(['auth', 'verified']);

// Ruta para el popup de laboratorio resultados
Route::get('laboratorio/pdf/resultados/{rowId}', [LaboratorioResultController::class, 'resultados_pdf'])
    ->name('laboratorio_resultados_servicio.pdf');

// RUTA PARA VARIABLES ADMINISTRABLES ---------------
Route::get('atencion_medica/variables/index', function () {
    return view('AtencionMedica.Variables.index');
})->name('atencion_medica.variables.index')->middleware(['auth', 'verified']);

// RUTA PARA ESPECIALIDAD - VARIABLES   ---------------
Route::get('atencion_medica/especialidad_variables/index', function () {
    return view('AtencionMedica.EspecialidadVariables.index');
})->name('atencion_medica.especialidad_variables.index')->middleware(['auth', 'verified']);

// RUTA PARA FORMULARIO ATENCION MEDICA   ---------------
Route::get('atencion_medica/form_atencion_medica/index', function () {
    return view('AtencionMedica.FormAtencionMedica.index');
})->name('atencion_medica.form_atencion_medica.index')->middleware(['auth', 'verified']);

// ----- PDF de atencion medica
Route::get('consulta_externa/pdf_reportes_atencion/{id_asig}/{fecha}', [pdfReporteAtencionMedController::class, 'pdfOdontologia'])
    ->name('pdf.consulta_externa.pdf_reportes_atencion');

// Pdf ficha anticipada
Route::get(
    'atencion_medica/consulta_externa/pdf_ficha_anticipada/{id}',
    [pdfFichaAnticipadaController::class, 'pdfFichaAnticipada']
)
    ->name('pdf.consulta_externa.ficha_anticipada')->middleware(['auth', 'verified']);

// Para POPUP pdf receta
Route::get('/pdf-receta/{id}', [PdfRecetaController::class, 'mostrarPdf'])->name('pdf.receta');

//ver receta
Route::get('/pdf-receta-preview/{id}', [PdfRecetaController::class, 'pdfRecetaPreview'])->name('pdf.receta.preview');

//ruta para enfermeria
Route::get('atencion-medica/enfermeria/signosvitales/index', function () {
    return view('AtencionMedica.Enfermeria.SignosVitales.index');
})->name('atencion_medica.consulta-externa.enfermeria.signos-vitales.index')->middleware(['auth', 'verified']);
