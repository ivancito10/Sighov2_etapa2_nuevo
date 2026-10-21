<div>
    <div class="input-group pr-4" style="justify-content: space-between">
        <h4 style="color: black">ADMINISTRACIÓN - <b>LISTA DE USUARIOS</b></h4>
        @can('admin.registro_usuario')
            <!--BOTON PARA AGREGAR NUEVO USUARIO-->
            <x-agregar-button wire:click="redirectToForm" title="REGISTRAR USUARIO">
                REGISTRAR</x-agregar-button>
        @endcan
    </div>

    <div class="card-body pt-2 pr-0 pl-0">

        <div class="card-body">
            @can('admin.lista_usuarios')
                @livewire('Administracion.usuarios.usuarios-table')
            @endcan
            @can('admin.ver_usuario')
                <!--Modal para ver usuario-->
                @include('livewire.Administracion.usuarios.modalVerUsuarios')
            @endcan
            @can('admin.editar_usuario')
                <!--Modal para editar usuario-->
                @include('livewire.Administracion.usuarios.modalEditarUsuarios')
            @endcan
            @can('admin.cambiar_contraseña_usuario')
                <!--Modal para cambiar contraseña del usuario-->
                @include('livewire.Administracion.usuarios.modalCambiarContraseña')
            @endcan
            @can('admin.asignar_rol_usuarios')
                <!--Modal para asignar roles-->
                @include('livewire.Administracion.usuarios.modalAsignarRoles')
            @endcan

            {{-- resources/views/Administracion/Usuarios/pdfCredencialesUsuario.blade.php --}}
            {{-- @include('Administracion.Usuarios.pdfCredencialesUsuario') --}}



        </div>
    </div>
    <script>
        document.addEventListener('ContraseñaReseteada', function() {
            Swal.fire({
                title: 'Actualizado!',
                text: "Se Reseteó la contraseña con éxito.",
                icon: 'success'
            });
        });

        document.addEventListener('ContraseñaReseteadaError', function() {
            Swal.fire({
                title: 'Alerta!',
                text: "Se produjo un error no se pudo resetear la contraseña!!!",
                icon: 'error'
            });
        });
        document.addEventListener('AsignacionRolesCorrectamente', function() {
            $('#asignarRoles').modal('hide');
            Swal.fire({
                title: 'Actualizado!',
                text: "Se asignó los roles con éxito",
                icon: 'success'
            });
        });

        document.addEventListener('AsignacionRolesIncorrectamente', function() {
            $('#asignarRoles').modal('hide');
            Swal.fire({
                title: 'Alerta!',
                text: "Se produjo un error al asignar los roles al usuario!!!",
                icon: 'error'
            });
        });

        document.addEventListener('activo', function() {
            Swal.fire({
                title: 'Activo!',
                text: "Se actualizo con éxito.",
                icon: 'success'
            });
        });


        document.addEventListener('inactivo', function() {
            Swal.fire({
                title: 'Inactivo!',
                text: "Se actualizo con éxito.",
                icon: 'error'
            });
        });
    </script>
</div>
