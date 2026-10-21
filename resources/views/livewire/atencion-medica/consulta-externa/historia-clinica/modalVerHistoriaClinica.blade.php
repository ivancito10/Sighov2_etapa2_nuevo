<x-ver-modal id="modalVerHistoriaClinica" labelledby="modalVerHistoriaClinica" title="  HISTORIA CLÍNICA">
    <div class="historia-clinica-contenedor watermark">
        <div class="container-fluid ">


            <div class="section-box">
                <div class="section-title">IDENTIFICACIÓN PERSONAL</div>
                <div class="row p-2">
                    <div class="col-md-4"><strong>Nombres:</strong> {{ $datos_paciente->nombres ?? '' }}</div>
                    <div class="col-md-2"><strong>Edad:</strong> {{ $edad ?? '' }}</div>
                    <div class="col-md-2"><strong>Género:</strong> {{ $datos_paciente->sexo ?? '' }}</div>
                    <div class="col-md-4"><strong>Fecha de Nacimiento:</strong>
                        {{ $datos_paciente?->fecha_nacimiento
                            ? \Carbon\Carbon::parse($datos_paciente->fecha_nacimiento)->format('d/m/Y')
                            : '' }}
                    </div>
                </div>
                <div class="row p-2">
                    <div class="col-md-4"><strong>Apellidos:</strong>
                        {{ trim(($datos_paciente?->p_apellido ?? '') . ' ' . ($datos_paciente?->s_apellido ?? '')) }}
                    </div>
                    <div class="col-md-4"><strong>Nro de Identificación:</strong>
                        {{ $datos_paciente->ci ?? '' }}{{ $datos_paciente?->complemento ? '-' . $datos_paciente?->complemento : '' }}
                    </div>
                    <div class="col-md-4"><strong>Estado Civil:</strong>
                        {{ $datos_paciente->estadoCivilPersona->nombre ?? '' }}</div>

                </div>
                <div class="row p-2">
                    <div class="col-md-6"><strong>Matrícula:</strong>
                        {{ $datos_paciente->matricula_seguro ?? '' }}</div>
                </div>

                <div class="row p-2">
                    <div class="col-md-6"><strong>Teléfonos de Referencia:</strong> {{ $emergencia ?? '' }}</div>
                </div>
                <div class="row p-2">
                    <div class="col-md-6"><strong>Tipo de Sangre:</strong> {{ $datos_paciente->grupo_sanguineo ?? '' }}
                    </div>
                    <div class="col-md-6"><strong>Alergias:</strong> {{ $alergias ?? 'Ninguna' }}</div>
                </div>
            </div>

            <!-- RESUMEN DE INGRESO -->
            <div class="section-box">
                <div class="section-title">DATOS DE ATENCIÓN MÉDICA</div>
                <div class="row p-2">
                    <div class="col-md-6">
                        <strong>Fecha y Hora de Atención:</strong>
                        {{ \Carbon\Carbon::parse($fecha_hora)->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-6"><strong>Tipo de Atención:</strong> {{ $id_tipo_consulta ?? '' }}</div>
                </div>
                <div class="row p-2">
                    <div class="col-md-6"><strong>Médico:</strong> {{ $nombreEspecialista ?? '' }}</div>
                    <div class="col-md-6"><strong>Especialidad:</strong> {{ $nombreEspecialidad ?? '' }}</div>
                </div>
            </div>

            <!-- RESUMEN DE EGRESO -->
            <!-- <div class="section-box">
                <div class="section-title">RESUMEN DE EGRESO DEL PACIENTE DE LA INSTITUCIÓN</div>
                <div class="row p-2">
                    <div class="col-md-4"><strong>Fecha y Hora de Egreso:</strong> {{ $fecha_egreso ?? '' }}</div>
                    <div class="col-md-4"><strong>Egreso por:</strong> {{ $egreso_por ?? '' }}</div>
                    <div class="col-md-4"><strong>Servicio:</strong> {{ $servicio_egreso ?? '' }} <strong>Cama:</strong> {{ $cama_egreso ?? '' }}</div>
                </div>
                <div class="p-2"><strong>Diagnóstico Clínico Final:</strong> {{ $diagnostico_egreso ?? '' }}</div>
            </div> -->

            <!-- SITUACIÓN SOCIOECONÓMICA -->
            <div class="section-box">
                <div class="section-title"> S.O.A.P.</div>
                <div class="row p-2">
                    <div class="col-md-6"><strong>Motivo de Consulta:</strong> {{ $motivo_consulta ?? '' }}</div>
                    <div class="col-md-6"><strong>Enfermedad Actual:</strong> {{ $enfermedad_actual ?? '' }}</div>

                </div>
                <div class="row p-2">
                    <div class="col-md-12"><strong>Objetivo:</strong> {{ $objetivo ?? '' }}</div>
                    <div class="col-md-12"><strong>Diagnóstico descriptivo:</strong> {{ $descriptivo ?? '' }}</div>
                </div>
                <div class="p-2">
                    <strong>Diagnóstico CIE-10</strong>
                    <br>
                    <div class="table-responsive px-2 pb-3">
                        <table class="table table-bordered table-sm mb-0 bg-diagnostico">
                            <thead style="background-color: #D4E6F1; color:black; text-align:center;">
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($diagnosticosCie10 as $dx)
                                    <tr>
                                        <td style="text-align:center;">{{ $dx->codigo }}</td>
                                        <td>{{ $dx->descripcion }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay diagnósticos
                                            registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- PLANES DE ATENCIÓN -->
            <div class="section-box">
                <div class="section-title">PLANES REGISTRADOS</div>
                <br>
                <div class="row justify-content-center flex-wrap" style="gap: 10px;" wire:ignore>
                    <button class="btn btn-danger mb-2" type="button" title="MOSTRAR RECETA"
                        wire:click="mostrarModalReceta">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-capsule" viewBox="0 0 16 16">
                            <path
                                d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                        </svg> <br>RECETA
                    </button>

                    <button class="btn btn-info mb-2" type="button" title="MOSTRAR LABORATORIO"
                        wire:click="mostrarModalLaboratorio">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-clipboard2-check-fill" viewBox="0 0 16 16">
                            <path
                                d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5" />
                            <path
                                d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5m6.769 6.854-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                        </svg> <br>LABORATORIO
                    </button>

                    <button class="btn btn-primary mb-2" type="button" title="MOSTRAR IMAGENOLOGIA"
                        wire:click="mostrarModalImagenologia">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-lungs-fill" viewBox="0 0 16 16">
                            <path
                                d="M8 1a.5.5 0 0 1 .5.5v5.243L9 7.1V4.72C9 3.77 9.77 3 10.72 3c.524 0 1.023.27 1.443.592.431.332.847.773 1.216 1.229.736.908 1.347 1.946 1.58 2.48.176.405.393 1.16.556 2.011.165.857.283 1.857.24 2.759-.04.867-.232 1.79-.837 2.33-.67.6-1.622.556-2.741-.004l-1.795-.897A2.5 2.5 0 0 1 9 11.264V8.329l-1-.715-1 .715V7.214c-.1 0-.202.03-.29.093l-2.5 1.786a.5.5 0 1 0 .58.814L7 8.329v2.935A2.5 2.5 0 0 1 5.618 13.5l-1.795.897c-1.12.56-2.07.603-2.741.004-.605-.54-.798-1.463-.838-2.33-.042-.902.076-1.902.24-2.759.164-.852.38-1.606.558-2.012.232-.533.843-1.571 1.579-2.479.37-.456.785-.897 1.216-1.229C4.257 3.27 4.756 3 5.28 3 6.23 3 7 3.77 7 4.72V7.1l.5-.357V1.5A.5.5 0 0 1 8 1m3.21 8.907a.5.5 0 1 0 .58-.814l-2.5-1.786A.5.5 0 0 0 9 7.214V8.33z" />
                        </svg> <br>IMAGENOLOGÍA
                    </button>

                    <button class="btn btn-success mb-2" type="button" title="MOSTRAR BAJA MÉDICA"
                        wire:click="mostrarModalBajaMedica">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-truck" viewBox="0 0 16 16">
                            <path
                                d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12z" />
                        </svg> <br>BAJA MÉDICA
                    </button>

                    <button class="btn btn-warning mb-2" type="button" title="MOSTRAR FISIOTERAPIA"
                        style="background-color: #884ea0; color: white; border: 1px solid #884ea0;"
                        wire:click="mostrarModalFisioterapia">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> <br>FISIOTERAPIA
                    </button>

                    <button class="btn btn-warning mb-2" type="button" title="MOSTRAR INTERNACIÓN"
                        style="background-color: #f39c12; color: white; border: 1px solid #f39c12;"
                        wire:click="mostrarModalInternacion">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-clipboard-check-fill" viewBox="0 0 16 16">
                            <path
                                d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                            <path
                                d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm6.854 7.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                        </svg> <br>INTERNACIÓN
                    </button>

                    <button class="btn btn-warning mb-2" type="button" title="MOSTRAR REFERENCIA"
                        style="background-color: #1f618d; color: white; border: 1px solid #1f618d;"
                        wire:click="mostrarModalReferencia">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                            <path fill-rule="evenodd"
                                d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                        </svg> <br>REFERENCIA
                    </button>

                    <button class="btn btn-warning mb-2" type="button" title="INTERCONSULTA"
                        style="background-color: rgb(196, 169, 21); color: white; border: 1px solid rgb(196, 169, 21);"
                        wire:click="mostrarModalInterconsulta" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5z"/>
                            <path fill-rule="evenodd" d="M15 4.5a.5.5 0 0 0-.5-.5H2.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 1 0 .708-.708L2.707 5H14.5a.5.5 0 0 0 .5-.5z"/>
                        </svg>
                        <br> INTERCONSULTA
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-ver-modal>

<style>
    .bg-diagnostico {
        background-color: white;
        /* gris plomo translúcido */
    }

    .bg-diagnostico th,
    .bg-diagnostico td {
        background-color: transparent;
        /* mantiene transparencia del fondo */
    }

    .historia-clinica-contenedor {
        position: relative;
        background-color: #fff;
        font-size: 16px;
        padding: 30px;
    }

    .watermark::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('/images/confidencialHD.png');
        background-repeat: repeat;
        background-size: 300px;
        background-position: center;
        opacity: 0.1;
        pointer-events: none;
        z-index: 0;
    }

    .watermark>* {
        position: relative;
        z-index: 1;
    }

    .section-box {
        border: 1px solid rgba(0, 0, 0, 0.15);
        background-color: transparent;
        border-radius: 4px;
        margin-bottom: 1.5rem;
    }

    .section-title {
        background-color: #2E86C1;
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 8px 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    window.addEventListener('modalMostrarHistoriaClinica', () => {
        $('#modalVerHistoriaClinica').modal('show');
    });

    window.addEventListener('cerrarModalSubjetivo', () => {
        $('#modalVerHistoriaClinica').modal('hide');
    });

    window.addEventListener('mostrarAlertaCamposVacios', () => {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, completa todos los campos antes de continuar.',
            confirmButtonText: 'OK'
        });
    });
</script>
