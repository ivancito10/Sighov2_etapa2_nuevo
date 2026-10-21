<div class="">
    {{-- The whole world belongs to you. --}}
    <x-agregar-button data-toggle="modal" data-target="#exModal">AGREGAR ROL</x-agregar-button>
    
        <!-- esto es iniciando el registro en un modal -->
    <x-small-modal id="exModal" labelledby="exModalLabel" title="AGREGAR ROL" saveAction="confirmSaveRol()">
    <div class="form-group">
            <p class="h5"><em>Complete los datos.</em></p>
            <div class="row mb-6">
                <label for="permiso" class="col-sm-3 col-form-label">ROL:</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" wire:model="name" placeholder ='Introduzca un nuevo rol'>
                </div>
            </div> 
        </div>
    </x-small-modal>

    <script>
        // Función para guardar los datos
        function saveDataRol() {
            // Llama al método save() del componente Livewire
            Livewire.dispatch('save');
             // Emitir el evento 'actualizarTabla' para actualizar la tabla PowerGrid
            // Cierra el modal con el id "exampleModal"
            $('#exModal').modal('hide');

        }

        // Función para mostrar la alerta de confirmación
        function confirmSaveRol() {
            Swal.fire({
                title: '¿Estás seguro de guardar?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, llama a la función saveData()
                    saveDataRol();

                }
            });
        }
        //fucniona de exito 
        function showSuccessAlertRol() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro exitosamente.",
                icon: "success"

            });
        }

        function showCancelAlertRol() {
            Swal.fire({
                title: "Verifica todos los datos!",
                text: "Se cancelo el registro",
                icon: "error"

            });
        }

        function rolYaExiste() {
            Swal.fire({
                title: "Ya existe un rol con ese nombre",
                text: "Se cancelo el registro",
                icon: "error"

            });
        }

        document.addEventListener('CreadaRegisterRoles', function() {
            // Cierra el modal con el id "exampleModal"
            showSuccessAlertRol();
        });
        document.addEventListener('CanceladoRegisterRoles', function() {
            // Cierra el modal con el id "exampleModal"
            showCancelAlertRol();
        });
        document.addEventListener('RolExiste', function() {
            rolYaExiste();
        });
    </script>
</div>
    