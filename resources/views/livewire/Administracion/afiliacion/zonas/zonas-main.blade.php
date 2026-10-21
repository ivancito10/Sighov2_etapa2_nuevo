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
            <h6 style="font-family: Verdana, sans-serif ; text-align: left; color:white">LISTADO DE ZONAS</h6>
        </div>

        <div class="card-body">
            @livewire('Administracion.afiliacion.zonas.zonas-table')
        </div>
    </div>



    <!-- Inclusion del modal para editar zonas -->
    @include('livewire.Administracion.afiliacion.zonas.modalEditarZonas')

    <script>
        document.addEventListener('ZonaNoActualizada', function() {
            Swal.fire({
                title: "No se realizo ninguna accion",
                text: "Se cancelo el registro",
                icon: "error"

            });
        });

        document.addEventListener('ZonaDeshabilitada', function() {
            Swal.fire({
                title: 'Ahora esta ACTIVO!',
                text: 'Cambio de estado exitoso.',
                icon: 'success'
            });
        });
        document.addEventListener('ZonaHabilitada', function() {
            Swal.fire({
                title: 'Ahora esta INACTIVO!',
                text: 'Cambio de estado exitoso.',
                icon: 'error'
            });
        });
    </script>

</div>
