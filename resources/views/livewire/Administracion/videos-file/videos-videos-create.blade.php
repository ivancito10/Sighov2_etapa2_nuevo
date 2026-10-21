<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REGISTRO
                DE VIDEOS</h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="card card-comun p-2">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los datos
                        necesarios para el registro del video:</b> </em>
                <spam style="color:#cb4335;"><b>*</b></spam>
            </p>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                <label for="nombre">NOMBRE:</label>
                <input id="nombre" type="text"
                    class="form-control input-comun  @error('nombre') is-invalid @enderror"
                    placeholder="Ingrese el nombre del video ..." wire:model=nombre
                    style=" text-transform: uppercase;" />
                @error('nombre')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                <label for="titulo">TÍTULO:</label>
                <input id="titulo" type="text"
                    class="form-control input-comun  @error('titulo') is-invalid @enderror"
                    placeholder="Ingrese el titulo del video ..." wire:model=titulo
                    style=" text-transform: uppercase;" />
                @error('titulo')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                <label for="descripcion">DESCRIPCIÓN:</label>
                <textarea for="descripcion" type="text" id="descripcion"
                    class="form-control input-comun  @error('descripcion') is-invalid @enderror"
                    placeholder="Ingrese la descripción del video ..." wire:model=descripcion style=" text-transform: uppercase;"
                    row='3'> 
                </textarea>
                @error('descripcion')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2 ">
                <div wire:loading wire:target="video_file">
                    <x-cargando-dos />
                </div>

                @if ($video_file) 
                    <label for="video_file">
                        VIDEO SUBIDO
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path
                                d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                        </svg>
                    </label>
                @else
                    <label for="video_file">SUBIR VIDEO:</label>
                @endif

                <input type="file" wire:model="video_file" id ="video_file" class="form-control">
                @error('video_file')
                    <span class="text-danger">{{ $message }}</span>
                @enderror


            </div>


            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mt-2 ">
                <label for="customExterno"> TIPO:</label>
                <div class="col-sm-6 d-flex align-items-center">
                    <strong style="color:#2980b9">Interno</strong>
                    <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-red">
                        <input wire:model="externo" id="customExterno" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="customExterno"
                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                    </div>
                    <strong style="color:red">Externo</strong>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                <label for="customPermanente"> DURACIÓN:</label>
                <div class="col-sm-6 d-flex align-items-center">
                    <strong style="color:green">Temporal</strong>
                    <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-green">
                        <input wire:model.live="permanente" id="customPermanente" type="checkbox"
                            class="custom-control-input">
                        <label class="custom-control-label" for="customPermanente"
                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                    </div>
                    <strong style="color:#2980b9"> Permanente</strong>
                </div>
            </div>



            @if ($permanente === false)
                <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
                    <label for="fecha_inicio">FECHA INICIO:</label>
                    <input id="fecha_inicio" type="date"
                        class="form-control input-comun  @error('fecha_inicio') is-invalid @enderror"
                        placeholder="Ingrese fecha inicio del video ..." wire:model.live=fecha_inicio />
                    @error('fecha_inicio')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
                    <label for="fecha_fin">FECHA FIN:</label>
                    <input id="fecha_fin" type="date"
                        class="form-control input-comun  @error('fecha_fin') is-invalid @enderror"
                        placeholder="Ingrese fecha fin del video ..." wire:model.live=fecha_fin />
                    @error('fecha_fin')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
            @endif


        </div>
    </div>

    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="atras">
            CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarVideo()">GUARDAR</x-save-button>
    </div>

</div>
<script>
    function guardarVideo() {
        Swal.fire({
            title: '¿Guardar registro de Video?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cargando').style.display = 'block';
                Livewire.dispatch('crearVideitos');

            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                document.getElementById('cargando').style.display = 'none';

            }
        });
    }

    document.addEventListener('VideoRegistradoExito', function() {
        Swal.fire({
            title: "Registro correcto",
            text: "El video fue registrado correctamente",
            icon: "success",
        });
    });
</script>
