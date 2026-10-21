<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> ALMACEN DE MEDICAMENTOS - <b> LISTADO DE INGRESO DE MEDICAMENTOS</b></h5>
        </div>
        @can('registrar.ingresos')
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">
                <x-agregar-button wire:click="registrar_ingreso">REGISTRAR</x-agregar-button>
            </div>
        @endcan
    </div>
    <br>
    <div class="row">
        @can('lista.ingresos')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('farmacia.almacen-farmacia.ingreso-medicamentos.ingreso-medicamentos-table')
            </div>
        @endcan
    </div>
    <!-- Modal para Ver datos de ingreso de medicamentos -->
    @can('ver.ingresos')
        @include('livewire.farmacia.almacen-farmacia.ingreso-medicamentos.modalVerIngresoMedicamentos')
        @include('livewire.farmacia.almacen-farmacia.ingreso-medicamentos.modalEditarFechaVencimiento')
    @endcan
    @can('registrar.ingresos')
        @include('livewire.farmacia.almacen-farmacia.ingreso-medicamentos.modalAgregarFacturas')
    @endcan
    <div id="cargando-validar-ingreso" style="display: none;">
        <x-cargando-dos />
    </div>

</div>
<script>
    document.addEventListener('validarIngresoAlmacen', function() {
        Swal.fire({
            title: '<b>¿Validar ingreso?</b>',
            text: "¿Está seguro de validar el ingreso? Se validarán todos los datos registrados",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, validar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registrarIngresoMedicamentos');
                document.getElementById('cargando-validar-ingreso').style.display = 'block';
            }
        });
    });

    document.addEventListener('noExisteIngresoError', function() {
        Swal.fire({
            title: '<b>Ingreso no encontrado</b>',
            text: 'Verifique e intente nuevamente',
            icon: 'error',
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('ingresoValidadoCorrectamente', function() {
        Swal.fire({
            title: '<b>Ingreso validado</b>',
            text: 'El ingreso se validó correctamente',
            icon: 'success',
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });
</script>
