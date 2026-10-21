<div class="">

<x-agregar-button data-toggle="modal" data-target="#exModalPermiso">AGREGAR PERMISO</x-agregar-button>

    {{-- The Master doesn't talk, he acts. --}}
    <x-small-modal id="exModalPermiso" labelledby="exModalPermisoLabel" title="AGREGAR NUEVO PERMISO" saveAction="confirmSavePermiso()">
        <div class="form-group">
            <p class="h5"><em>Complete los datos.</em></p>
            <div class="row mb-6">
                <label for="permiso" class="col-sm-3 col-form-label">PERMISO:</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" wire:model="name" placeholder ='Introduzca un nuevo permiso'>
                </div>
            </div> 
        </div>
    </x-small-modal>
     <!--<h1>{{ $alert }}</h1>-->

    <script>
        // Función para guardar los datos

        // Función para mostrar la alerta de confirmación
        function confirmSavePermiso() {
            Swal.fire({
                title: '¿Guardar?',
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
                    Livewire.dispatch('crearpermiso');
                    $('#exModalPermiso').modal('hide');
                }
            });


        }
        //fucniona de exito 
        function showSuccessAlertPermiso() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro exitosamente.",
                icon: "success"

            });
        }

        function showCancelAlertPermiso() {
            Swal.fire({
                title: "Cancelado!",
                text: "Completa todos los datos, se cancelo el registro",
                icon: "error"

            });
        }

        function permisoYaExiste() {
            Swal.fire({
                title: "Ya existe un permiso con el mismo nombre",
                text: "Se cancelo el registro",
                icon: "error"

            });
        }

        document.addEventListener('CreadaRegisterPermiso', function() {
            // Cierra el modal con el id "exampleModal"
            showSuccessAlertPermiso();
        });
        document.addEventListener('CanceladoRegisterPermiso', function() {
            // Cierra el modal con el id "exampleModal"
            showCancelAlertPermiso();
        });
        document.addEventListener('PermisoExiste', function() {
            permisoYaExiste();
        });
    </script>
</div>
