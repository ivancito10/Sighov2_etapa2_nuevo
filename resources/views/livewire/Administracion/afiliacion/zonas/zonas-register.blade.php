<div>
    <div class="card">
        <div class="card-header" style="background-color: #154360">
            <h7 style="font-family: Verdana, sans-serif ; text-align: left; color:white">DATOS DE LA NUEVA ZONA</h7>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="idDepartamento">DEPARTAMENTO</label>
                    <select wire:model="idDepartamento" wire:change="obtenerProvincias" name="idDepartamento"
                        id="idDepartamento" class="form-control" style="width: 100%;" tabindex="-1" required>
                        <option selected="selected">Selecciona el departamento </option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <label for="idProvincia">PROVINCIA</label>
                    <select wire:model="idProvincia" wire:change="obtenerMunicipio" name="idProvincia" id="idProvincia"
                        class="form-control" style="width: 100%;" tabindex="-1">
                        <option selected="selected">Selecciona la provincia </option>
                        @foreach ($provincias as $provincia)
                            <option value="{{ $provincia->id }}">{{ $provincia->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="idMunicipio">MUNICIPIO</label>
                    <select wire:model="idMunicipio" name="idMunicipio" id="idMunicipio" class="form-control"
                        style="width: 100%;" tabindex="-1" required>
                        <option selected="selected">Selecciona el municipio </option>
                        @foreach ($municipios as $municipio)
                            <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="nombreZona">ZONA</label>
                    <input wire:model="nombreZona" class="form-control" name="nombreZona" id="nombreZona"
                        placeholder="Ingrese el nombre de la Zona"  oninput="this.value = this.value.toUpperCase()">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-cancel-button wire:click="cancelarZona">CANCELAR</x-cancel-button>
        <x-save-button onclick="registrarZona()">GUARDAR</x-save-button>
    </div>

    <script>
        function registrarZona() {
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
                    Livewire.dispatch('crearNuevaZona');
                } else {
                    Livewire.dispatch('cancelarZona');
                }
            });
        }

        /*
        function guardarZona() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro correctamente",
                icon: "success"

            });
        }

        function cancelarZona() {
            Swal.fire({
                title: "Se canceló la operación",
                text: "Se cancelo el registro",
                icon: "error"

            });
        }


        document.addEventListener('zonaCreada', function() {
            guardarZona();
        });
        document.addEventListener('zonaCancelada', function() {
            cancelarZona();
        });
        */
    </script>


</div>
