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
                            </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes datos para el registro. </b>
                            </em>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="nro_sesiones">NRO. DE SESIONES: <span style="color:#cb4335;"><b>*</b></span></label>
                        <input id="nro_sesiones" class="form-control @error('nro_sesiones') is-invalid @enderror" wire:model=nro_sesiones type="number"
                         placeholder="Ingrese el nro. de sesiones" style="border-radius:8px;" min="0">
                        @error('nro_sesiones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                   
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                        <label for="indicaciones"> INDICACIONES: <span style="color:#cb4335;"><b>*</b></span></label>
                        <input type="text" id="indicaciones" placeholder="Ingrese el motivo de indicaciones"
                        class="form-control @error('indicaciones') is-invalid @enderror" oninput="this.value = this.value.toUpperCase()"
                        wire:model="indicaciones" style="border-radius:8px;"  > 
                        @error('indicaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
 
            </div>
        </div>
    </div>
    <div class="modal-footer " style="border: none; background-color: white;">
        <x-cancel-button onclick="cerrarModalFisioterapia()"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarFisioterapia()">GUARDAR</x-save-button>
    </div>
</div>

<script>
    

    document.addEventListener('DOMContentLoaded', function() {
        $('#examenes').select2();

        $('#examenes').on('change', function() {
            let seleccionadas = $(this).val();
            console.log("examenes seleccionadas (JS):", seleccionadas);
            @this.call('agregarFuncion', seleccionadas);
        });
    });

    function guardarFisioterapia() {
        Swal.fire({
                title: '¿Guardar Orden de Fisioterapia?',
                text: "Se registrarán los datos ingresados para fisioterapia.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardarFisioterapia');
                }
            });
        }

    document.addEventListener('fisioterapiaGuardada', function() {
        $('#modalRFisioterapia').modal('hide');
        Swal.fire({
            title: '¡Guardado!',
            text: 'Los datos de fisioterapia se registraron correctamente.',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
        });
    });

    function cerrarModalFisioterapia() {
        $('#modalRFisioterapia').modal('hide');
    }
    
</script>
