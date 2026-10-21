<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;"> SOLICITUD DE MEDICAMENTOS A ENTREGAR
                - FARMACIA</h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-12 ">
            <div class="card-comun p-1 h-100">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-8">
                        <div class="cabecera">
                            <b>SOLICITADO POR:</b>
                        </div>
                    </div>
                </div>
                <div style="height: 0.5rem;"></div>
                <div class="row p-2 ">
                    <table class="table tabla-ploma">
                        <tr>
                            <td>
                                <strong><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                    </svg>NOMBRE:</strong>
                            </td>
                            <td>
                                {{ $usuarioSolicita }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong> <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                                    </svg>
                                    FECHA SOLICITUD:</strong>
                            </td>
                            <td>
                                {{ $fechaCreado }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                                    </svg>
                                    UNIDAD:</strong>
                            </td>
                            <td>
                                {{ $unidadSolicitante }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-briefcase-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5" />
                                        <path
                                            d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85z" />
                                    </svg>
                                    CARGO:</strong>
                            </td>
                            <td>
                                {{ $cargoSolicitante }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="card-comun p-1 h-100">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-8">
                        <div class="cabecera">
                            <b>APROBADO POR:</b>
                        </div>
                    </div>
                </div>
                <div style="height: 0.5rem;"></div>
                <div class="row  p-2">
                    <table class="table tabla-ploma">
                        <tr>
                            <td>
                                <strong><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                    </svg>NOMBRE:</strong>
                            </td>
                            <td>
                                {{ $usuarioAprueba }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong> <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                                    </svg>
                                    FECHA SOLICITUD:</strong>
                            </td>
                            <td>
                                {{ $fecha_aprobacion }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                                    </svg>
                                    UNIDAD:</strong>
                            </td>
                            <td>
                                {{ $unidadAprobado }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-briefcase-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5" />
                                        <path
                                            d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85z" />
                                    </svg>
                                    CARGO:</strong>
                            </td>
                            <td>
                                {{ $cargoAprobado }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 0.5rem;"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <strong>
                        Ingrese los siguientes datos</strong>
                    <span><b>*</b></span>
                </legend>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style ="text-align:right;">
                        <span> <b> SOLICITUD:</b> </span>
                        @if ($estadoValidacion == 'PENDIENTE')
                            <span class="h3 parpadeo" style="color:orange;"> <b>PENDIENTE </b></span>
                        @elseif($estadoValidacion == 'RECHAZADO')
                            <span class="h3" style="color: #cb4335;"> <b>RECHAZADO </b></span>
                        @elseif($estadoValidacion == 'APROBADO')
                            <span class="h3" style="color: #1d8348 ;"> <b>APROBADO </b></span>
                        @elseif($estadoValidacion == 'ENTREGADO')
                            <span class="h3" style="color: #34495e;"> <b>ENTREGADO </b></span>
                        @endif

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-4">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-qr-code" viewBox="0 0 16 16">
                                <path d="M2 2h2v2H2z" />
                                <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                                <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                                <path
                                    d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                                <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                            </svg>
                            CÓDIGO:
                        </strong>
                        <p class="p-comun-disabled p-1">
                            {{ $cod_solicitud }}
                        </p>

                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-4 col-4">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                            </svg>
                            FECHA SOLICITUD:
                        </strong>
                        <p class="p-comun-disabled p-1">{{ $fecha_solicitud }} </p>
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-4 col-4">
                        <strong> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                            </svg>
                            UNIDAD RECEPTORA:
                        </strong>
                        <p class="p-comun-disabled p-1">{{ $unidad_receptora }} </p>
                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                            </svg>
                            MOTIVO DE LA SOLICITUD:
                        </strong>
                        <p class="p-comun-disabled p-1">{{ $motivo }} </p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                            </svg>
                            DESCRIPCIÓN:
                        </strong>
                        <p class="p-comun-disabled p-1">{{ $descripcion }} </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="cabecera-tabla-azul-oscuro">
                            <b> MEDICAMENTOS INGRESADOS</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                        <div style="max-height: 200px; overflow-y: auto;">
                            <table class="table tabla-azul-oscuro">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>CÓDIGO MEDICAMENTO</th>
                                        <th>MEDICAMENTO</th>
                                        <th>FORMA FARMACÉUTICA</th>
                                        <th>CONCENTRACIÓN</th>
                                        <th>STOCK ACTUAL</th>
                                        <th>CANTIDAD SOLICITADA</th>
                                        <th>CANTIDAD ENTREGADA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($medicamentosAgregados != [])
                                        @foreach ($medicamentosAgregados as $index => $med)
                                            <tr style="text-align:center;">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $med['codigo_medicamento'] }}</td>
                                                <td>{{ $med['nombre'] }}</td>
                                                <td>{{ $med['forma_farmaceutica'] }}</td>
                                                <td>{{ $med['concentracion'] }}</td>
                                                <td>{{ $med['stock_actual'] }}
                                                </td>
                                                <td>{{ $med['cantidad'] }}
                                                </td>
                                                <td>
                                                    @if ($med['stock_actual'] == 0)
                                                        <input type="hidden"
                                                            wire:model="medicamentosAgregados.{{ $index }}.cantidad" />
                                                        <span class="badge badge-danger">STOCK INSUFICIENTE</span>
                                                    @else
                                                        <input type="number" min="0"
                                                            class="form-control text-center"
                                                            wire:model="medicamentosAgregados.{{ $index }}.cantidad" />

                                                        @error("medicamentosAgregados.$index.cantidad")
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </fieldset>

            <div class="modal-footer" style="border: none; background-color: white;">
                <x-cancel-button wire:click="cancelarEntrega"> CANCELAR</x-cancel-button>
                <x-save-button onclick="guardarEdicionSolicitud()">GUARDAR</x-save-button>
            </div>
        </div>

    </div>



    <style>
        @keyframes parpadeo {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .parpadeo {
            animation: parpadeo 1s infinite;
        }
    </style>

    <script>
        function guardarEdicionSolicitud() {
            Swal.fire({
                title: '¿Registrar Entrega?',
                text: "Se guardarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardarEntrega');
                } else {
                    Swal.fire({
                        title: "Cancelado",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }


        document.addEventListener('entregadoExitosamente', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La entrega fue registrada exitosamente.",
                icon: "success",
                showConfirmButton: false
            });
        });

        document.addEventListener('noEntregadoExitosamente', function() {
            Swal.fire({
                title: "Cancelado!",
                text: "No se registró la entrega.",
                icon: "error"

            });
        });
    </script>
</div>
