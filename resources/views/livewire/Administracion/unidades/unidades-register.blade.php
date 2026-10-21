<div>
    <div class="card">
        <div class="card-header" style="background-color: #154360">
            <h7 style="font-family: Verdana, sans-serif; text-align: left; color: white;">DATOS DE LA NUEVA UNIDAD</h7>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="nombre">NOMBRE</label>
                    <input wire:model="nombre" class="form-control" name="nombre" id="nombre"
                        placeholder="Ingrese el nombre de la unidad"  oninput="this.value = this.value.toUpperCase()">
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="sigla">SIGLA</label>
                    <input wire:model="sigla" class="form-control" name="sigla" id="sigla"
                        placeholder="Ingrese la sigla de la unidad" oninput="this.value = this.value.toUpperCase()">
                </div>
            </div>

            <div class="row mb-3">

                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="estado">ESTADO</label>
                    <select wire:model="estado" name="estado" id="estado" class="form-control" style="width: 100%;" tabindex="-1">
                        <option value=1 selected>Activo</option>
                        <option value=0>Inactivo</option>
                    </select>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="unidDependiente">UNIDAD DEPENDIENTE</label>
                    <select wire:model="unidDependiente" wire:change="obtenerUnidades" name="unidDependiente" id="unidDependiente"
                        class="form-control" style="width: 100%;" tabindex="-1">
                        <option selected="selected">Selecciona la Unidad Dependiente</option>
                        @foreach ($unidades as $unidad)
                            <option value="{{ $unidad->id }}">{{ $unidad->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <x-cancel-button wire:click="cancelarUnidad">CANCELAR</x-cancel-button>
        <x-save-button onclick="registrarUnidad()">GUARDAR</x-save-button>
    </div>

    <script>
        function registrarUnidad() {
            Swal.fire({
                title: '¿Guardar registro?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('crearNuevaUnidad');
                } else {
                    Livewire.dispatch('cancelarUnidad');
                }
            });
        }

        /*
        function guardarUnidad() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro correctamente",
                icon: "success"
            });
        }

        function cancelarUnidad() {
            Swal.fire({
                title: "Se canceló la operación",
                text: "Se cancelo el registro",
                icon: "error"
            });
        }
        */

        // Listeners de eventos de Livewire
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
    </script>

</div>
