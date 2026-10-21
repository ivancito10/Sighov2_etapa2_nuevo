<x-small-modal id="editarModalUsuario" labelledby="editarModalUsuarioLabel" title="EDITAR USUARIO "
    saveAction="saveEdiUsuario()">

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12" align="center">
                    @if($this->fotografia==null)

                    <img src="{{ asset('images/default/user_default.jpg') }}" class="img-thumbnail" alt="..."
                        width="170" height="230">
                    @else
                    <img src="data:image/jpg;base64,{{ $this->fotografia }}" class="img-thumbnail" alt="..." width="170"
                        height="230">
                    @endif

                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 text-info">
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> <em>Ingrese los nuevos datos a modificar.</em></p>
                </div>
                <div class="col-lg-4">
                    <label for="name">Estado:</label>
                    @if($this->estado==true)
                    <em><b style="font-size: 18px;color: green;">Activo</b></em>
                    @else
                    <em><b style="font-size: 18px;color: red;">Inactivo</b></em>
                    @endif
                </div>

                <div class="col-lg-12">
                    <label for="name">Nombre del Usuario:</label>
                    <input type="text" id="name" class="form-control" wire:model="name">
                </div>
                <div class="col-lg-12">
                    <label for="name">Email Usuario:</label>
                    <input type="text" id="name" class="form-control" wire:model="email">
                </div>
                <div class="col-lg-4">
                    <label for="name">Fecha:</label>
                    <input type="text" id="name" class="form-control" disabled wire:model="fecha_creacion">
                </div>
                <div class="col-lg-8">
                    <label for="name">Registrado por:</label>
                    <input type="text" id="name" class="form-control" disabled wire:model="usuario_creador">
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="sexo"> ESTADO</label>
                    <div class="col-sm-6 d-flex align-items-center">
                        <label class="mr-2">INACTIVO</label>
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input wire:model="estado" type="checkbox" class="custom-control-input" id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2"
                                style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                        </div>
                        <label class="ml-2">ACTIVO</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-small-modal>

<script>
    window.addEventListener('modalEditarUsuarios', event => {
        $('#editarModalUsuario').modal('show');
    });


    function saveEdiUsuario() {
        Swal.fire({
            title: '¿Actualizar registro?',
            text: "Se actualizarán los datos ingresados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizar');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }

    document.addEventListener('EditadoCorrectamente', function () {
        Swal.fire({
            title: 'Actualizado!',
            text: "Se registro con éxito.",
            icon: 'success'
        });
        $('#editarModalUsuario').modal('hide');
    });
    document.addEventListener('EditadoIncorrectamente', function () {
        Swal.fire({
            title: 'Alerta!',
            text: "Se produjo un error!!",
            icon: 'error'
        });
        $('#editarModalUsuario').modal('hide');
    });
</script>