<!-- Modal de editar de unidades -->
<x-small-modal id="modalUnidadEditar" labelledby="modalUnidadEditarLabel" title="MODIFICAR UNIDAD" saveAction="editarUnidad()">
    <div class="form-group">
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="nombreUnid">NOMBRE</label>
                <input wire:model="nombreUnid" class="form-control" name="nombreUnid" id="nombreUnid">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="siglaUnid">SIGLA</label>
                <input wire:model="siglaUnid" class="form-control" name="siglaUnid" id="siglaUnid">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="estadoUnid">ESTADO</label>
                <input wire:model="estadoUnid" class="form-control" name="estadoUnid" id="estadoUnid">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="UnidadDep">UNIDAD DEPENDIENTE</label>
                <input wire:model="UnidadDep" class="form-control" name="UnidadDep" id="UnidadDep"
                    placeholder="Ingrese el nombre de la unidad">
            </div>
        </div>
    </div>
</x-small-modal>


<script>
    window.addEventListener('abrirModalEditar', event => {
        $('#modalUnidadEditar').modal('show');
    });

    function editarUnidad() {
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
                Livewire.dispatch('actualizarUnidad');
                $('#modalUnidadEditar').modal('hide');
            } else {
                Swal.fire({
                    title: "No se guardaron los cambios",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#modalUnidadEditar').modal('hide');
            }
        });

        function UnidadModificada() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro correctamente",
                icon: "success"

            });
        }

        function UnidadCancelada() {
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


        document.addEventListener('unidadActualizada', function() {
            UnidadModificada();
        });
        document.addEventListener('unidadNoActualizada', function() {
            UnidadCancelada();
        });
        document.addEventListener('datoIncompleto', function() {
            sinNombre();
        });
    }
</script>
