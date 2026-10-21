<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card-borde p-3">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                        <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes datos para el
                                    registro de </b> <b style="text-decoration:underline;">
                                    {{ $nombreImagenologia }}.</b>
                            </em>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label for="estudios"> ESTUDIOS: <span style="color:#cb4335;"><b>*</b></span></label>
                        <select id="estudios" class="form-control select2" style="width: 100%; border-radius:8px;"
                            multiple="multiple" tabindex="-1">
                            @foreach ($estudios as $estudio)
                                <option value="{{ $estudio->id }}">{{ $estudio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="indicaciones" class="form-label"> INDICACIONES: </label>
                        <textarea id="indicaciones" wire:model="indicaciones" placeholder="Ingrese las indicaciones..."
                            class="form-control @error('indicaciones') is-invalid @enderror" style="border-radius:8px;" rows="2"></textarea>

                        @error('indicaciones')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" style="border: none; background-color: white;">
        <x-cancel-button onclick="cerrarModalImagenologia()"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarImagenologia()" wire:target="guardarImagenologia">GUARDAR</x-save-button>

        <div wire:loading wire:target="guardarImagenologia">
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
    </style>
</div>


<script>
    document.addEventListener('abrirModalImagenologia', function() {
        const $estudios = $('#estudios');

        // 1️⃣ Limpiar selección previa
        $estudios.val(null).trigger('change');

        setTimeout(() => {
            // 2️⃣ Reinicializar select2
            if ($estudios.hasClass('select2-hidden-accessible')) {
                $estudios.select2('destroy');
            }

            $estudios.select2();

            // 3️⃣ Listener único
            $estudios
                .off('change.imagenologia')
                .on('change.imagenologia', function() {
                    let seleccionadas = $(this).val();
                    console.log(seleccionadas);
                    @this.call('agregarEstudios', seleccionadas);
                });

        }, 50);
    });



    function guardarImagenologia() {
        Swal.fire({
            title: '¿Guardar Orden de Imagenología?',
            text: "Se registrarán los estudios seleccionados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarImagenologia');
            }
        });
    }

    document.addEventListener('imagenologiaGuardada', function() {
        $('#modalRImagenologia').modal('hide');
        $('#estudios').val(null).trigger('change');
        Swal.fire({
            title: '¡Guardado!',
            text: 'Los estudios  se registraron correctamente.',
            icon: 'success'
        });
    });
    document.addEventListener('error', function() {
        Swal.fire({
            title: '¡Error!',
            text: 'Comuniquese con el administrador.',
            icon: 'error'
        });
    })

    document.addEventListener('campoIncompleto', (event) => {
        Swal.fire({
            title: '¡Campos necesarios!',
            text: event.detail.mensaje,
            icon: 'warning'
        });
    })

    function cerrarModalImagenologia() {
        $('#modalRImagenologia').modal('hide');
    }
</script>
