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
                                    registro. </b>
                            </em>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                        <label for="especialidad"> ESPECIALIDAD: <b style="color:#cb4335;">*</b></label>
                        <select id="especialidad_externa"
                            class="form-control @error('id_especialidad') is-invalid @enderror"
                            style="width: 100%; border-radius:8px;" wire:model="id_especialidad" required>
                            <option selected="selected">Selecciona la especialidad</option>
                            @foreach ($especialidades as $esp)
                                <option value="{{ $esp['id'] }}">
                                    {{ $esp['especialidad'] ?? 'Sin nombre' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_especialidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="indicaciones_ref">INDICACIONES: <b style="color:#cb4335;">*</b></label>
                        <textarea class="form-control @error('indicaciones_ref') is-invalid @enderror" id="indicaciones_ref" type="text"
                            wire:model="indicaciones_ref" style="border-radius:8px;" placeholder="Ingrese el  motivo de referencia"
                            rows="2"></textarea>
                        @error('indicaciones_ref')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer" style="border: none; background-color: white;">
        <x-cancel-button onclick="cerrarModalReferencia()"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarReferencia()">GUARDAR</x-save-button>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const especialidadSelect = new Choices('#especialidad_externa', {
            searchEnabled: true,
            itemSelectText: 'Presiona para seleccionar',
            searchResultLimit: 8, // <-- muestra hasta 10 resultados al buscar
            renderChoiceLimit: 10 // <-- limita cuántas opciones renderiza en el dropdown
        });
        window.addEventListener('referenciaGuardada', () => {
            especialidadSelect.removeActiveItems();
            $('#modalRReferencia').modal('hide');
            Swal.fire({
                title: '¡Guardado!',
                text: 'Los datos se registraron correctamente',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
            });
        });
    });

    function guardarReferencia() {
        Swal.fire({
            title: '¿Guardar Orden de Referencia?',
            text: "Se registrarán los datos de referencia ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarReferencia');
            }
        });
    }

    document.addEventListener('error', function() {
        Swal.fire({
            title: '¡Error!',
            text: 'Comuniquese con el administrador',
            icon: 'error'
        });
    })

    function cerrarModalReferencia() {
        $('#modalRReferencia').modal('hide');
    }
</script>
