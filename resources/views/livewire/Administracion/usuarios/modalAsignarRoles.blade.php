<x-big-component id="asignarRoles" labelledby="asignarRolesLabel" title=" ASIGNAR ROLES "
    saveAction="saveAsignarroles()">

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p style="background-color:ORANGE ; color:white; text-align:center;"> <b>DATOS USUARIO</b>
                            </p>
                        </div>
                        <div class="col-lg-6">

                            <label for="name">Nombre Usuario:</label>
                            <input type="text" class="form-control" disabled wire:model="name">
                        </div>
                        <div class="col-lg-6">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control" disabled wire:model="email">
                        </div>
                        <div class="col-lg-6">
                            <label for="fecha_registro">Fecha Registro:</label>
                            <input type="text" class="form-control" disabled wire:model="fecha_registro">
                        </div>
                        <div class="col-lg-6">
                            <label for="usuario">Usuario Registro:</label>
                            <input type="text" class="form-control" disabled wire:model="usuario">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card" style="max-height: 800px; overflow-y: auto;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p style="background-color:green ; color:white; text-align:center;"> <b>SELECCIONAR LOS
                                    ROLES</b>
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-info">
                            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg> <em>Seleccione los roles para asignar.</em></p>
                        </div>
                        <div class="col-lg-12">
                            @foreach($this->lista_roles as $role)
                            <div>
                                <label>
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                        wire:model="roles_asignados" @if($this->roles_asignados->contains($role->name))
                                    checked @endif >
                                    {{ $role->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-big-component>

<script>

    function saveAsignarroles() {
        Swal.fire({
            title: '¿Asignar Roles?',
            text: "Se asignarán los roles seleccionados",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, asignar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('asignarRoles');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló la asignación",
                    icon: "error"
                });
            }
        });
    }
    window.addEventListener('modalAsignarrol', event => {

        $('#asignarRoles').modal('show');
    });
</script>