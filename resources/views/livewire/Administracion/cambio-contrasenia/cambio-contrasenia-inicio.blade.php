<x-small-modal-no-close id="verModalCambioContrasenia" labelledby="verModalCambioContrasenia" title="CAMBIO DE CONTRASEÑA">
    <div class="content">
        <div class="modal-body px-4 py-3">
            <div class="d-flex justify-content-center">
                <img src="/logo/logo2.png" alt="login-icon" width="400" height="200" />
            </div>
            <p class="text-muted mb-4" style="text-align: justify;">
                Por motivos de seguridad y con el objetivo de proteger su información personal, 
                la Unidad de Tecnologías de la Información y Comunicación del Seguro Social Universitario La Paz 
                le solicita que actualice su contraseña durante este primer inicio de sesión.
            </p>
            <form wire:submit.prevent="save">
                <!-- Nueva contraseña -->
                <div class="form-group mb-3">
                    <label for="password" class="form-label fw-bold">Nueva Contraseña</label>
                    <div class="input-group">
                        <input type="password" id="password" wire:model="password" class="form-control" placeholder="Ingrese su nueva contraseña">
                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <small class="form-text" style="color: {{ $password_strength_message === 'Contraseña segura.' ? 'green' : 'red' }};">
                        {{ $password_strength_message }}
                    </small>
                </div>
                <!-- Confirmar contraseña -->
                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label fw-bold">Confirmar Contraseña</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" wire:model="password_confirmation" class="form-control" placeholder="Confirme su nueva contraseña">
                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password_confirmation') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>
                <!-- Botón -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</x-small-modal-no-close>

    <!-- Script para mostrar y ocultar el modal -->
    <script>;

        window.addEventListener('verModalCambioContrasenia', event => {
            $('#verModalCambioContrasenia').modal('show');
        });

        window.addEventListener('password-updated',event =>{
            Swal.fire({
            icon: 'success',
            title: 'Su contraseña se actualizo con exito',
            confirmButtonText: 'Aceptar',
            }).then(() => {
                //$('#verModalCambioContrasenia').modal('hide'); // Cierra el modal
                Livewire.dispatch('logoutNow');
            });
        })
        document.addEventListener('errorContrasenia', function (event) {
            //console.log(event.detail);
            document.querySelector('.wrapper').setAttribute('aria-hidden', 'false');
            const mensaje = event.detail[0].message;

            //console.log(mensaje);
            Swal.fire({
                icon: 'warning',
                title: 'Verifica tu contraseña',
                text: mensaje,
                confirmButtonText: 'Aceptar',
            });
        });

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
            } else {
                field.type = "password";
            }
    }
    </script>

