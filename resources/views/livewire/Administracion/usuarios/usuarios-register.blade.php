<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                REGISTRO DE NUEVO ESPECIALISTA
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="cancelarUsuario" wire:loading.attr="disabled" wire:target="cancelarUsuario">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="cancelarUsuario">
                <x-cargando-dos />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body ">
                    <div class="cabecera text-center">
                        <b>DATOS REGISTRO DE USUARIO</b>
                    </div>
                    <div class="row">

                        <div class="col-lg-4">
                            <label for="">Nro. C.I.</label>
                            <input class="form-control" type="number" placeholder="Número de CI... " wire:model="ci_b"
                                min="0" step="1" oninput="this.value = this.value.replace(/[^0-9]/g,'');">
                        </div>
                        <div class="col-lg-2">
                            <label for="">Complemento</label>
                            <input type="text" class="form-control" placeholder="Complemento del CI..."
                                max="3" wire:model="complemento_b"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-lg-1">
                            <label for="">Buscar</label>
                            <button class=" form-control btn-outline-success" wire:click="BuscarPersona"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-database-fill-check" viewBox="0 0 16 16">
                                    <path
                                        d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M8 1c-1.573 0-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4s.875 1.755 1.904 2.223C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777C13.125 5.755 14 5.007 14 4s-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1" />
                                    <path
                                        d="M2 7v-.839c.457.432 1.004.751 1.49.972C4.722 7.693 6.318 8 8 8s3.278-.307 4.51-.867c.486-.22 1.033-.54 1.49-.972V7c0 .424-.155.802-.411 1.133a4.51 4.51 0 0 0-4.815 1.843A12 12 0 0 1 8 10c-1.573 0-3.022-.289-4.096-.777C2.875 8.755 2 8.007 2 7m6.257 3.998L8 11c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13h.027a4.55 4.55 0 0 1 .23-2.002m-.002 3L8 14c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-1.3-1.905" />
                                </svg></button>
                        </div>
                        <div wire:loading wire:target="BuscarPersona">
                            <div class="spinner-grow text-warning" role="status">

                            </div>
                            <b><i>Cargando datos espere porfavor...</i></b>
                        </div>
                        <div class="col-lg-5">
                            @if ($this->encontrado === 1)
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-database-check" viewBox="0 0 16 16">
                                        <path
                                            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514" />
                                        <path
                                            d="M12.096 6.223A5 5 0 0 0 13 5.698V7c0 .289-.213.654-.753 1.007a4.5 4.5 0 0 1 1.753.25V4c0-1.007-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1s-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4v9c0 1.007.875 1.755 1.904 2.223C4.978 15.71 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-.813-.927Q8.378 15 8 15c-1.464 0-2.766-.27-3.682-.687C3.356 13.875 3 13.373 3 13v-1.302c.271.202.58.378.904.525C4.978 12.71 6.427 13 8 13h.027a4.6 4.6 0 0 1 0-1H8c-1.464 0-2.766-.27-3.682-.687C3.356 10.875 3 10.373 3 10V8.698c.271.202.58.378.904.525C4.978 9.71 6.427 10 8 10q.393 0 .774-.024a4.5 4.5 0 0 1 1.102-1.132C9.298 8.944 8.666 9 8 9c-1.464 0-2.766-.27-3.682-.687C3.356 7.875 3 7.373 3 7V5.698c.271.202.58.378.904.525C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777M3 4c0-.374.356-.875 1.318-1.313C5.234 2.271 6.536 2 8 2s2.766.27 3.682.687C12.644 3.125 13 3.627 13 4c0 .374-.356.875-1.318 1.313C10.766 5.729 9.464 6 8 6s-2.766-.27-3.682-.687C3.356 4.875 3 4.373 3 4" />
                                    </svg>
                                    <strong> Encontrado en la BD!

                                    </strong>
                                    </button>
                                </div>
                            @endif
                            @if ($this->encontrado === 2)
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-database-fill-exclamation" viewBox="0 0 16 16">
                                        <path
                                            d="M8 1c-1.573 0-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4s.875 1.755 1.904 2.223C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777C13.125 5.755 14 5.007 14 4s-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1" />
                                        <path
                                            d="M2 7v-.839c.457.432 1.004.751 1.49.972C4.722 7.693 6.318 8 8 8s3.278-.307 4.51-.867c.486-.22 1.033-.54 1.49-.972V7c0 .424-.155.802-.411 1.133a4.51 4.51 0 0 0-4.815 1.843A12 12 0 0 1 8 10c-1.573 0-3.022-.289-4.096-.777C2.875 8.755 2 8.007 2 7m6.257 3.998L8 11c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13h.027a4.55 4.55 0 0 1 .23-2.002m-.002 3L8 14c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-1.3-1.905" />
                                        <path
                                            d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1.5a.5.5 0 0 0 1 0V11a.5.5 0 0 0-.5-.5m0 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                    </svg>
                                    <strong>Se encontró en el SEGIP!</strong>
                                    </button>
                                </div>
                            @endif
                            @if ($this->encontrado === 0)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-database-fill-exclamation" viewBox="0 0 16 16">
                                        <path
                                            d="M8 1c-1.573 0-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4s.875 1.755 1.904 2.223C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777C13.125 5.755 14 5.007 14 4s-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1" />
                                        <path
                                            d="M2 7v-.839c.457.432 1.004.751 1.49.972C4.722 7.693 6.318 8 8 8s3.278-.307 4.51-.867c.486-.22 1.033-.54 1.49-.972V7c0 .424-.155.802-.411 1.133a4.51 4.51 0 0 0-4.815 1.843A12 12 0 0 1 8 10c-1.573 0-3.022-.289-4.096-.777C2.875 8.755 2 8.007 2 7m6.257 3.998L8 11c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V10c0 1.007.875 1.755 1.904 2.223C4.978 12.711 6.427 13 8 13h.027a4.55 4.55 0 0 1 .23-2.002m-.002 3L8 14c-1.682 0-3.278-.307-4.51-.867-.486-.22-1.033-.54-1.49-.972V13c0 1.007.875 1.755 1.904 2.223C4.978 15.711 6.427 16 8 16c.536 0 1.058-.034 1.555-.097a4.5 4.5 0 0 1-1.3-1.905" />
                                        <path
                                            d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1.5a.5.5 0 0 0 1 0V11a.5.5 0 0 0-.5-.5m0 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                    </svg>
                                    <strong> No se encontró en la BD!</strong>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-12">
                            <br>
                            <div class="row">
                                @if ($this->encontrado === 1 || $this->encontrado === 2)
                                    @if ($this->validacion_usuario != 1)
                                        <div class="col-lg-12">
                                            <p style="background-color:#000000 ; color:white; text-align:center;">
                                                <b>DATOS EN LA BASE DE
                                                    DATOS</b>
                                            </p>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label for="formFile" class="form-label">SELECCIONAR FOTO: </label>
                                                    <input class="form-control @error('foto') is-invalid @enderror"
                                                        type="file" id="formFile" name="archivo" required
                                                        wire:model="foto" accept="image/*">
                                                    @error('foto')
                                                        <div class="text-danger">
                                                            <b>{{ $message }}</b>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6">
                                                    <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                                                    @if ($foto)
                                                        <img src="{{ $foto->temporaryUrl() }}"
                                                            style=" box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.5); width: 150px; height: 150px; object-fit: cover;"
                                                            class="img-thumbnail">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @include('livewire.Administracion.usuarios.DatosRegistroUsuario')
                                    @endif
                                @elseif($this->encontrado === 0)
                                    <div class="col-lg-12">
                                        <p style="background-color:#000000 ; color:white; text-align:center;">
                                            <b>REGISTRO MANUAL</b>
                                        </p>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="formFile" class="form-label">SELECCIONAR FOTO: </label>

                                                <input class="form-control @error('foto') is-invalid @enderror"
                                                    type="file" id="formFile" name="archivo" required
                                                    wire:model="foto" accept="image/*">
                                                @error('foto')
                                                    <div class="text-danger">
                                                        <b>{{ $message }}</b>
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-6">
                                                <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                                                @if ($foto)
                                                    <img src="{{ $foto->temporaryUrl() }}"
                                                        style=" box-shadow: 0 0 10px 5px rgba(0, 0, 0, 0.5); width: 200px; height: 200px; object-fit: cover;"
                                                        class="img-thumbnail">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @include('livewire.Administracion.usuarios.DatosRegistroUsuarioManual')

                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($this->validacion_usuario != 1)
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body" style="overflow: auto; max-height: 550px;padding-right: 10px;">
                        <p style="background-color:#02b4ccc4 ; color:white; text-align:center;"><b> ASIGNAR ROL</b></p>
                        @foreach ($this->roles as $role)
                            <div>
                                <input type="checkbox" id="role_{{ $role->name }}" name="roles[]"
                                    wire:model="roles_select" value="{{ $role->name }}">
                                <label for="role_{{ $role->name }}">{{ $role->name }}</label>

                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        @endif
        <div class="col-lg-12">
            <x-save-button onclick="saveUsuario()">Registrar</x-save-button>
            <x-salir-button wire:click="cancel">Volver</x-salir-button>
        </div>
    </div>
    <script>
        function saveUsuario() {
            Swal.fire({
                title: '¿Guardar registro?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarUsuario');
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }
        document.addEventListener('ErrorYaExisteUsuario', function() {
            Swal.fire({
                title: 'Alerta!',
                text: "Ya existe un usuario asignado a la persona!!!",
                icon: 'error'
            });
        });

        document.addEventListener('sin_ci', function() {
            Swal.fire({
                title: 'Alerta!',
                text: "El campo ci es obligatorio!!!",
                icon: 'error'
            });
        });

        document.addEventListener('error_fecha_nacimiento', function() {
            Swal.fire({
                title: 'Alerta!',
                text: "Error en la fecha de nacimiento!!!",
                icon: 'error'
            });
        });
        
    </script>
</div>
