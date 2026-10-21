<div>
    <link href="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/styles/choices.min.css" rel="stylesheet">

    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">
                FARMACIA - REGISTRAR REINGRESO DE MEDICAMENTOS</h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>

    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <fieldset style="border: 1px solid #2471a3; border-radius: 5px; padding: 15px; position: relative;">
                <legend
                    style="color: #2471a3; font-size: 1.25rem; padding: 0; margin-left: 10px; display: inline-block; width: auto; font-weight: bold;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em>Ingrese los datos del reingreso:</em>
                    <span style="color: #cb4335;"><b>*</b></span>
                </legend>
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <label for="nro_comprobante">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-qr-code" viewBox="0 0 16 16">
                                <path d="M2 2h2v2H2z" />
                                <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                                <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                                <path
                                    d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                                <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                            </svg>
                            CÓDIGO REINGRESO: <span style="color:#cb4335;"><b>*</b></span></label>
                        <input class="form-control input-comun @error('nro_comprobante') is-invalid @enderror"
                            id="nro_comprobante" wire:model="nro_comprobante" type="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
                            placeholder="Ingrese el codigo de reingreso..." disabled>
                        @error('nro_comprobante')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-12" wire:ignore>
                        <label for="unidad">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                            </svg>
                            UNIDAD: <span style="color:#cb4335;"><b>*</b></span>
                        </label>

                        <select class="form-control  @error('unidad') is-invalid @enderror" id="unidad">
                            <option selected="selected" value="">Selecciona la unidad</option>
                            @foreach ($unidades as $unidadItem)
                                <option value="{{ $unidadItem['id'] }}">{{ $unidadItem['nombre'] }}</option>
                            @endforeach
                        </select>

                        @error('unidad')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <label for="customSwitchOrigenReingreso">ORIGEN REINGRESO:</label>
                        <div class="col-sm-6 d-flex align-items-center">
                            <strong style="color:#006400">UNIDAD</strong>
                            <div class="custom-control custom-switch custom-switch-off-success custom-switch-on-blue">
                                <input wire:model.live="origenReingreso" id="customSwitchOrigenReingreso"
                                    type="checkbox" class="custom-control-input">
                                <label class="custom-control-label" for="customSwitchOrigenReingreso"
                                    style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                            </div>
                            <strong style="color:#293696">PISO</strong>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <label for="fecha_reingreso">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-calendar2-plus-fill" viewBox="0 0 16 16">
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 3.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5H2.545c-.3 0-.545.224-.545.5m6.5 5a.5.5 0 0 0-1 0V10H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V11H10a.5.5 0 0 0 0-1H8.5z" />
                            </svg>
                            FECHA REINGRESO: <span style="color:#cb4335;"><b>*</b></span></label>
                        <input class="form-control input-comun @error('fecha_reingreso') is-invalid @enderror"
                            id="fecha_reingreso" wire:model="fecha_reingreso" type="date"
                            max="{{ now()->toDateString() }}">
                        @error('fecha_reingreso')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>

                    @if ($origenReingreso)
                        <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                            <label for="nro_habitacion">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16">
                                    <path
                                        d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                                </svg>
                                Nº DE HABITACIÓN: <span style="color:#cb4335;"><b>*</b></span></label>
                            <input class="form-control input-comun @error('nro_habitacion') is-invalid @enderror"
                                id="nro_habitacion" wire:model="nro_habitacion" maxlength="5"
                                oninput="this.value = this.value.toUpperCase()"
                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').slice(0,6);"
                                placeholder="Ingrese el nro de habitación..">
                            @error('nro_habitacion')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    @endif

                    @if ($origenReingreso)
                        <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                            <label for="piso">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16">
                                    <path
                                        d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                                </svg>
                                Nº DE PISO: <span style="color:#cb4335;"><b>*</b></span></label>
                            <input class="form-control input-comun @error('piso') is-invalid @enderror" id="piso"
                                oninput="this.value = this.value.toUpperCase()" wire:model="piso" maxlength="5"
                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').slice(0,6);"
                                placeholder="Ingrese el nro de piso ..">
                            @error('piso')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    @endif


                    <div class="col-lg-12 col-md-12 col-sm-8 col-12">
                        <label for="motivo_reingreso">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                            </svg>
                            MOTIVO DE REINGRESO:  <span style="color:#cb4335;"><b>*</b></span></label>
                        <input class="form-control input-comun @error('motivo_reingreso') is-invalid @enderror"
                            id="motivo_reingreso" wire:model="motivo_reingreso"
                            placeholder="Ingrese el motivo del reingreso ..."
                            oninput="this.value = this.value.toUpperCase()">
                        @error('motivo_reingreso')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label for="observacion">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                            </svg>
                            OBSERVACIONES:</label>
                        <input class="form-control input-comun @error('observacion') is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase()" id="observacion" wire:model="observacion"
                            placeholder="Ingrese las observacion ..">
                        @error('observacion')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>

                </div>
            </fieldset>
        </div>
    </div>
    @if ($origenReingreso)
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <fieldset style="border: 1px solid #2471a3; border-radius: 5px; padding: 15px; position: relative;">
                    <legend
                        style="color: #2471a3; font-size: 1.25rem; padding: 0; margin-left: 10px; display: inline-block; width: auto; font-weight: bold;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em>Ingrese datos del asegurado a buscar:</em>
                        <span style="color: #cb4335;"><b>*</b></span>
                    </legend>

                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-12">
                            <div
                                style="display: flex; justify-content: space-between; gap: 5px; flex-wrap: wrap; width: 100%;">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="CONVENIO"
                                        wire:model.live="tipo_reingreso" id="convenioCheck">
                                    <label class="form-check-label" for="convenioCheck">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                        </svg>
                                        CONVENIO</label>
                                    @error('tipo_reingreso')
                                        <div class="text-danger ">
                                            <b> {{ $message }}</b>
                                        </div>
                                    @enderror

                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="INTERIOR"
                                        wire:model.live="tipo_reingreso" id="interiorCheck">
                                    <label class="form-check-label" for="interiorCheck">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-walking" viewBox="0 0 16 16">
                                            <path
                                                d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z" />
                                            <path
                                                d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.531-.22Z" />
                                        </svg>
                                        INTERIOR</label>
                                    @error('tipo_reingreso')
                                        <div class="text-danger ">
                                            <b> {{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="SSUE"
                                        wire:model.live="tipo_reingreso" id="ssueCheck">
                                    <label class="form-check-label" for="ssueCheck">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-backpack-fill" viewBox="0 0 16 16">
                                            <path d="M5 13v-3h4v.5a.5.5 0 0 0 1 0V10h1v3z" />
                                            <path
                                                d="M6 2v.341C3.67 3.165 2 5.388 2 8v5.5A2.5 2.5 0 0 0 4.5 16h7a2.5 2.5 0 0 0 2.5-2.5V8a6 6 0 0 0-4-5.659V2a2 2 0 1 0-4 0m2-1a1 1 0 0 1 1 1v.083a6 6 0 0 0-2 0V2a1 1 0 0 1 1-1m0 3a4 4 0 0 1 3.96 3.43.5.5 0 1 1-.99.14 3 3 0 0 0-5.94 0 .5.5 0 1 1-.99-.14A4 4 0 0 1 8 4M4.5 9h7a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5" />
                                        </svg>
                                        SSUE</label>
                                    @error('tipo_reingreso')
                                        <div class="text-danger ">
                                            <b> {{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="ONCOLOGÍA"
                                        wire:model.live="tipo_reingreso" id="oncologiaCheck">
                                    <label class="form-check-label" for="oncologiaCheck">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                        </svg>
                                        ONCOLOGÍA</label>
                                    @error('tipo_reingreso')
                                        <div class="text-danger ">
                                            <b> {{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="OTROS"
                                        wire:model.live="tipo_reingreso" id="otroCheck">
                                    <label class="form-check-label" for="otroCheck">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-clipboard2-plus-fill"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5" />
                                            <path
                                                d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5M8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0" />
                                        </svg>
                                        OTROS</label>
                                    @error('tipo_reingreso')
                                        <div class="text-danger ">
                                            <b> {{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                            @livewire('farmacia.farmacia.reingreso.buscar-personas-component')
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-12">
                            <table class="table tabla-ploma">
                                <tr>
                                    <td>
                                        <strong>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                            </svg>
                                            NOMBRE ASEGURADO:
                                        </strong>
                                    </td>
                                    <td colspan="3">
                                        {{ $nombreCompleto ?: 'BUSCAR ASEGURADO' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-person-vcard-fill"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                                            </svg>
                                            CARNET IDENTIDAD:
                                        </strong>
                                    </td>
                                    <td>
                                        {{ $ci ?: 'BUSCAR ASEGURADO' }}
                                    </td>
                                    <td>
                                        <strong>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-postcard-fill" viewBox="0 0 16 16">
                                                <path d="M11 8h2V6h-2z" />
                                                <path
                                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5.5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0zM2 5.5a.5.5 0 0 0 .5.5H6a.5.5 0 0 0 0-1H2.5a.5.5 0 0 0-.5.5M2.5 7a.5.5 0 0 0 0 1H6a.5.5 0 0 0 0-1zM2 9.5a.5.5 0 0 0 .5.5H6a.5.5 0 0 0 0-1H2.5a.5.5 0 0 0-.5.5m8-4v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5" />
                                            </svg>
                                            MATRICULA:
                                        </strong>
                                    </td>
                                    <td>
                                        {{ $matricula ?: 'BUSCAR ASEGURADO' }}
                                    </td>
                                </tr>

                            </table>
                        </div>

                    </div>

                </fieldset>
            </div>
        </div>
    @endif
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
            <fieldset style="border: 1px solid #2471a3; border-radius: 5px; padding: 15px; position: relative;">
                <legend
                    style="color: #2471a3; font-size: 1.25rem; padding: 0; margin-left: 10px; display: inline-block; width: auto; font-weight: bold;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>

                    <em>Ingrese datos de los medicamentos a reingresar:</em>
                    <span style="color: #cb4335;"><b>*</b></span>
                </legend>

                <div class="row">
                    <div class='col-lg-4 col-md-12 col-sm-12 col-12' wire:ignore>
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
                            CÓDIGO: </label>
                        <select wire:model.live="id_medicamento" id="id_medicamento"
                            class="form-control input-comun select2" style="width: 100%;"
                            wire:change="muestraMedicamentoSolicitado" tabindex="-1" required>
                            <option selected="selected">Selecciona código .. </option>
                            @foreach ($medicamentos as $medicamento)
                                <option value="{{ $medicamento->id }}">{{ $medicamento->codigo_medicamento }} -
                                    {{ $medicamento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-capsule" viewBox="0 0 16 16">
                                <path
                                    d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                            </svg>
                            MEDICAMENTO O INSUMO</strong>

                        <p class="p-2 p-comun-disabled">{{ $nombreMedicamento ?: 'SIN SELECCIONAR' }} </p>

                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                        <label for="formaFarmaceutica">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-prescription2" viewBox="0 0 16 16">
                                <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                                <path
                                    d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                            </svg>
                            FORMA FARMACÉUTICA:</label>
                        <input class="form-control input-comun @error('formaFarmaceutica') is-invalid @enderror"
                            id="formaFarmaceutica" wire:model="formaFarmaceutica" placeholder="SIN SELECCIONAR"
                            disabled>
                        @error('formaFarmaceutica')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                        <label for="concentracion">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-beaker-fill" viewBox="0 0 16 16">
                                <path
                                    d="M15.575.006a.5.5 0 0 1 .327.79l-.048.058-.122.12A2.5 2.5 0 0 0 15 2.743V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2.742a2.5 2.5 0 0 0-.566-1.584L.268.975.146.854A.5.5 0 0 1 .5 0h15zM11.5 13a.5.5 0 1 0 0 1H13v-1zm-2-2a.5.5 0 0 0 0 1H13v-1zm2-2a.5.5 0 1 0 0 1H13V9zm-2-2a.5.5 0 1 0 0 1H13V7zm2-2a.5.5 0 1 0 0 1H13V5zm-2-2a.5.5 0 1 0 0 1H13V3z" />
                            </svg>
                            CONCENTRACIÓN:</label>
                        <input class="form-control input-comun @error('concentracion') is-invalid @enderror"
                            id="concentracion" wire:model="concentracion" placeholder="SIN SELECCIONAR" disabled>
                        @error('concentracion')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                        <label for="fecha_vencimiento">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-calendar2-heart-fill" viewBox="0 0 16 16">
                                <path
                                    d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zm-2 4v-1c0-.276.244-.5.545-.5h10.91c.3 0 .545.224.545.5v1c0 .276-.244.5-.546.5H2.545C2.245 5 2 4.776 2 4.5m6 3.493c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132" />
                            </svg>
                            FECHA VENCIMIENTO:</label>
                        <input class="form-control input-comun @error('fecha_vencimiento') is-invalid @enderror"
                            id="fecha_vencimiento" wire:model="fecha_vencimiento" type="date">
                        @error('fecha_vencimiento')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                        <label for="cantidad">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-sort-numeric-up-alt" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M11.36 7.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.836 1.973-1.836 1.09 0 2.063.637 2.063 2.688 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98" />
                                <path
                                    d="M12.438 8.668V14H11.39V9.684h-.051l-1.211.859v-.969l1.262-.906h1.046zM4.5 13.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                            </svg>
                            CANTIDAD:</label>
                        <input class="form-control input-comun @error('cantidad') is-invalid @enderror"
                            id="cantidad" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
                            type="number" wire:model="cantidad" placeholder="Ingrese cantidad ...">
                        @error('cantidad')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right pt-2">
                        <x-agregar-button wire:click="agregarMedicamento">AGREGAR</x-agregar-button>
                    </div>
                </div>
            </fieldset>
            <br>

            <div class="row">
                <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                    <div class="cabecera-tabla-azul-oscuro justify-content-center">
                        <b> MEDICAMENTOS INGRESADOS</b>
                    </div>
                </div>
                <div style="height: 0.5rem;"></div>
                <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                    <div class="table-responsive">
                        <table class="table tabla-azul-oscuro">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CÓDIGO</th>
                                    <th>MEDICAMENTO</th>
                                    <th>FORMA FARMACÉUTICA</th>
                                    <th>CONCENTRACIÓN</th>
                                    <th>FECHA VENCIMIENTO</th>
                                    <th>CANTIDAD</th>
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
                                            <td>
                                                {{ \Carbon\Carbon::parse($medicamentoIngresado['fecha_vencimiento'])->Format('d /m/ Y') }}
                                            </td>
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

            <br>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
                        <x-cancel-button wire:click="cancelar">CANCELAR</x-cancel-button>
                        <x-save-button onclick="funcionReingreso()" wire:loading.attr="disabled"
                            id="save-button">GUARDAR</x-save-button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    //Select con choice
    /*  document.addEventListener('DOMContentLoaded', function() {
        const tipoProcesoSelect = new Choices('#id_medicamento', {
            searchEnabled: true,
            itemSelectText: 'Presiona para seleccionar',
        });

        const choicesInner = document.querySelector('#id_medicamento').closest('.choices').querySelector(
            '.choices__inner');
        choicesInner.style.height = '30px';
    });
 */


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
            Livewire.dispatch('muestraMedicamentoSolicitado');
        });
    });


    //Select2 para unidad
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#unidad');
        $(select).select2();

        $(select).on('select2:open', function() {
            setTimeout(function() {
                document.querySelector('.select2-search__field').focus();
            }, 100);
        });

        $('#unidad').on('change', function(e) {
            var data = $(this).val();
            @this.set('unidad', data);
            Livewire.dispatch('muestraMedicamentoSolicitado');
        });
    });



    function funcionReingreso() {
        Swal.fire({
            title: '¿Registrar Ingreso?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('crearReingreso');
                document.getElementById('cargando-guardar-reingreso');
                //document.getElementById('cargando-guardar-reingreso').style.display = 'block';
            } else {
                Swal.fire({
                    title: "<b>Cancelado</b>",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }

    document.addEventListener('registroReingresoExitoso', function() {
        Swal.fire({
            title: "<b>Reingreso registrado exitosamente</b>",
            text: "Los datos fueron registrados con éxito",
            icon: "success",
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('registroReingresoNoExitoso', function() {
        Swal.fire({
            title: "<b>Falló el registro del reingreso</b>",
            text: "Verifique los datos ingresados e intente nuevamente",
            icon: "error",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('medicamentoAgregado', function() {
        Swal.fire({
            title: "Medicamento agregado",
            text: "El medicamento fue agregado exitosamente",
            icon: "success",
            showConfirmButton: false,
            timer: 1000
        });
    });

    document.addEventListener('datosIncompletos', function() {
        Swal.fire({
            title: "Datos incompletos",
            text: "Complete los campos necesarios",
            icon: "warning"
        });
    });
    document.addEventListener('medicamentoDuplicado', function() {
        Swal.fire({
            title: "Medicamento duplicado",
            text: "El medicamento ya fue agregado a la lista",
            icon: "warning"
        });
    });
    document.addEventListener('ingresePersona', function() {
        Swal.fire({
            title: "No olvide seleccionar al asegurado",
            text: "Debe presionar el boton verde que dice: BUSCAR ASEGURADO Y seleccionar el asegurado que corresponda",
            icon: "error"

        });
    });

    document.addEventListener('ingreseMedicamentosAReingresar', function() {
        Swal.fire({
            title: "No olvide agregar medicamentos",
            text: "La lista de medicamentos a reingresar se encuentra vacía",
            icon: "error"
        });
    });

    document.addEventListener('agregarUnidad', function() {
        Swal.fire({
            title: "No olvide seleccionar la unidad",
            text: "Debe seleccionar la unidad en el primer campo del formulario",
            icon: "error"

        });
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error de registro",
            text: "Vuelva a intentarlo o comuniquese con la unidad de Sistemas",
            icon: "error"

        });
    });

    document.addEventListener('sinRegistroPrevio', function() {
        Swal.fire({
            title: "No hay un registro previo",
            text: "El medicamento no fue añadido previamente, comuniquese con Almacen de medicamentos.",
            icon: "error"
        });
    });
</script>
