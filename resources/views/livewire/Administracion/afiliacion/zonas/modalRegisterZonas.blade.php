<!-- Modal de registro de zonas -->
<div>
{{-- BORIS :V --}}
    <x-small-modal id="modalZona" labelledby="modalZonaLabel" title="AGREGAR NUEVA ZONA" saveAction="">
        <div class="form-group">
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="idDepartamento">DEPARTAMENTO</label>
                    <select wire:model="idDepartamento" wire:click="obtenerProvincias" name="idDepartamento" id="idDepartamento" class="form-control"
                        style="width: 100%;" tabindex="-1" required>
                        <option selected="selected">Selecciona el departamento </option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="idProvincia">PROVINCIA</label>
                    <select wire:model="idProvincia" name="idProvincia" id="idProvincia" class="form-control"
                        style="width: 100%;" tabindex="-1">
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

                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="zona">ZONA</label>
                    <input wire:model="zona" class="form-control" name="zona" id="zona"
                        placeholder="Ingrese el nombre de la Zona">
                </div>
            </div>
        </div>
    </x-small-modal>
</div>
<!--<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('aporteVoluntarioCreado', (event) => {
            alert('JAJAJAJA SI ESCUCHA LOCOOOO');
            $('#modalZona').modal('show');
        });
    });
</script>-->
