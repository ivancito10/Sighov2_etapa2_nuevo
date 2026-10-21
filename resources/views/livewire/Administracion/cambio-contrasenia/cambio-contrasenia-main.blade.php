<div>
    <h2>Introduce tu nueva contraseña</h2>

    <form wire:submit.prevent="updatePassword">
        @csrf
        <!-- Contraseña actual -->
        <div class="form-group">
            <label for="current_password">Contraseña actual</label>
            <!--<input type="password" class="form-control" id="current_password" wire:model="current_password" required>-->
            <div class="input-group">
                <input type="password" class="form-control" id="current_password" wire:model="current_password" required>
                <!-- Botón para mostrar/ocultar contraseña -->
                <button type="button" class="btn btn-outline-secondary" title="VER"
                    onclick="togglePassword('current_password', this)">
                    <svg id="icon-eye-current_password" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path
                            d="M12 4.5C7.5 4.5 3.76 7.03 2 12c1.76 4.97 5.5 7.5 10 7.5s8.24-2.53 10-7.5C20.24 7.03 16.5 4.5 12 4.5z" />
                        <circle cx="12" cy="12" r="3.5" />
                    </svg>
                </button>
            </div>
            @error('current_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>


        <!-- Contraseña nueva -->
        <div class="form-group">
            <label for="new_password">Contraseña nueva</label>
            <div class="input-group">
            <input type="password" class="form-control" id="new_password" style="width: 10px;" wire:model.live="new_password" required>

                <button type="button" class="btn btn-outline-secondary" title="VER" onclick="togglePassword('new_password', this)" >
                    <svg id="icon-eye-new_password" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path
                            d="M12 4.5C7.5 4.5 3.76 7.03 2 12c1.76 4.97 5.5 7.5 10 7.5s8.24-2.53 10-7.5C20.24 7.03 16.5 4.5 12 4.5z" />
                        <circle cx="12" cy="12" r="3.5" />
                    </svg>
                </button>
            </div>
            @error('new_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!-- Mensaje de recomendación en tiempo real -->
            <!--small class="form-text text-muted">{{ $password_strength_message }}</small>-->
            <!-- Mensaje de recomendación en tiempo real -->
            <small class="form-text"
                style="color: {{ $password_strength_message === 'Contraseña segura.' ? 'green' : 'red' }};">
                {{ $password_strength_message }}
            </small>
        </div>

        <!-- Confirmar nueva contraseña -->
        <div class="form-group">
            <label for="confirm_password">Confirmar nueva contraseña</label>
            <div class="input-group">
                <input type="password" class="form-control" id="confirm_password" wire:model.live="confirm_password"
                    required>
                <button type="button" class="btn btn-outline-secondary" title="VER" onclick="togglePassword('confirm_password', this)">
                    <svg id="icon-eye-confirm_password" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                        <path
                            d="M12 4.5C7.5 4.5 3.76 7.03 2 12c1.76 4.97 5.5 7.5 10 7.5s8.24-2.53 10-7.5C20.24 7.03 16.5 4.5 12 4.5z" />
                        <circle cx="12" cy="12" r="3.5" />
                    </svg>
                </button>
            </div>
            @error('confirm_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <x-boton-azul style=" padding: 5px 10px; font-weight: bold;" wire:click="updatePassword"
            title="Cambiar Contrasenia">Cambiar contraseña</x-boton-azul>
    </form>
</div>

<script>
    function togglePassword(inputId, button) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById('icon-eye-' + inputId);

        if (input.type === "password") {
            input.type = "text";
            icon.innerHTML = `
                <path d="M12 4.5C7.5 4.5 3.76 7.03 2 12c1.76 4.97 5.5 7.5 10 7.5s8.24-2.53 10-7.5C20.24 7.03 16.5 4.5 12 4.5z" />
                <line x1="2" y1="2" x2="22" y2="22" stroke="currentColor" />
            `; // Cambia a ojo tachado
        } else {
            input.type = "password";
            icon.innerHTML = `
                <path d="M12 4.5C7.5 4.5 3.76 7.03 2 12c1.76 4.97 5.5 7.5 10 7.5s8.24-2.53 10-7.5C20.24 7.03 16.5 4.5 12 4.5z" />
                <circle cx="12" cy="12" r="3.5" />
            `; // Cambia a ojo abierto
        }
    }

    document.addEventListener('cambiado', function() {
        Swal.fire({
            title: 'Realizado!',
            text: "Se actualizó su contraseña con éxito.",
            icon: 'success'
        }).then(() =>{
            Livewire.dispatch('logoutNow');
        })
    });
</script>
