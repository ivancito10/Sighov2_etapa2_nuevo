<div>
    <div>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/styles/choices.min.css"
            rel="stylesheet">
        @if ($mostrarAdvertencia)
            <div id="miToast" class="toast bg-warning text-dark show" role="alert" aria-live="assertive"
                aria-atomic="true" style="position: absolute; right: 10px; z-index: 999999;">
                <div class="toast-header">
                    <strong class="mr-auto">¡ADVERTENCIA!</strong>
                    <button type="button" class="ml-2 mb-1 close" aria-label="Cerrar"
                        onclick="document.getElementById('miToast').remove()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Está agregando más de 3 medicamentos, esta receta necesita autorización de Gerencia de Salud.
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <div class="card-borde p-3">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:right;">
                            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes campos para
                                        registrar.</b>
                                </em>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="medicamento"> MEDICAMENTO: <b style="color:#cb4335;">*</b></label>
                            <select id="medicamento" class="form-control" wire:change="stockActualMed"
                                title="Selecciona una opción...">
                                <option value="" disabled selected>Selecciona una opción...</option>
                                @forelse ($medicamentos as $med)
                                    <option value="{{ $med['id'] }}"
                                        data-custom-properties='@json([
                                            'noVigente' => $med['no_vigente'],
                                            'stock' => $med['stock_actual'],
                                        ])'>
                                        {{ $med['codigo_medicamento'] }} - {{ $med['nombre'] }}
                                        - [{{ $med['concentracion'] }}] [{{ $med['forma_farmaceutica'] }}]
                                        {{ $med['stock_actual'] == 0 ? ' ⚠️(sin stock)' : '' }}
                                        {{ $med['no_vigente'] ? ' (En tratamiento)' : '' }}
                                    </option>
                                @empty
                                    <option disabled selected>No hay medicamentos disponibles</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="stock_actual">STOCK ACTUAL:</label>
                            <input class="form-control" id="stock_actual" type="number" wire:model="stock_actual"
                                style="border-radius:8px;" min="0" disabled>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="cantidad">CANTIDAD: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control @error('cantidad') is-invalid @enderror" id="cantidad"
                                type="number" wire:model.live="cantidad" style="border-radius:8px;"
                                placeholder="Ingrese la cantidad" min="0" required
                                wire:blur="diasValidezMedicamento">
                            @error('cantidad')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <label for="indicaciones"> INDICACIONES: <b style="color:#cb4335;">*</b></label>
                            <select id="indicaciones"
                                class="form-control @error('selectedIndicacion') is-invalid @enderror"
                                wire:model="selectedIndicacion" wire:change="diasValidezMedicamento">
                                style="width: 100%; border-radius:8px;" required>
                                <option value="" disabled>Seleccione Indicaciones</option>
                                <option value="Cada 24 Hrs.">Cada 24 Hrs.</option>
                                <option value="Cada 12 Hrs.">Cada 12 Hrs.</option>
                                <option value="Cada 8 Hrs.">Cada 8 Hrs.</option>
                                <option value="Cada 6 Hrs.">Cada 6 Hrs.</option>
                                <option value="Cada 4 Hrs.">Cada 4 Hrs.</option>
                                <option value="Cada 2 Hrs.">Cada 2 Hrs.</option>
                                <option value="1 vez al dia">1 vez al dia</option>
                                <option value="2 veces al dia">2 veces al dia</option>
                                <option value="3 veces al dia">3 veces al dia</option>
                                <option value="1 vez por semana">1 vez por semana</option>
                                <option value="2 veces por semana">2 veces por semana</option>
                                <option value="Seguir indicaciones">Seguir indicaciones</option>
                            </select>
                            @error('selectedIndicacion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="diasValidez"> DÍAS VALIDEZ: <b style="color:#cb4335;">*</b></label>
                            <input type="text" id="diasValidez" class="form-control @error('diasValidez') is-invalid @enderror" wire:model.live="diasValidez">
                            @error('diasValidez')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="forma_adm"> FORMA DE ADMINISTRACIÓN: <b style="color:#cb4335;">*</b></label>
                            <select id="forma_adm" class="form-control @error('forma_adm') is-invalid @enderror"
                                wire:model.live="forma_adm" style="width: 100%; border-radius:8px;" required>
                                <option value="" disabled>Seleccione forma adm</option>
                                <option value="Vía Oral">Vía Oral</option>
                                <option value="Vía Endovenosa">Vía Endovenosa</option>
                                <option value="Vía Intramuscular">Vía Intramuscular</option>
                                <option value="Vía Rectal">Vía Rectal</option>
                                <option value="Vía Topica">Vía Topica</option>
                                <option value="Vía Intravaginal">Vía Intravaginal</option>
                                <option value="Vía Otica">Vía Otica</option>
                                <option value="Vía Nasal">Vía Nasal</option>
                                <option value="Vía Ocular">Vía Ocular</option>
                                <option value="Vía subcutanea">Vía subcutánea</option>
                                <option value="Otros">Otros</option>
                            </select>
                            @error('forma_adm')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <label for="recomendaciones" class="form-label">
                                RECOMENDACIONES:
                            </label>
                            <textarea id="recomendaciones" wire:model.live="recomendacion"
                                class="form-control @error('recomendacion') is-invalid @enderror" rows="3"
                                placeholder="Ingrese recomendaciones..." style="border-radius: 8px; resize: vertical;"></textarea>

                            @error('recomendacion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <label for="recomendaciones" class="form-label">
                                FECHA PROXIMA CONSULTA:
                            </label>
                            <input id="fecha_proxima_consulta"
                                class="form-control @error('fecha_internacion') is-invalid @enderror"
                                wire:model=fecha_proxima_consulta type="date" placeholder="Fecha proxima consulta"
                                style="border-radius:8px;" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <br>

                    <div class="row" style="text-align:right">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <x-agregar-button wire:click="registrarMedicamento"> Agregar</x-agregar-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-12" style="text-align:center;">
                <div class="card-comun p-2">
                    <h5><b> Medicamentos en tratamiento</b></h5>
                    <x-ver-button class="py-1" wire:click="verMedicamentosRestringidos"
                        title="VER MEDICAMENTOS RESTRINGIDOS" wire:loading.attr="disabled">
                        Ver
                    </x-ver-button>
                </div>


            </div>

            <!-- Card de la derecha (4 espacios) -->
            <!-- Medicamentos Restringidos -->

            <!-- <div class="card p-3" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                     
                    <button class="btn btn-warning" wire:click="actualizarMedicamentosManual" title="ACTUALIZAR">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                        </svg> <b> Actualizar Medicamentos </b>
                    </button>

                </div> -->

        </div>


        <!-- Tabla de medicamentos seleccionados -->


        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="cabecera-tabla-azul-oscuro text-center">
                    <b> MEDICAMENTOS INGRESADOS</b>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table tabla-azul-oscuro">
                <thead style="background-color: #1f618d; color:white; text-align:center;">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">CÓDIGO</th>
                        <th scope="col">MEDICAMENTO</th>
                        <th scope="col">CONCENTRACIÓN</th>
                        <th scope="col">CANT.</th>
                        <th scope="col">INDICACIONES</th>
                        <th scope="col">DÍAS DE VALIDEZ</th>
                        <th scope="col">FORMA FARMACÉUTICA</th>
                        <th scope="col">OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicamentosSeleccionados as $i => $item)
                        <tr>
                            <td style="text-align:center;"> <b>{{ $loop->iteration }}. </b></td>
                            <td style="text-align:center;">{{ $item['codigo'] }}</td>
                            <td>{{ $item['medicamento'] }}</td>
                            <td style="text-align:center;">{{ $item['concentracion'] }}</td>
                            <td style="text-align:center;">{{ $item['cantidad'] }}</td>
                            {{-- INDICACIONES editable siempre --}}
                            <td style="width:220px;">
                                <select
                                    class="form-control form-control-sm @error('medicamentosSeleccionados.' . $i . '.indicaciones') is-invalid @enderror"
                                    wire:model.lazy="medicamentosSeleccionados.{{ $i }}.indicaciones">
                                    <option value="" disabled>Seleccione Indicaciones</option>
                                    <option value="Cada 24 Hrs.">Cada 24 Hrs.</option>
                                    <option value="Cada 12 Hrs.">Cada 12 Hrs.</option>
                                    <option value="Cada 8 Hrs.">Cada 8 Hrs.</option>
                                    <option value="Cada 6 Hrs.">Cada 6 Hrs.</option>
                                    <option value="Cada 4 Hrs.">Cada 4 Hrs.</option>
                                    <option value="Cada 2 Hrs.">Cada 2 Hrs.</option>
                                    <option value="1 vez al dia">1 vez al dia</option>
                                    <option value="2 veces al dia">2 veces al dia</option>
                                    <option value="3 veces al dia">3 veces al dia</option>
                                    <option value="1 vez por semana">1 vez por semana</option>
                                    <option value="2 veces por semana">2 veces por semana</option>
                                </select>
                                @error('medicamentosSeleccionados.' . $i . '.indicaciones')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                            <td style="text-align:center; width:120px;">
                                <input type="number"
                                    class="form-control form-control-sm @error('medicamentosSeleccionados.' . $i . '.dias_validez') is-invalid @enderror"
                                    min="0"
                                    wire:model.lazy="medicamentosSeleccionados.{{ $i }}.dias_validez">
                                @error('medicamentosSeleccionados.' . $i . '.dias_validez')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                            <td>{{ $item['forma_farmaceutica'] }}</td>
                            <td class="text-center align-middle">
                                <button wire:click="removeMedicamento({{ $i }})"
                                    class="btn btn-sm btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <div wire:loading wire:target="registrarMedicamento">
        <x-cargando-dos />
    </div>

    <div class="modal-footer" style="border: none; background-color: white;">
        <x-cancel-button onclick="cerrarModalReceta()"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarReceta()">GUARDAR</x-save-button>
    </div>

    @include('livewire.atencion-medica.consulta-externa.receta.modalMedicamentosRest')

    <style>
        .choices__inner {
            width: 100% !important;
            /* Asegurarse que ocupe todo el ancho disponible */
            border: 1px solid #ced4da;
            /* Borde similar a Bootstrap */
            border-radius: 0.375rem;
            /* Bordes redondeados como Bootstrap */
            padding: 0.375rem 0.75rem;
            /* Padding similar a los formularios de Bootstrap */
            font-size: 1rem;
            /* Tamaño de fuente igual al de los formularios de Bootstrap */

            overflow: visible !important;
        }

        .choices__list {
            max-height: 300px;
            overflow-y: auto;
            width: 100% !important;
        }

        .choices__item {
            writing-mode: unset !important;
            transform: unset !important;
            white-space: normal !important;
            text-align: left;
        }

        .modal-backdrop {
            z-index: 1040 !important;
            /* Menor que el toast y el modal */
        }
    </style>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Choices.js -->
<script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('medicamento');

        const medicamentoSelect = new Choices(el, {
            searchEnabled: true,
            searchFields: ['label', 'value'],
            itemSelectText: 'Presiona para seleccionar',
            shouldSort: false,
            searchResultLimit: 8, // <-- muestra hasta 10 resultados al buscar
            renderChoiceLimit: 10 // <-- limita cuántas opciones renderiza en el dropdown
        });

        function marcarNoVigentes() {
            // Recorremos las opciones que Choices tiene en memoria
            medicamentoSelect._store.choices.forEach(choice => {
                if (choice.customProperties && choice.customProperties.noVigente) {
                    // Buscamos el elemento correspondiente en el DOM
                    const el = document.querySelector(
                        `.choices__list--dropdown .choices__item[data-id="${choice.id}"]`
                    );

                    if (el) {
                        el.style.opacity = '0.5';
                        el.style.fontStyle = 'italic';
                        el.style.color = '#555';
                    }
                }
            });
        }

        // Llamamos una vez al iniciar
        marcarNoVigentes();

        // Cuando el usuario intenta seleccionar una opción
        el.addEventListener('addItem', function(event) {
            const props = event.detail && event.detail.customProperties;

            if (props && props.virtualDisabled) {
                // Intento de cancelar (si el navegador/evento lo permite)
                if (event.preventDefault) event.preventDefault();

                // Como respaldo, elimina el ítem agregado
                medicamentoSelect.removeActiveItemsByValue(event.detail.value);

                Swal.fire({
                    icon: 'warning',
                    title: '¡Sin stock!',
                    text: 'Este medicamento no está disponible.',
                });
                return; // no disparamos Livewire
            }

            // Solo si la opción es válida, sincronizamos con Livewire
            @this.set('selectedMedicamento', event.detail.value);
        });

        // Si igual quieres escuchar el change por compatibilidad:

        // Ejemplo de tu evento custom:
        window.addEventListener('medicamentoRegistrado', () => {
            medicamentoSelect.removeActiveItems();
            Swal.fire({
                title: 'Medicamento Agregado',
                text: 'El medicamento se agrego correctamente.',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        });

        window.addEventListener('medicamentoNovigente', () => {
            medicamentoSelect.removeActiveItems();
            Swal.fire({
                title: '¡Medicamento no vigente!',
                text: 'El medicamente ya se encuentra en tratamiento para este asegurado.',
                icon: 'warning'
            });
        });

        window.addEventListener('stockInsuficiente', () => {
            medicamentoSelect.removeActiveItems();
            Swal.fire({
                title: '¡Stock Insuficiente!',
                text: 'No hay suficiente stock para completar la solicitud.',
                icon: 'warning',
            });
        });

        window.addEventListener('sinMedicamento', () => {
            Swal.fire({
                title: '¡Sin medicamentos registrados!',
                text: 'Debe ingresar al menos un medicamento antes de guardar.',
                icon: 'warning',
            });
        });


    });

    document.addEventListener('medicamentoYaRegistrado', function() {
        Swal.fire({
            title: '¡Medicamento Duplicado!',
            text: 'El medicamento ya se registro anteriormente.',
            icon: 'warning'
        });
    })

    function guardarReceta() {
        Swal.fire({
            title: '¿Guardar Receta?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarReceta');
            }
        });
    }

    function cerrarModalReceta() {
        $('#modalRReceta').modal('hide');
        $wire.limpiarDatos();
    }
    document.addEventListener('recetaCreada', function() {
        $('#modalRReceta').modal('hide');
        Swal.fire({
            title: '¡Guardado!',
            text: 'La receta fue prescrita correctamente.',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
        });
    })
    document.addEventListener('error', function() {
        Swal.fire({
            title: 'Error!',
            text: 'Comuniquese con el administrador',
            icon: 'error'
        });
    })

    document.addEventListener('limiteSuperado', function() {
        Swal.fire({
            title: 'Limite superado!',
            text: 'No es posible agregar más de 3 medicamentos por receta',
            icon: 'warning'
        });
    })

    //toast
    document.addEventListener('abrirToast3Medicamentos', function() {
        setTimeout(() => {
            const toast = document.getElementById('miToast');
            if (toast) toast.remove();
        }, 5000);
    });

    document.addEventListener('CantidadExcedida', function() {
        Swal.fire({
            title: 'Cantidad excedida',
            text: 'La cantidad ingresada supera el límite permitido para consulta externa.',
            html: `
                <p>La cantidad ingresada supera el límite permitido para consulta externa.</p>
                <p><strong>Cantidad máxima permitida:</strong> ${event.detail.cantidad_maxima}</p>
                <p><strong><em>Comuníquese con la regente de farmacia.</em></strong></p>
            `,
            icon: 'warning',
        });
    });



    document.addEventListener('medicamentosActualizados', function() {
        $('#modalRReceta').modal('hide');
        Swal.fire({
            title: '¡Medicamentos actualizados!',
            text: 'La lista de medicamentos se actualizo corretamente',
            icon: 'success',
        });
    });



    // document.addEventListener('actualizarChoices', function() {
    //     if (choicesInstance) {
    //         choicesInstance.clearStore();
    //         // Recarga las opciones desde el DOM actualizado
    //         choicesInstance.setChoices(
    //             Array.from(document.querySelectorAll('#selectMedicamentos option')).map(option => ({
    //                 value: option.value,
    //                 label: option.text,
    //                 selected: option.selected,
    //                 disabled: option.disabled
    //             }))
    //         );
    //     }
    // });
</script>
