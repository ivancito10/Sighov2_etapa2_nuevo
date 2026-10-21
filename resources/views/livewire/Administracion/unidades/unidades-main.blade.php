<div>
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4">
                        <x-agregar-button wire:click="redirectToForm">AGREGAR</x-agregar-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="background-color:#154360">
            <h6 style="font-family: Verdana, sans-serif ; text-align: left; color:white">UNIDADES</h6>
        </div>
        <div class="card-body">
            @livewire('Administracion.Unidades.unidades-table')
        </div>
    </div>
    <!-- Inclusion del modal para editar zonas -->
    @include('livewire.Administracion.unidades.modalEditarUnidades')
</div>
<script>
        document.addEventListener('unidadEliminada', function() {
            Swal.fire({
                title: 'Unidad eliminada!',
                text: 'La unidad se a eliminado con exito!',
                icon: 'success'
            });
        });
        document.addEventListener('confirmarEliminarDato', function() {
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
                Livewire.dispatch('eliminarUnidad');
                }
            });
        });
    </script>