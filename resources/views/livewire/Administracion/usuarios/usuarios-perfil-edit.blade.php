<div class="container mt-4">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Datos del Usuario</h3>
        </div>
        <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Imagen de Perfil -->
                    <div class="me-4 text-center">
                        <div class="form-group">
                            @if ($imagen)
                                <img src="{{ $imagen->temporaryUrl() }}" class="mt-2 img-thumbnail" style="width: 350px; height: 250px; object-fit: cover;">
                            @elseif($user->imagen)
                                <img src="{{ Auth::user()->fotoUsuario() }}" class="mt-2 img-thumbnail" style="width: 350px; height: 250px; object-fit: cover;">
                            @else
                                <img src="{{ asset('img/default-user.png') }}" class="mt-2 img-thumbnail" style="width: 350px; height: 250px; object-fit: cover;">
                            @endif

                            <input type="file" class="form-control mt-2 @error('imagen') is-invalid @enderror" wire:model="imagen" id="imagen">
                            
                            @error('imagen')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div style="margin: 4; padding: 0;">
                    </div>
                    <!-- Datos del Usuario -->
                    <div class="w-100 ms-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" wire:model="name" id="name" disabled>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Usuario SIGHOv2</label>
                                    <input type="email" class="form-control" wire:model="email" id="email" disabled>
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ci">Cédula de Identidad</label>
                                    <input type="text" class="form-control" wire:model="ci" id="ci" disabled>
                                    @error('ci') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="matricula">Matricula SSU</label>
                                    <input type="text" class="form-control" wire:model="matricula" id="matricula" disabled>
                                    @error('matricula') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_asegurado">Tipo de Asegurado</label>
                                    <span class="badge" style="font-size: 1rem; padding: 10px; 
                                        background-color: {{ $this->tipo_asegurado == 1 ? 'blue' : ($this->tipo_asegurado == 2 ? 'green' : ($this->tipo_asegurado == 3 ? 'orange' : 'red')) }}; 
                                        color: white;">
                                        {{ $this->tipo_asegurado == 1 ? 'Titular' : ($this->tipo_asegurado == 2 ? 'Beneficiario' : ($this->tipo_asegurado == 3 ? 'Estudiante' : 'No Asegurado')) }}
                                    </span>
                                </div>
                            </div>
                            @if($tipo_asegurado != 0)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="correo">Correo Electrónico</label>
                                    <input type="correo" class="form-control" wire:model="correo" id="correo" disabled>
                                    @error('correo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-cancel-button wire:click="cancelarPerfil">CANCELAR</x-cancel-button>
                    <x-save-button onclick="actualizarPerfil()">GUARDAR</x-save-button>
                </div>
        </div>
    </div>
</div>

<script>
    function actualizarPerfil() {
        Swal.fire({
            title: '¿Desea actualizar sus datos?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarPerfil');
            } else {
                Livewire.dispatch('cancelarUnidad');
            }
        });
    }
    document.addEventListener('unidadCreada', function() {
        Swal.fire({
                title: "Guardado!",
                text: "Se registro correctamente.",
                icon: "success"
            });
    });
    document.addEventListener('unidadCancelada', function() {
        //cancelarUnidad();
    });

    document.addEventListener('imagenInvalida', function() {
        Swal.fire({
            title: "Archivo no válido",
            text: "El archivo seleccionado no es una imagen válida.",
            icon: "error"
        });
    });
</script>
