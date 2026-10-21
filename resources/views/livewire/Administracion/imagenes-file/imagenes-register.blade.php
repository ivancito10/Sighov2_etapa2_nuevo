<div>
    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header p-1 cabecera " >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                    </svg>
                    <b >REGISTRAR IMAGEN:</b>
                </div>
                
                <div class="row">
                    @if ($foto)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#2471a3"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                <em class="ml-2" style="color:#2471a3"><b>Imagen seleccionada</b> </em>
                            </p>
                        </div>
                    @else
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="#5499c7 " class="bi bi-upload" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                    <path
                                        d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                                </svg><em class="ml-2" style="color:#5499c7 "><b>Seleccionar imagen:</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    @endif
                    <input class="form-control form-control-sm @error('foto') is-invalid @enderror" type="file"
                        id="formFile" name="archivo" required wire:model="foto" accept=".jpg,.jpeg,.png">
                            @error('foto')
                                <div class="text-danger">
                                    <b>Este campo es obligatorio en formato JPG.</b>
                                </div>
                            @enderror
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12" align="center">
                        <div wire:loading wire:target="foto">
                            <x-cargando-dos />
                        </div>

                        @if ($foto)
                            @if (Str::startsWith($foto->getMimeType(), 'image/'))
                                <img src="{{ $foto->temporaryUrl() }}" class="img-thumbnail">
                            @endif
                        @endif


                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-7 col-md-12 col-sm-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#1a5276 ;" class="h5"><svg
                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg> <em><b>Ingresar los siguientes campos:</b> </em>
                            
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <strong> NRO. DE IMAGEN: <span style="color:#cb4335;"><b>*</b></span></strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px; text-align:center;">
                                {{ $id_ultimo ?? 'sin dato'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="descripcion">DESCRIPCIÓN:<span style="color:#cb4335;"><b>*</b></span></label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion"
                            wire:model="descripcion" placeholder="Introduzca la descripción.." style="border-radius: 10px;"
                            oninput="this.value = this.value.toUpperCase()" rows="3"></textarea>
                            @error('descripcion')
                                <div class="text-danger">
                                    <b>Este campo es obligatorio.</b>
                                </div>
                            @enderror
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:right;">
                        <x-salir-button wire:click="cancelar_registro" title="CANCELAR REGISTRO"> CANCELAR</x-salir-button>
                        <x-save-button onclick="registrarImagen()" title="GUARDAR REGISTRO"> GUARDAR</x-save-button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function registrarImagen() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardar_imagen');
                $('#registrarModal').modal('hide');
            } else if (result.isDismissed) {
                Livewire.dispatch('cancelar_registro');
                $('#registrarModal').modal('hide');
            }
        });
    }

    document.addEventListener('guardado_imagen', function() {
        Swal.fire({
            title: "Guardado!",
            text: "El archivo se registro correctamente.",
            icon: "success"
        });
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('cerrarModal', () => {
            $('#registrarModal').modal('hide');
        });
    });
</script>
