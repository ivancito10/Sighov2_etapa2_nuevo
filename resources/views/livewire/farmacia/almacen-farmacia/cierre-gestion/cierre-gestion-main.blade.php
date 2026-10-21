<div>
    <div class="row d-flex justify-content-between align-items-center pr-4">
        <h4> ALMACEN DE MEDICAMENTOS - <b> LISTA DE CIERRE DE GESTIÓN</b></h5>
            <div class="d-flex">
                @can('registrar.almacen_farmacia_cierre_gestion')
                    <div class="mr-2">
                        <x-pdf2-button wire:click="reporteCierreAlmacenFarmacia" style="padding:6px">
                            <b>REPORTES</b>
                        </x-pdf2-button>
                    </div>
                    <div class="mr-2">
                        <x-ver-excel-button wire:click="reporteExcelCierreAlmacen" style="padding:6px">
                            <b>REPORTES</b>
                        </x-ver-excel-button>
                    </div>
                    @livewire('farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-register')
                @endcan
            </div>
    </div>
    <br>
    <div class="row">
        @can('lista.almacen_farmacia_cierre_gestion')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-almacen-farmacia-table')
            </div>
        @endcan
    </div>
    @can('ver.almacen_farmacia_cierre_gestion')
        @include('livewire.farmacia.almacen-farmacia.cierre-gestion.modalVerCierre')
        @include('livewire.farmacia.almacen-farmacia.cierre-gestion.modalEditarCierreAlmacenFarmacia')
    @endcan
    @can('añadir_precio.almacen_farmacia_cierre_gestion')
        @include('livewire.farmacia.almacen-farmacia.cierre-gestion.modalAgregarPrecioAlmFarmacia')
    @endcan

    <div wire:loading>
        <x-cargando-dos />
    </div>

</div>

<script>
    document.addEventListener('verCierreAlmacenFarmacia', function() {
        $('#modalVerCierreAlmacenFarmacia').modal('show');
    });
    document.addEventListener('editarCierreAlmacenFarmacia', function() {
        $('#modalEditarCierreAlmacenFarmacia').modal('show');
    });
</script>
