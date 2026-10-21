<div>
    <div class="card">
        <div class="card-header">
            <div class='row'>
                <div class='col-lg-3'>
                @livewire('Administracion.roles-permisos.roles-permisos-register')
                </div>
                
                <div class='col-lg-3'>
                @livewire('Administracion.roles-permisos.agregar-permiso-modal')
                </div>
            </div>
        </div>
    </div> 
    
    <div class="card">
        <div class="card-header" style="background-color:#154360">
            <h5 style="color: white; border-radius: 5px; text-align:center">NUEVOS ROLES</h5>
        </div>

        <div class="card-body">
            @livewire('Administracion.roles-permisos.roles-permisos-list')
        </div>
    </div>
     <!--Modal para ver el modal permiso lista  -->
     @include('livewire.Administracion.roles-permisos.modalPermisoLista')
<!--Modal para asignar permisos  -->
@include('livewire.Administracion.roles-permisos.modalAsignarPermisos')

     

    <div >
    <x-big-component  id="permissionsModal" labelledby="permissionsModalLabel" title="AGREGAR NUEVOS PERMISOS"
                saveAction="confirmSaveAsignarPermiso()" >
                
                <div class='row'>
                    

                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="rol" class="col-form-label" style='color:green;'> ROL :</label>
                            <p class="h5">{{$name}}</p>
                        </div>
                       
                    
                    </div>

                    <div class='col-lg-12'>
                    <br>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr align="center"  class="bg-info">
                                <th>NRO.</th>
                                <th>NAME</th>
                                <th>GUARD NAME</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $permission)
                                <tr class="text-center">
                                <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->guard_name }}</td>
                                    <td >
                                        <!-- <button wire:click="asignarPermiso({{ $permission->id }})"
                                        class="btn btn-primary">Assign</button> -->
                                        @if (in_array($permission->id, $permiso_asignado))
                                            <!-- BOTON REMOVER-->
                                            <button onclick="alertRemoverPermiso({{ $permission->id }})" class="btn btn-red">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg></button>
                                        @else
                                            <!-- BOTON AGREGAR-->
                                            <button onclick="alertAgregarPermiso({{ $permission->id }})" class="btn btn-green">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg></button>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    
                </div>
               
            </x-big-component>

    </div>

    
<script>
    
    document.addEventListener('livewire:load', function () {
        Livewire.on('CreadaRegisterRoles', () => {
            // Aquí puedes poner el código que deseas ejecutar cuando se emite el evento 'CreadaRegisterRoles'
            console.log('Se ha emitido el evento CreadaRegisterRoles');
            // Puedes realizar acciones como recargar datos, actualizar la interfaz, etc.
            Livewire.emit('actualizarListaRoles'); // Ejemplo de cómo podrías emitir otro evento desde JavaScript
        });
    });

    window.addEventListener('open-permissions-modal', event => {
        $('#permissionsModal').modal('show');
    });
  // Función para guardar los datos
        function saveDataAsingnarPermiso() {
            // Llama al método save() del componente Livewire
            Livewire.dispatch('guardarPermisos');
             // Emitir el evento 'actualizarTabla' para actualizar la tabla PowerGrid
            // Cierra el modal con el id "exampleModal"
            $('#permissionsModal').modal('hide');

        }

        // Función para mostrar la alerta de confirmación
        function confirmSaveAsignarPermiso() {
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
                    Livewire.dispatch('guardarPermisos');
                    $('#permissionsModal').modal('hide');
                }
            });


        }
       

        function rolYaExiste() {
            Swal.fire({
                title: "Ya existe un rol con ese nombre",
                text: "Se cancelo el registro",
                icon: "error"

            });
        }

        document.addEventListener('CreadaAsignarPermiso', function() {
            // Cierra el modal con el id "exampleModal"
            Swal.fire({
                title: "Guardado!",
                text: "Se registro exitosamente.",
                icon: "success"

            });
        });

       
        document.addEventListener('CanceladoAsignarPermiso', function() {
            // Cierra el modal con el id "exampleModal"
            Swal.fire({
                title: "Verifica todos los datos!",
                text: "Se cancelo el registro",
                icon: "error"

            });
        });


        // ==================================================================
         // Escuchar el evento despachado desde Livewire
         // Escuchar el evento emitido desde Livewire
        
        
        function alertAgregarPermiso( id_permiso) {
            
            Swal.fire({
                title: '¿Asignar Permiso?',
                text: "Se asignara el permiso.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, asignar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                Livewire.dispatch('addPermiso',  [id_permiso]); 
               
                }
            });
        }


        function alertRemoverPermiso(id_permiso) {
            Swal.fire({
                title: 'Remover Permiso?',
                text: "Se quitará el permiso.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                   
                    Livewire.dispatch('quitPermiso',[id_permiso]);
                }
            });
        }
        

        document.addEventListener('asignar', function() {
            Swal.fire({
                title: 'ASIGNADO!',
                text: 'Se asigno el permiso.',
                icon: 'success'
            });
            
        });
        document.addEventListener('quitar', function() {
            Swal.fire({
                title: 'QUITADO!',
                text: 'Se quito el permiso.',
                icon: 'error'
            });
        });


</script>

</div>