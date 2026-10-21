<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center;  flex-wrap: wrap; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center; flex: 1; min-width: 200px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="bi bi-pencil-square"
                viewBox="0 0 16 16">
                <path
                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
            </svg>
            <h6
                style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 10px; font-size: clamp(12px, 2vw, 16px);">
                ACTUALIZACIÓN DE DATOS - SEGURO SOCIAL UNIVERSITARIO
            </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left:auto; padding:5px;">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="col-12 mb-3 mt-2 ">
        <div class="alert d-flex align-items-center shadow-sm rounded-3 p-2" role="alert"
            style="background-color: #eee8a9;color:black">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                    class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg>

                <b>Le solicitamos verificar cuidadosamente la actualización de sus datos personales.</b>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-2">
            <div class="card-comun p-2">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="cabecera justify-content-center d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                            class="bi bi-person-fill mr-2" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                        </svg>
                        <h5 style="color:#0077bd"><b>MIS DATOS PERSONALES</b></h5>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="nombres">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                            NOMBRES:</label>
                        <input for="nombres" type="text" @if ($deshabilitarEspacios) disabled @endif
                            class="form-control @error('nombres') is-invalid @enderror input-comun"
                            wire:model.live="nombres" wire:input="actualizarMatricula"
                            style=" text-transform: uppercase;" placeholder="Ejemplo: JUAN PEDRITO" />
                        @error('nombres')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="p_apellido">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                            PRIMER APELLIDO:</label>
                        <input for="p_apellido" type="text" class="form-control  input-comun"
                            placeholder=" Ejemplo: PEREZ" wire:model.live="p_apellido"
                            style=" text-transform: uppercase;" @if ($deshabilitarEspacios) disabled @endif
                            wire:change="actualizarMatricula" />


                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="s_apellido">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                            SEGUNDO APELLIDO:</label>
                        <input for="s_apellido" type="text" class="form-control input-comun"
                            placeholder=" Ejemplo: PEREZ" wire:model.live="s_apellido"
                            style=" text-transform: uppercase;" @if ($deshabilitarEspacios) disabled @endif
                            wire:change="actualizarMatricula" />

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                        <label id="carnet">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                <path
                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                            </svg>
                            CARNET IDENTIDAD:</label>
                        <input for="carnet" type="number"
                            class="form-control @error('carnet') is-invalid @enderror input-comun" wire:model.live="ci"
                            placeholder=" Ejemplo: 1234567" @if ($deshabilitarEspacios) disabled @endif />
                        @error('carnet')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                        <label id="complemento">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                <path
                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                            </svg>
                            COMPLEMENTO:</label>
                        <input for="complemento" type="text" class="form-control  input-comun"
                            style=" text-transform: uppercase;" placeholder=" Ejemplo: B1"
                            wire:model.live="complemento" @if ($deshabilitarEspacios) disabled @endif />

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                        <label id="fecha_nacimiento">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-cake2-fill" viewBox="0 0 16 16">
                                <path
                                    d="m2.899.804.595-.792.598.79A.747.747 0 0 1 4 1.806v4.886q-.532-.09-1-.201V1.813a.747.747 0 0 1-.1-1.01ZM13 1.806v4.685a15 15 0 0 1-1 .201v-4.88a.747.747 0 0 1-.1-1.007l.595-.792.598.79A.746.746 0 0 1 13 1.806m-3 0a.746.746 0 0 0 .092-1.004l-.598-.79-.595.792A.747.747 0 0 0 9 1.813v5.17q.512-.02 1-.055zm-3 0v5.176q-.512-.018-1-.054V1.813a.747.747 0 0 1-.1-1.01l.595-.79.598.789A.747.747 0 0 1 7 1.806" />
                                <path
                                    d="M4.5 6.988V4.226a23 23 0 0 1 1-.114V7.16c0 .131.101.24.232.25l.231.017q.498.037 1.02.055l.258.01a.25.25 0 0 0 .26-.25V4.003a29 29 0 0 1 1 0V7.24a.25.25 0 0 0 .258.25l.259-.009q.52-.018 1.019-.055l.231-.017a.25.25 0 0 0 .232-.25V4.112q.518.047 1 .114v2.762a.25.25 0 0 0 .292.246l.291-.049q.547-.091 1.033-.208l.192-.046a.25.25 0 0 0 .192-.243V4.621c.672.184 1.251.409 1.677.678.415.261.823.655.823 1.2V13.5c0 .546-.408.94-.823 1.201-.44.278-1.043.51-1.745.696-1.41.376-3.33.603-5.432.603s-4.022-.227-5.432-.603c-.702-.187-1.305-.418-1.745-.696C.408 14.44 0 14.046 0 13.5v-7c0-.546.408-.94.823-1.201.426-.269 1.005-.494 1.677-.678v2.067c0 .116.08.216.192.243l.192.046q.486.116 1.033.208l.292.05a.25.25 0 0 0 .291-.247M1 8.82v1.659a1.935 1.935 0 0 0 2.298.43.935.935 0 0 1 1.08.175l.348.349a2 2 0 0 0 2.615.185l.059-.044a1 1 0 0 1 1.2 0l.06.044a2 2 0 0 0 2.613-.185l.348-.348a.94.94 0 0 1 1.082-.175c.781.39 1.718.208 2.297-.426V8.833l-.68.907a.94.94 0 0 1-1.17.276 1.94 1.94 0 0 0-2.236.363l-.348.348a1 1 0 0 1-1.307.092l-.06-.044a2 2 0 0 0-2.399 0l-.06.044a1 1 0 0 1-1.306-.092l-.35-.35a1.935 1.935 0 0 0-2.233-.362.935.935 0 0 1-1.168-.277z" />
                            </svg>
                            FECHA NACIMIENTO:</label>
                        <input for="" type="date"
                            class="form-control @error('fecha_nacimiento') is-invalid @enderror input-comun"
                            wire:model.live="fecha_nacimiento" max="{{ now()->toDateString() }}"
                            @if ($deshabilitarEspacios) disabled @endif wire:change="actualizarMatricula" />
                        @error('fecha_nacimiento')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                        <label id="sexo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-gender-ambiguous" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z" />
                            </svg>
                            SEXO:</label>
                        <select wire:model.live="sexo" id="sexo"
                            class="select2 form-control @error('sexo') is-invalid @enderror input-comun"
                            @if ($deshabilitarEspacios) disabled @endif wire:change="actualizarMatricula">
                            <option selected=" ">Seleccione un
                                tipo.. </option>
                            <option value="F">FEMENINO</option>
                            <option value="M">MASCULINO</option>
                        </select>
                        @error('sexo')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                        <label id="matricula">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-postcard-fill" viewBox="0 0 16 16">
                                <path d="M11 8h2V6h-2z" />
                                <path
                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5.5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0zM2 5.5a.5.5 0 0 0 .5.5H6a.5.5 0 0 0 0-1H2.5a.5.5 0 0 0-.5.5M2.5 7a.5.5 0 0 0 0 1H6a.5.5 0 0 0 0-1zM2 9.5a.5.5 0 0 0 .5.5H6a.5.5 0 0 0 0-1H2.5a.5.5 0 0 0-.5.5m8-4v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5" />
                            </svg>
                            MATRICULA:</label>
                        <input for="matricula" type="varchar" disabled
                            class="form-control @error('matricula') is-invalid @enderror input-comun"
                            wire:model.live="matricula_seguro" />
                        @error('matricula')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    @if ($deshabilitarEspacios && !$errorAsuss)
                        <div class="col-12 mb-3 mt-2">
                            <div class="alert  d-flex align-items-center shadow-sm rounded-3 p-3" role="alert"
                                style="background-color: #e6f4f1;color:black">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                        fill="currentColor" class="bi bi-shield-fill-check" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m2.146 5.146a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793z" />
                                    </svg>
                                    <b>¡Su información personal está actualizada y
                                        verificada con el SEGIP!</b>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errorAsuss)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                            <div class="alert alert-danger p-2"> <b>¡Ocurrio un error! </b>
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z" />
                                </svg>
                                <br>
                                Hubo un problema con la plataforma de verificación de datos del ASUSS.
                                <br>
                                Recarga la página e inténtalo nuevamente más tarde.
                            </div>
                        </div>
                    @endif

                    @if (!$deshabilitarEspacios && !$errorAsuss)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right mt-2">
                            <x-guardar-button wire:click="verificarDatosIntroducidos">ACTUALIZAR DATOS
                                PERSONALES</x-guardar-button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card-comun p-2">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class=" cabecera justify-content-center d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-person-fill-add mr-2" viewBox="0 0 16 16">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                        </svg>
                        <h5 style="color:#0077bd"><b>MIS DATOS REFERENCIALES</b></h5>
                    </div>
                </div>
                <br>
                @if ($referencialesActualizados)
                    <div class="col-12 mb-3 mt-2">
                        <div class="alert  d-flex align-items-center shadow-sm rounded-3 p-3" role="alert"
                            style="background-color: #e6f4f1;color:black">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    fill="currentColor" class="bi bi-shield-fill-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m2.146 5.146a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793z" />
                                </svg>
                                Sus datos referenciales fueron actualizados por
                                última vez en fecha: <b>{{ $fechaActualizacionDatosReferenciales }}</b> a horas:
                                <b>{{ $horaActualizacionDatosReferenciales }}</b>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="nombres">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                            </svg>
                            TELEFONO PERSONAL:</label>
                        <input for="nombres" type="text" class="form-control input-comun"
                            placeholder=" Ejemplo: 71212121" wire:model.live="telefono" />
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-telephone-plus-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877zM12.5 1a.5.5 0 0 1 .5.5V3h1.5a.5.5 0 0 1 0 1H13v1.5a.5.5 0 0 1-1 0V4h-1.5a.5.5 0 0 1 0-1H12V1.5a.5.5 0 0 1 .5-.5" />
                            </svg>
                            TELEFONO DE REFERENCIA:</label>
                        <input for="nombres" type="text" class="form-control input-comun"
                            placeholder="Ejemplo: 71212121" wire:model.live="telefono_referencia" />
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="nombres">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                <path
                                    d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
                            </svg>
                            NOMBRE DE REFERENCIA EN CASO DE EMERGENCIA:</label>
                        <input for="nombres" type="text" class="form-control input-comun"
                            style=" text-transform: uppercase;" placeholder=" Ejemplo: JUAN PEREZ "
                            wire:model.live="nombre_referencia" />
                    </div>
                    <!--  <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="nombres">DIRECCIÓN:</label>
                        <input for="nombres" type="text" class="form-control input-comun" />
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="nombres">ZONA:</label>
                        <input for="nombres" type="text" class="form-control input-comun" />
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label id="nombres">NRO.DE CASA:</label>
                        <input for="nombres" type="text" class="form-control input-comun" />
                    </div> -->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right mt-2">
                        <x-guardar-button wire:click="actualizarSoloDatosReferenciales">ACTUALIZAR DATOS
                            REFERENCIALES</x-guardar-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
