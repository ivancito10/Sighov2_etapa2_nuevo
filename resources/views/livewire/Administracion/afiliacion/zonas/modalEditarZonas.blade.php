<!-- Modal de editar de zonas -->
<x-small-modal id="modalZonaEditar" labelledby="modalZonaEditarLabel" title="MODIFICAR ZONA" saveAction="editarZona()">
    <div class="form-group">
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="departamentoEdit">DEPARTAMENTO</label>
                <input wire:model="departamentoEdit" class="form-control" name="departamentoEdit" id="departamentoEdit"
                    disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="provinciaEdit">PROVINCIA</label>
                <input wire:model="provinciaEdit" class="form-control" name="provinciaEdit" id="provinciaEdit" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="municipioEdit">MUNICIPIO</label>
                <input wire:model="municipioEdit" class="form-control" name="municipioEdit" id="municipioEdit" disabled>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="zonaEdit">ZONA</label>
                <input wire:model="zonaEdit" class="form-control" name="zonaEdit" id="zonaEdit"
                    placeholder="Ingrese el nombre de la Zona">
            </div>
        </div>
    </div>
</x-small-modal>


<script>
    window.addEventListener('abrirModalEditar', event => {
        $('#modalZonaEditar').modal('show');
    });

    function editarZona() {
        Swal.fire({
            title: '¿Guardar cambios?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarZona');
                $('#modalZonaEditar').modal('hide');
            } else {
                Swal.fire({
                    title: "No se guardaron los cambios",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#modalZonaEditar').modal('hide');
            }
        });

        function zonaModificada() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro correctamente",
                icon: "success"

            });
        }

        function zonaCancelada() {
            Swal.fire({
                title: "Datos Incorrectos",
                text: "Se cancelo el registro",
                icon: "error"

            });
        }

        function sinNombre() {
            Swal.fire({
                title: "Introduzca el nombre de la zona",
                text: "Se cancelo el registro",
                icon: "error"

            });
        }


        document.addEventListener('zonaActualizada', function() {
            zonaModificada();
        });
        document.addEventListener('zonaNoActualizada', function() {
            zonaCancelada();
        });
        document.addEventListener('datoIncompleto', function() {
            sinNombre();
        });
    }
</script>
