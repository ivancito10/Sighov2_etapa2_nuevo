<div>
    <fieldset class="field_estilos">
        <legend>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path
                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                    d="M1 13.5A1.5 1.5 0 0 0 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
            </svg>
            <em>Complete la información correspondiente al Registro de Placas:</em>
            <span>*</span>
        </legend>
        <div class="row">

            <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                <label for="observaciones">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-chat-left-text" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414L1 14.414V2a1 1 0 0 1 1-1h12z" />
                        <path d="M3 3.5h10v1H3v-1zm0 2h10v1H3v-1zm0 2h7v1H3v-1z" />
                    </svg>

                    OBSERVACIONES: <span style="color:#cb4335;"><b>*</b></span></label>
                <textarea type="text" class="form-control @error('observaciones') is-invalid @enderror input-comun"
                    wire:model="observaciones" placeholder="Ingrese observaciones"></textarea>
                @error('observaciones')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                <label for="placas_rx">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-prescription" viewBox="0 0 16 16">
                        <path
                            d="M5.5 6a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 1 0V9h.293l2 2-1.147 1.146a.5.5 0 0 0 .708.708L9 11.707l1.146 1.147a.5.5 0 0 0 .708-.708L9.707 11l1.147-1.146a.5.5 0 0 0-.708-.708L9 10.293 7.695 8.987A1.5 1.5 0 0 0 7.5 6zM6 7h1.5a.5.5 0 0 1 0 1H6z" />
                        <path
                            d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                    </svg>
                    SUBIR PLACAS: <span style="color:#cb4335;"><b>*</b></span></label>

                <input type="file" class="form-control" wire:model="placas_rx" multiple accept="image/*">
                <div wire:loading wire:target="placas_rx" class="mt-2">
                    <x-cargando-dos />
                </div>
                @error('placas_rx')
                    <span class="text-danger">Debes subir al menos una imagen.</span>
                @enderror
                @error('placas_rx.*')
                    <span class="text-danger">Solo se permiten imágenes y máximo 2MB.</span>
                @enderror

                <!-- PREVISUALIZACIÓN DE IMÁGENES -->
                @if ($placas_rx)
                    <div class="mt-3 d-flex flex-wrap">
                        @foreach ($placas_rx as $imagen)
                            <div class="m-2">
                                <img src="{{ $imagen->temporaryUrl() }}" class="img-thumbnail" style="height:120px;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </fieldset>

    <div class="modal-footer pt-0"
        style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
        <x-cancel-button data-dismiss="modal" onclick="CancelarRegistro()">CANCELAR</x-cancel-button>
        <x-save-button wire:loading.attr="disabled" onclick="guardarPlacasRX()">GUARDAR</x-save-button>
    </div>

    <div wire:loading wire:target="guardarPlacas, cancelarRegistroMedicamento">
        <x-cargando-dos />
    </div>

    <div id="cargando-guardar-medicamento" style="display: none;">
        <x-cargando-dos />
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    function CancelarRegistro() {
        $('#modalRegistrarPlacasRX').modal('hide');
    }


    function guardarPlacasRX() {
        Swal.fire({
            title: '¿Guardar Placas?',
            text: "Los datos de las placas serán registrados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarPlacasRX');
                document.getElementById('cargando-guardar-medicamento').style.display = 'block';
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Usted canceló el registro.",
                    icon: "warning"
                });
                Livewire.dispatch('cancelarRegistroPlacasRX');
                $('#modalRegistrarPlacasRX').modal('hide');
            }
        });
    }

    document.addEventListener('placas_registradas_g', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Las placas se registraron exitosamente.",
            icon: "success"
        });
        $('#modalRegistrarPlacasRX').modal('hide');
    });
</script>
