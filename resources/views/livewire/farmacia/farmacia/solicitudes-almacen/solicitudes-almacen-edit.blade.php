<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">

            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;"> FARMACIA - MODIFICACIÓN DE
                SOLICITUDES A
                ALMACEN DE MEDICAMENTOS</h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card p-2 card-comun h-100 ">
                <div class="cabecera text-center">
                    <b>USUARIO SOLICITANTE</b>
                </div>
                <div style="height: 0.5rem;"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <table class="table tabla-ploma">
                            <tr>
                                <td>
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                        </svg>
                                        NOMBRE COMPLETO:</strong>
                                </td>
                                <td>
                                    {{ $usuarioSolicita }}
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
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card p-2 card-comun h-100">
                <fieldset class="field_estilos h-100">
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
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <strong>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                </svg>
                                FECHA SOLICITUD:</strong>
                            <p class="p-comun-disabled p-1">
                                {{ $fecha_solicitud }} </p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <strong> Nº DE SOLICITUD:</strong>
                            <p class="p-comun-disabled p-1">{{ $cod_solicitud }} </p>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label for="descripcion">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                                </svg>
                                DESCRIPCIÓN:</label>
                            <textarea class="form-control input-comun" id="descripcion" wire:model.live="descripcion" rows="3"
                                placeholder="Introduzca la descripción del motivo de la solicitud" oninput="this.value = this.value.toUpperCase()">
                            </textarea>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <br>
    <div class="card p-2 card-comun">
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
                    Ingrese y registre todos los datos del medicamento.</strong>
                <span><b>*</b></span>
            </legend>
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 col-12" wire:ignore>
                    <label for="id_medicamento">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-qr-code" viewBox="0 0 16 16">
                            <path d="M2 2h2v2H2z" />
                            <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                            <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                            <path
                                d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                            <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                        </svg>
                        CÓDIGO: </label>
                    <select id="id_medicamento" class="form-control input-comun select2" style="width: 100%;"
                        wire:model="id_medicamento" wire:change="muestraMedicamentoSolicitado" required>
                        <option selected="selected">Selecciona código</option>
                        @foreach ($medicamentos as $medicamento)
                            <option value="{{ $medicamento->id }}">
                                {{ $medicamento->codigo_medicamento }} - {{ $medicamento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                    <strong><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-capsule" viewBox="0 0 16 16">
                            <path
                                d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                        </svg>
                        MEDICAMENTO O INSUMO: </strong>
                    <p class="p-2 p-comun-disabled">
                        {{ $nombreMedicamento }}
                    </p>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label for="formaFarmaceuticaMostrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-prescription2" viewBox="0 0 16 16">
                            <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                            <path
                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                        </svg>
                        FORMA FARMACÉUTICA:</label>
                    <input class="form-control input-comun-disabled" id="formaFarmaceuticaMostrar"
                        wire:model="forma_farmaceutica" disabled>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label for="concentracionMostrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-prescription2" viewBox="0 0 16 16">
                            <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                            <path
                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                        </svg>
                        CONCENTRACIÓN</label>
                    <input class="form-control input-comun-disabled" id="concentracionMostrar"
                        wire:model="concentracion" disabled>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-right"><br>
                    <div>
                        <x-agregar-button wire:click="addMedicamentoListado">ADICIONAR</x-agregar-button>
                    </div>
                </div>
            </div>
            <br>
        </fieldset>
        <div style="height: 0.5rem;"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="cabecera-tabla-azul-oscuro text-center">
                    <b> MEDICAMENTOS INGRESADOS</b>
                </div>
            </div>
        </div>
        <div class="row">
            <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                <div style="height: 180px; overflow-y: auto;">
                    <table class="table tabla-azul-oscuro">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>COD MEDICAMENTO</th>
                                <th>MEDICAMENTO</th>
                                <th>FORMA FARMACÉUTICA</th>
                                <th>CONCENTRACIÓN</th>
                                <th>STOCK DE FARMACIA</th>
                                <th>STOCK DE ALMACEN</th>
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
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $medicamentoIngresado['codigo_medicamento'] }}</td>
                                        <td>{{ $medicamentoIngresado['nombre'] }}</td>
                                        <td>{{ $medicamentoIngresado['forma_farmaceutica'] }}</td>
                                        <td>{{ $medicamentoIngresado['concentracion'] }}</td>
                                        <td>{{ number_format($medicamentoIngresado['stock_farmacia'], 0, ',', '.') }}
                                        </td>
                                        <td>{{ number_format($medicamentoIngresado['stock_almacen'], 0, ',', '.') }}
                                        </td>

                                        <td>
                                            <x-eliminar-button
                                                wire:click="eliminarMedicamentoSeleccionado('{{ $medicamentoIngresado['codigo_medicamento'] }}')"></x-eliminar-button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="modal-footer " style="border: none; background-color: white;">
        <x-cancel-button wire:click="cancelarEntrega"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarEdicionSolicitud()">GUARDAR</x-save-button>
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

        .custom-swal-popup {
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 25px;
        }
    </style>
</div>
<script>
    function guardarEdicionSolicitud() {
        Swal.fire({
            title: '¿Guardar Entrega?',
            text: "Se guardarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('EditSolicitud');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "success"
                });
            }
        });
    }

    document.addEventListener('guardar_entrega_almacen', function() {
        Swal.fire({
            title: "Guardado!",
            text: "El registro se guardo correctamente.",
            icon: "success"

        });
    });
    document.addEventListener('errorFuncion', function() {
        Swal.fire({
            title: "Error!",
            text: "Ocurrio un error en el registro.",
            icon: "warning"

        });
    });
    document.addEventListener('stockAlmacenCero', function() {
        Swal.fire({
            title: "¡Stock insuficiente !",
            text: "El medicamento solicitado no se encuentra disponible en stock de Almacen de medicamentos",
            icon: "error",
            customClass: {
                popup: 'custom-swal-popup'
            }
        });
    });

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
    document.addEventListener('medicamentoYaSeleccionado', function() {
        Swal.fire({
            title: "¡Medicamento ya registrado!",
            text: "El medicamento ya fue seleccionado, porfavor seleccione otro medicamento.",
            icon: "error",
            customClass: {
                popup: 'custom-swal-popup'
            }
        });
    });
</script>
