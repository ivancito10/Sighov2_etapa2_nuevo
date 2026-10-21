<div>
    <div class="card-borde p-3">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:right;">
                <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Seleccione los campos necesarios para el
                            registro
                            de </b> <b style="text-decoration:underline;"> {{ $tipo_lab_padre }}.</b>
                    </em>
                </p>
            </div>
        </div>

        @if ($configuracionVista)

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @foreach ($tipos_laboratorio as $index => $tipoLaboratorioNombre)
                        <button class="nav-link @if ($index === 0) active @endif"
                            id="nav-tab-{{ $index }}" data-toggle="tab"
                            data-target="#nav-pane-{{ $index }}" type="button" role="tab"
                            aria-controls="nav-pane-{{ $index }}"
                            aria-selected="@if ($index === 0) true @else false @endif">
                            {{ $tipoLaboratorioNombre['nombre_examen'] }}
                        </button>
                    @endforeach
                </div>
            </nav>
            <br>
            <div class="tab-content" id="nav-tabContent">
                @foreach ($tipos_laboratorio as $index => $tipoLaboratorioNombre)
                    <div class="tab-pane fade @if ($index === 0) show active @endif"
                        id="nav-pane-{{ $index }}" role="tabpanel" aria-labelledby="nav-tab-{{ $index }}">
                        @if ($tipoLaboratorioNombre['examenes_hijo'] !== [])
                            <div class="row">
                                @foreach ($tipoLaboratorioNombre['examenes_hijo'] as $examenHijoIndex => $examenHijo)
                                    <div class="col-md-3 col-sm-6 col-xs-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="checkbox-{{ $index }}-{{ $examenHijoIndex }}"
                                                value="{{ $examenHijo['id'] }}" wire:model="examenesSeleccionados">
                                            <label class="form-check-label"
                                                for="checkbox-{{ $index }}-{{ $examenHijoIndex }}">
                                                {{ $examenHijo['nombre_examen'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkbox-hormonas"
                                            value="{{ $tipoLaboratorioNombre['id'] }}"
                                            wire:model="examenesSeleccionados">
                                        <label class="form-check-label" for="checkbox-hormonas">
                                            {{ $tipoLaboratorioNombre['nombre_examen'] }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label for="tipo_laboratorio"> TIPO DE LABORATORIO: <b style="color:#cb4335;">*</b></label>
                    <select id="examenesLab" class="form-control select2" multiple="multiple"
                        style="width: 100%; border-radius:8px; " placeholder="Seleccione.." tabindex="-1">
                        @foreach ($tipos_laboratorio as $examen)
                            @if (array_key_exists('nombre_examen', $examen))
                                <optgroup label="{{ $examen['nombre_examen'] }}">
                                    @foreach ($examen['examenes_hijo'] as $desc)
                                        @if (isset($orden['examenes']))
                                            @php
                                                $sw = 0;
                                            @endphp
                                            @foreach ($orden['examenes'] as $select)
                                                @if ($select['id'] == $desc['id'])
                                                    <option selected="true" value="{{ $desc['id'] }}">
                                                        {{ $desc['nombre_examen'] }}</option>
                                                    @php
                                                        $sw = 1;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if ($sw != 1)
                                                <option value="{{ $desc['id'] }}">
                                                    {{ $desc['nombre_examen'] }}
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $desc['id'] }}">{{ $desc['nombre_examen'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @elseif (array_key_exists('nombre', $examen))
                                <option value="{{ $examen['id'] }}">{{ $examen['nombre'] }}
                                </option>
                            @else
                                <p class="alert alert-danger text-center"><strong>SIN DATOS</strong></p>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="observaciones"> OBSERVACIONES: </label>
                <textarea type="text" id="observaciones" placeholder="Ingrese las observaciones" class="form-control"
                    oninput="this.value = this.value.toUpperCase()" wire:model="observaciones" style="border-radius:8px;"
                    rows="2"></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer " style="border: none; background-color: white;">
        <x-cancel-button onclick="cerrarModalLaboratorio()"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarLaboratorio()" wire:target="guardarLaboratorio">GUARDAR</x-save-button>

        <div wire:loading wire:target="guardarLaboratorio">
            <x-cargando-dos />
        </div>
    </div>

    <style>
        /* Cambia el fondo y color del select cuando tiene elementos seleccionados */
        .select2-container--default .select2-selection--multiple {
            background-color: white !important;
            /* Fondo blanco */
            color: #2874A6 !important;
            /* Texto azul */
            border-color: #ccc !important;
            /* Borde */
        }

        /* Cambia el color de los ítems seleccionados */
        .select2-container--default .select2-selection__choice {
            background-color: #2874A6 !important;
            /* Fondo azul */
            color: white !important;
            /* Texto blanco */
            border-color: #2874A6 !important;
            /* Borde del ítem */
        }

        /* Cambia los colores de los ítems en la lista desplegable */
        .select2-container--default .select2-results__option {
            background-color: white !important;
            /* Fondo blanco */
            color: #2874A6 !important;
            /* Texto azul */
        }

        /* Cambia el color del ítem que está resaltado al hacer hover o está seleccionado en la lista */
        .select2-container--default .select2-results__option--highlighted {
            background-color: #2874A6 !important;
            /* Fondo azul */
            color: white !important;
            /* Texto blanco */
        }

        /* EXTRAIDO DEL CHATCITO PARA CAMBIAR EL COLOR DE LAS PESTAÑAS */

        /* Color para las pestañas que NO están seleccionadas (reemplaza al plomo) */
        .nav-tabs .nav-link {
            background-color: #2471a3;
            color: white;
            /* Texto blanco para que resalte sobre el azul */
            border: 1px solid #1a5276;
            /* Un borde un poco más oscuro para dar profundidad */
            margin-right: 2px;
        }

        /* Color para la pestaña SELECCIONADA (reemplaza al blanco) */
        .nav-tabs .nav-link.active {
            background-color: #d4e6f1 !important;
            /* Azul muy claro */
            color: #2471a3 !important;
            /* Texto en el azul original para contraste */
            border-color: #2471a3 #2471a3 transparent;
            /* Bordes definidos */
            font-weight: bold;
        }

        /* Efecto Hover (opcional, para cuando pasas el mouse) */
        .nav-tabs .nav-link:hover {
            background-color: #2e86c1;
            color: white;
            border-color: #2471a3;
        }
    </style>
</div>

<script>
    document.addEventListener('abrirModalLaboratorio', function() {
        //SELECT2MODAL no se pierde en el modal USAR ESTO URGENTEEEE!!!

        // Primero resetear la selección antes de iniciar select2
        $('#examenesLab').val(null).trigger('change');

        setTimeout(() => {
            $('#examenesLab').select2();
        }, 50);

        $('#examenesLab').on('change', function() {
            let seleccionadas = $(this).val();
            //console.log("examenes seleccionadas (JS):", seleccionadas);
            @this.call('agregarExamenes', seleccionadas);
        });
    });

    function cerrarModalLaboratorio() {
        $('#modalRLabotarorio').modal('hide');
        Livewire.dispatch('resetearArrays');
    }

    function guardarLaboratorio() {
        Swal.fire({
            title: '¿Guardar Orden de Laboratorio?',
            text: "Se registrarán los estudios ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarLaboratorio');
            }
        });
    }

    document.addEventListener('laboratorioRegistrado', function() {
        $('#modalRLabotarorio').modal('hide');
        Swal.fire({
            title: '¡Guardado!',
            text: "La orden de laboratorio se registro exitosamente.",
            icon: 'success',
        })
    });

    document.addEventListener('examenesDuplicados', (event) => {
        //console.log(event.detail.message);
        Swal.fire({
            title: '¡Examenes Registrados Anteriormente!',
            text: event.detail.message,
            icon: 'warning',
        })
    });

    document.addEventListener('errorLaboratorio', (event) => {
        //console.log(event.detail.message);
        Swal.fire({
            title: '¡Examenes Registrados Anteriormente!',
            text: event.detail.message,
            icon: 'warning',
        })
    });
</script>
