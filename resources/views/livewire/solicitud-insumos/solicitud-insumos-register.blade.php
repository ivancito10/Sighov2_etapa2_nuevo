<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">
                REGISTRAR SOLICITUD DE MEDICAMENTOS INSUMOS</h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-12">
            <div class="card p-2 card-comun">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="cabecera text-center">
                            <b> DATOS SOLICITANTE</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                            NOMBRE COMPLETO:</strong>
                        <p class="p-1 p-comun-disabled">{{ $nombreUsuario }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                            </svg>
                            UNIDAD:</strong>
                        <p class="p-1 p-comun-disabled">{{ $unidadUsuario }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong>

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-briefcase-fill" viewBox="0 0 16 16">
                                <path
                                    d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5" />
                                <path
                                    d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85z" />
                            </svg>

                            CARGO:</strong>
                        <p class="p-1 p-comun-disabled">{{ $cargoUsuario }}</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-9 col-md-12 col-sm-12 col-12">
            <div class="card p-1 card-comun">
                <fieldset class="field_estilos">
                    <legend>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em> Ingrese los siguientes datos para el formulario:</em>
                        <span>*</span>
                    </legend>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                            <strong>
                                Nº DE SOLICITUD:
                            </strong>
                            <span class="p-comun-disabled p-2"> {{ $cod_solicitudLiteral }} </span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class='col-lg-6 col-md-6 col-sm-7 col-7'>
                            <label for="id_unidad_recepciona">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                                </svg>
                                SOLICITUD A: <span style="color:#cb4335;"><b>*</b></span> </label>
                            <select wire:model.live="id_unidad_recepciona" id="id_unidad_recepciona"
                                class="form-control input-comun  @error('id_unidad_recepciona') is-invalid @enderror"
                                style="width: 100%;" tabindex="-1" required>
                                <option selected="selected">Selecciona la unidad </option>
                                {{-- @foreach ($unidadesRecepcionan as $unidadRecep) --}}
                                    <option value="76">
                                        FARMACIA</option>
                                        <option value="89">
                                        ALMACEN DE MEDICAMENTOS</option>
                                {{-- @endforeach --}}
                            </select>
                            @error('id_unidad_recepciona')
                                <div class="text-danger">
                                    <b>El campo unidad receptora es obligatorio.</b>
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-5 col-5">
                            <label for="fecha_solicitud">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                </svg>
                                FECHA SOLICITUD:
                                <span style="color:#cb4335;"><b>*</b></span>
                            </label>
                            <input class="form-control input-comun @error('fecha_solicitud')is-invalid @enderror"
                                id="fecha_solicitud" type='date' wire:model.live=fecha_solicitud>
                            @error('fecha_solicitud')
                                <div class="text-danger">
                                    <b>El campo fecha de solicitud es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-7 col-md-6 col-sm-5 col-5 " wire:ignore>
                            <label for="id_unidad_servicio">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                                </svg>
                                UNIDAD O SERVICIO:
                                <span style="color:#cb4335;"><b>*</b></span>
                            </label>

                            <select id="id_unidad_servicio"
                                class="form-control input-comun  @error('id_unidad_servicio') is-invalid @enderror"
                                style="width: 100%;" tabindex="-1" required>
                                <option selected="selected">Selecciona la unidad </option>
                                @foreach ($listadoServicios as $unidadRecep)
                                    <option value="{{ $unidadRecep['id'] }}">
                                        {{ $unidadRecep['nombre'] }}</option>
                                @endforeach
                            </select>


                            @error('id_unidad_servicio')
                                <div class="text-danger">
                                    <b>El campo fecha de solicitud es obligatorio.</b>
                                </div>
                            @enderror
                        </div>

                        @if ($pisoSwitch)
                            <div class="col-lg-4 col-md-6 col-sm-5 col-5">
                                <label for="piso">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                                        <path
                                            d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0M2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z" />
                                    </svg>
                                    PISO:
                                    <span style="color:#cb4335;"><b>*</b></span>
                                </label>
                                <select wire:model.live="piso" id="piso"
                                    class="form-control input-comun  @error('piso') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required>
                                    <option selected="selected">Selecciona la unidad </option>
                                    <option value="SOTANO 1">SOTANO 1 </option>
                                    <option value="SOTANO 2">SOTANO 2 </option>
                                    <option value="PLANTA BAJA">PLANTA BAJA </option>
                                    <option value="PISO 1">PISO 1 </option>
                                    <option value="PISO 2">PISO 2 </option>
                                    <option value="PISO 3">PISO 3 </option>
                                    <option value="PISO 4">PISO 4 </option>
                                    <option value="PISO 4">PISO 4 </option>
                                    <option value="PISO 5">PISO 5 </option>
                                    <option value="PISO 6">PISO 6 </option>
                                    <option value="PISO 7">PISO 7 </option>
                                    <option value="PISO 8">PISO 8 </option>
                                    <option value="PISO 9">PISO 9 </option>
                                    <option value="PISO 10">PISO 10 </option>
                                    <option value="PISO 11">PISO 11</option>
                                    <option value="PISO 12">PISO 12 </option>
                                    <option value="PISO 13">PISO 13</option>
                                    <option value="PISO 14">PISO 14</option>
                                    <option value="PISO 15">PISO 15</option>
                                    <option value="PISO 16">PISO 16</option>

                                </select>
                                @error('piso')
                                    <div class="text-danger">
                                        <b>Debe seleccionar el piso obligatoriamente.</b>
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="motivo">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                                </svg>
                                MOTIVO DE LA SOLICITUD: <span style="color:#cb4335;"><b>*</b></span> </label>
                            <textarea class="form-control input-comun @error('motivo') is-invalid @enderror " id="motivo"
                                wire:model.live="motivo" placeholder="Ingrese le motivo de la solicitud"
                                oninput="this.value = this.value.toUpperCase()">
                            </textarea>
                            @error('motivo')
                                <div class="text-danger">
                                    <b>El motivo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="descripcion">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                                </svg>
                                DESCRIPCIÓN:</label>
                            <textarea class="form-control input-comun" id="descripcion" wire:model.live="descripcion"
                                placeholder="Introduzca la descripción del motivo de la solicitud"
                                oninput="this.value = this.value.toUpperCase()">
                            </textarea>
                        </div>
                    </div>
                </fieldset>
                <br>
                <fieldset class="field_estilos">
                    <legend>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em> Ingrese y registre todos los datos del medicamento:</em>
                        <span>*</span>
                    </legend>

                    <div class="row">
                        <div class='col-lg-6 col-md-12 col-sm-12 col-12' wire:ignore>
                            <label for="id_medicamento">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                                    <path d="M2 2h2v2H2z" />
                                    <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                                    <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                                    <path
                                        d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                                    <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                                </svg>
                                CÓDIGO: <span style="color:#cb4335;"><b>*</b></span> </label>
                            <select wire:model.live="id_medicamento" id="id_medicamento"
                                class="form-control input-comun select2" style="width: 100%;"
                                wire:change="medicamentoSeleccionado" tabindex="-1" required>
                                <option selected="">Selecciona código </option>
                                @foreach ($medicamentos as $medicamento)
                                    <option value="{{ $medicamento->id }}">{{ $medicamento->codigo_medicamento }} -
                                        {{ $medicamento->nombre }} - {{ $medicamento->forma_farmaceutica }} 
                                        ({{ $medicamento->concentracion }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_medicamento')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <strong class="d-block mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-capsule" viewBox="0 0 16 16">
                                    <path
                                        d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                                </svg>
                                MEDICAMENTO O INSUMO:</strong>
                            <p class="p-comun-disabled p-2" style=" border: 1px solid #DBDBDB; font-weight: 500;">
                                {{ $nombreMedicamento }}
                            </p>
                        </div>


                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <label for="formaFarmaceuticaMostrar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-prescription2" viewBox="0 0 16 16">
                                    <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                                    <path
                                        d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                                </svg>
                                FORMA FARMACÉUTICA:</label>
                            <input class="form-control input-comun" id="formaFarmaceuticaMostrar"
                                wire:model="formaFarmaceuticaMostrar" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <label for="concentracionMostrar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-prescription2" viewBox="0 0 16 16">
                                    <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                                    <path
                                        d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                                </svg>
                                CONCENTRACIÓN:</label>
                            <input class="form-control input-comun" id="concentracionMostrar"
                                wire:model="concentracionMostrar" disabled>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-4 col-4">
                            <label for="cantidad">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-sort-numeric-up-alt" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.36 7.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.836 1.973-1.836 1.09 0 2.063.637 2.063 2.688 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98" />
                                    <path
                                        d="M12.438 8.668V14H11.39V9.684h-.051l-1.211.859v-.969l1.262-.906h1.046zM4.5 13.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                                </svg>
                                CANTIDAD: <span style="color:#cb4335;"><b>*</b></span></label>
                            <input class="form-control input-comun @error('cantidad') is-invalid @enderror"
                                id="cantidad" wire:model="cantidad" type="text" inputmode="numeric"
                                maxlength="10" placeholder="Cantidad ingresada"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                            @error('cantidad')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-4">
                            <br>
                            <x-agregar-button wire:click="agregarMedicamento">ADICIONAR</x-agregar-button>
                        </div>
                    </div>
                </fieldset>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                        <div class="cabecera-tabla-azul-oscuro justify-content-center ">
                            <b> MEDICAMENTOS INGRESADOS</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                        <div style="max-height: 200px; overflow-y: auto;">
                            <table class="table tabla-azul-oscuro  ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>CÓDIGO</th>
                                        <th>MEDICAMENTO O INSUMO</th>
                                        <th>FORMA FARMACÉUTICA</th>
                                        <th>CONCENTRACIÓN</th>
                                        <th>CANTIDAD SOLICITADA</th>
                                        <th>OPCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($medicamentosAgregados == [])
                                        <tr style="text-align: center;">
                                            <td colspan="8">Ningún elemento seleccionado</td>
                                        </tr>
                                    @else
                                        @foreach ($medicamentosAgregados as $index => $medicamentoIngresado)
                                            <tr style="text-align:center;">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $medicamentoIngresado['codigo_medicamento'] }}</td>
                                                <td>{{ $medicamentoIngresado['nombre'] }}</td>
                                                <td>{{ $medicamentoIngresado['forma_farmaceutica'] }}</td>
                                                <td>{{ $medicamentoIngresado['concentracion'] }}</td>
                                                <td>{{ $medicamentoIngresado['cantidad'] }}</td>
                                                <td>
                                                    <x-eliminar-button
                                                        wire:click="eliminarMedicamento('{{ $medicamentoIngresado['codigo_medicamento'] }}')"></x-eliminar-button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
                    <div id="cargando" style="display: none;">
                        <x-cargando-dos />
                    </div>
                    <x-cancel-button wire:click="cancelarSolicitud"> CANCELAR</x-cancel-button>
                    <x-save-button onclick="guardarSolicitud()">GUARDAR</x-save-button>
                </div>

            </div>
        </div>
        <div wire:loading wire:target="eliminarMedicamento, agregarMedicamento, cancelarSolicitud, atras">
            <x-cargando-dos />
        </div>
    </div>

    <style>
        .select2-container .select2-selection--single {
            height: 40px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 45px;
        }
    </style>

    <script>
        function guardarSolicitud() {
            Swal.fire({
                title: '¿Registrar Solicitud?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarSolicitud');
                } else {
                    Swal.fire({
                        title: "Cancelado",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }


        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#id_medicamento');
            $(select).select2();

            $(select).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-search__field').focus();
                }, 100);
            });

            $('#id_medicamento').on('change', function(e) {
                var data = $(this).val();
                @this.set('id_medicamento', data);
                Livewire.dispatch('medicamentoSeleccionado');
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#id_unidad_servicio');
            $(select).select2();

            $(select).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-search__field').focus();
                }, 100);
            });

            $('#id_unidad_servicio').on('change', function(e) {
                var data = $(this).val();
                @this.set('id_unidad_servicio', data);
                Livewire.dispatch('servicioSeleccionado');
            });
        });


        document.addEventListener('datosIncompletos', function() {
            Swal.fire({
                title: "Datos incompletos",
                text: "Complete los campos necesarios",
                icon: "warning"
            });
        });

        document.addEventListener('medicamentosVacio', function() {
            Swal.fire({
                title: "Lista de medicamentos vacia",
                text: "Registre al menos un medicamento para realizar la solicitud",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('seleccioneUnidadServicio', function() {
            Swal.fire({
                title: "<b>Seleccione su unidad o servicio</b>",
                text: "Seleccione la unidad o servicio que realiza la solicitud",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });



        document.addEventListener('guardado', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La solicitud fue registrada exitosamente.",
                icon: "success",
                showConfirmButton: false
            });
        });

        document.addEventListener('noGuardado', function() {
            Swal.fire({
                title: "Cancelado!",
                text: "No se registró la solicitud.",
                icon: "error"

            });
        });

        document.addEventListener('refreshSelect2', function() {
            var select = document.querySelector('#id_medicamento');
            @this.call('actualizarListaMedicamentos').then(() => {
                var medicamentos = @this.get('medicamentos');
                $(select).select2('destroy').select2()
            });
        });

        document.addEventListener('insumoAgregadoCorrectamente', function() {
            Swal.fire({
                title: "Insumo agregado correctamente",
                icon: "success",
                timer: 1000,
                timerProgressBar: true,
                showConfirmButton: false

            });
        });

        document.addEventListener('insumoDuplicado', function() {
            Swal.fire({
                title: "Insumo duplicado",
                text: "El insumo ya fue agregado a la lista",
                icon: "warning"
            });
        });

        document.addEventListener('cantidadNoDisponible', function() {
            Swal.fire({
                title: "Stock insuficiente",
                text: "La cantidad solicitada no se encuentra disponible en stock",
                icon: "warning",
                confirmButtonText:"Agregar de todos modos"
            });
        });

        document.addEventListener('seleccioneUnidad', function() {
            Swal.fire({
                title: "Seleccione la unidad a la que solicita insumos o medicamentos",
                text: "Debe seleccionar una unidad receptora de la solicitud",
                icon: "warning"
            });
        });
    </script>
</div>
