<div class="card">
    <div class="card-body">
        <div class="row" style="padding: 15px 20px;  ">
            <!--  Componente register -->
            <div class="col-4">
                @livewire('administracion.aportes.clase-aportante.clase-aportante-register')
            </div>

            <!-- Parte de búsqueda -->
            <div class="col-8" style="display: flex; align-items: center;">
                <button class="btn btn-outline-dark" type="submit" style="border: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>

                <input class="form-control" type="search" wire:model.live="search"
                    style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Buscar">
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr align="center" class="bg-info">
                    <th scope="col">NRO</th>
                    <th scope="col">NOMBRE</th>
                    <th scope="col">TIPO</th>
                    <th scope="col">PORCENTAJE</th>
                    <th scope="col">ESTADO</th>
                    <th scope="col">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach ($datos_clase_aportante as $claseaporta)
                    <tr style="padding: 15px;">
                        <td align="center" style="padding: 4px;">{{ $counter++ }}</td>
                        <td align="center" style="padding: 4px;">{{ $claseaporta->nombre }}</td>
                        <td align="center" style="padding: 4px;">{{ $claseaporta->tipo }}</td>
                        <td align="center" style="padding: 4px;">{{ $claseaporta->porcentaje }}</td>
                        <td align="center" style="padding: 4px;">
                            @if ($claseaporta->estado)
                                <span style="color: #227539;">ACTIVO</span>
                            @else
                                <span style="color: #DF3D0E ;">INACTIVO</span>
                            @endif

                        </td>
                        <td align="center" style="padding: 4px;">
                            <div class="row">
                                <!--    Boton Editar -->
                                <div class="col-sm-4">
                                    <x-edit-button wire:click="obtenerClaseAportante({{ $claseaporta->id }})"
                                        data-target="#modalEditar" data-toggle="modal"></x-edit-button>


                                    <!--         Modal para editar  -->
                                    <div class="modal fade" id="modalEditar" tabindex="-1"
                                        aria-labelledby="modalEditarLabel" aria-hidden="true">

                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title w-100 text-center text-primary"
                                                        id="modalEditarLabel">EDITAR CLASE
                                                        APORTANTE</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label for="outlined-basic"
                                                                class="col-sm-3 col-form-label">Nombre</label>
                                                            <div class="col-sm-9">
                                                                <select wire:model="nombre" class="select2 form-control">
                                                                    <option>Seleccione una clase</option>
                                                                    <option value="PATRONAL">PATRONAL</option>
                                                                    <option value="RENTISTA ">RENTISTA</option>
                                                                    <option value="TRASPASO">TRASPASO</option>
                                                                    <option value="VOLUNTARIO">VOLUNTARIO</option>
                                                                </select>
                                                                @error('nombre')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label for="outlined-basic"
                                                                class="col-sm-3 col-form-label">Tipo</label>
                                                            <div class="col-sm-9">
                                                                <select wire:model="tipo" class="select2 form-control">
                                                                    <option value="" disabled selected>Seleccione un tipo
                                                                    </option>
                                                                    <option value="PATRONAL">Patronal</option>
                                                                    <option value="RENTISTA JUBILADOS">Rentista Jubilado
                                                                    </option>
                                                                    <option value="RENTISTA POR SORTEO">Rentista por sorteo
                                                                    </option>
                                                                    <option value="TRASPASO">Traspaso</option>
                                                                    <option value="TRASPASO TGN">Traspaso TGN</option>
                                                                    <option value="VOLUNTARIO">Voluntario</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="outlined-basic"
                                                                class="col-sm-3 col-form-label">Porcentaje</label>
                                                            <div class="col-sm-9">
                                                                <input wire:model="porcentaje" type="number" step="0.00"
                                                                    class="form-control" id="outlined-basic"
                                                                    placeholder="Porcentaje">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <x-cancel-button data-dismiss="modal">
                                                        CANCELAR</x-cancel-button>
                                                    <x-save-button onclick="confirmSaveModifyCA()">
                                                        GUARDAR</x-save-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Boton Deshabilitar -->
                                <div class="col-sm-4">
                                    @if ($claseaporta->estado == true)
                                        <x-deshabilitar-button
                                            onclick="confirmDisable({{ $claseaporta->id }})"></x-deshabilitar-button>
                                    @else
                                        <x-habilitar-button
                                            onclick="confirmDisable({{ $claseaporta->id }})"></x-habilitar-button>
                                    @endif
                                </div>

                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        {{ $datos_clase_aportante->links() }}

    </div>


    <script>

        // Función para guardar los datos
        function saveDataModifyCA() {
            // Llama al método save() del componente Livewire
            Livewire.dispatch('editarClaseAportante');
            // Cierra el modal con el id "exampleModal"
            $('#modalEditar').modal('hide');
        }

        // Función para mostrar la alerta de confirmación
        function confirmSaveModifyCA() {
            Swal.fire({
                title: '¿Estás seguro de guardar?',
                text: "Se modificaran los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, llama a la función saveData()
                    saveDataModifyCA();
                }
            });
        }

        document.addEventListener('ModifyCA', function () {
            // Cierra el modal con el id "exampleModal"
            Swal.fire({
                title: "Se modificó correctamente!",
                text: "Los datos se han guardado exitosamente.",
                icon: "success"
            });
        });

        document.addEventListener('CancelModifyCA', function () {
            // Cierra el modal con el id "exampleModal"
            Swal.fire({
                title: "Se cancelo el registro!",
                text: "Completa todos los datos necesarios",
                icon: "error"

            });
        });

        function confirmDisable(idDisable) {
            Swal.fire({
                title: '¿Estás seguro de modificar el estado?',
                text: "Se modificará el estado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('buttonDisable', [idDisable]);
                }
            });
        }

        document.addEventListener('changeStatusDisabled', function () {
            // Cierra el modal con el id "exampleModal"
            Swal.fire({
                title: "Se deshabilitó correctamente!",
                icon: "error"
            });
        });

        document.addEventListener('changeStatusEnabled', function () {
            // Cierra el modal con el id "exampleModal"
            Swal.fire({
                title: "Se habilitó correctamente!",
                icon: "success"
            });
        });

    </script>
</div>