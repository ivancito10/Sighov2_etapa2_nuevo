<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
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
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_internacion">FECHA DE INTERNACIÓN: <span
                                style="color:#cb4335;"><b>*</b></span></label>
                        <input id="fecha_internacion"
                            class="form-control @error('fecha_internacion') is-invalid @enderror"
                            wire:model=fecha_internacion type="date" placeholder="Ingrese el nro. de sesiones"
                            style="border-radius:8px;">
                        @error('fecha_internacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                        <label for="indicaciones"> INDICACIONES DE INTERNACIÓN: <span
                                style="color:#cb4335;"><b>*</b></span></label>
                        {{-- <input type="text" id="indicaciones" placeholder="Ingrese las indicaciones de internacion"
                            class="form-control @error('indicaciones') is-invalid @enderror"
                            oninput="this.value = this.value.toUpperCase()" wire:model="indicaciones"
                            style="border-radius:8px;">
                        @error('indicaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror --}}
                        <div id="indicaciones-texto" style="height: 150px;">
                            {{-- <!-- <p>introduzca el diagnostico</p> --> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer " style="border: none; background-color: white;">
        <x-cancel-button onclick="cerrarModalInternacion()"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarInternacion()">GUARDAR</x-save-button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    let quill;
    document.addEventListener('DOMContentLoaded', function() {
        if (!quill) {
            quill = new Quill('#indicaciones-texto', {
                theme: 'snow',
                placeholder: 'Introduzca las indicaciones...',
            });
        } else {
            quill.setText('');
            quill = new Quill('#indicaciones-texto', {
                theme: 'snow',
                placeholder: 'Introduzca las indicaciones...',
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        $('#examenes').select2();

        $('#examenes').on('change', function() {
            let seleccionadas = $(this).val();
            console.log("examenes seleccionadas (JS):", seleccionadas);
            @this.call('agregarFuncion', seleccionadas);
        });
    });

    function guardarInternacion() {
        Swal.fire({
            title: '¿Guardar Orden de Internación?',
            text: "Se registrarán los datos de internación ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarInternacion', {
                    indicacion: quill.getSemanticHTML()
                });
                //Livewire.dispatch('guardarInternacion');
            }
        });
    }

    document.addEventListener('internacionGuardada', function() {
        $('#modalRInternacion').modal('hide');
        Swal.fire({
            title: '¡Guardado!',
            text: 'Los datos de internación se registraron correctamente.',
            icon: 'success'
        });
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: '¡Error!',
            text: 'Comuniquese con el administrador',
            icon: 'error'
        });
    })

    function cerrarModalInternacion() {
        $('#modalRInternacion').modal('hide');
    }
</script>
