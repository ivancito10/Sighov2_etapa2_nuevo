<div>
    <div class="row d-flex justify-content-between align-items-center pr-4">
        <h4> ALMACEN GENERAL - <b> LISTA DE CIERRE DE GESTIÓN</b></h5>
            <div class="d-flex">
                @can('reporte.cierre_gestion_almacen_general')
                    <div class="mr-2">
                        <x-pdf2-button wire:click="reporteCierreAlmacenGeneral" style="padding:6px">
                            <b>REPORTES</b>
                        </x-pdf2-button>
                    </div>
                @endcan

                @can('excel.cierre_gestion_almacen_general')
                    <div class="mr-2">
                        <x-ver-excel-button wire:click="reporteExcelCierreAlmacenGeneral" style="padding:6px">
                            <b>REPORTES</b>
                        </x-ver-excel-button>
                    </div>
                @endcan

                @can('registrar.cierre_gestion_almacen_general')
                    @livewire('almacen-general.cierre-gestion.cierre-gestion-register')
                @endcan
            </div>
    </div>
    <br>
    <div class="row">
        @can('lista.cierre_gestion_almacen_general')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('almacen-general.cierre-gestion.cierre-gestion-almacen-general-table')
            </div>
        @endcan
    </div>
    @can('ver.cierre_gestion_almacen_general')
        @include('livewire.almacen-general.cierre-gestion.modalVerCierre')
    @endcan
    @can('editar.cierre_gestion_almacen_general')
        @include('livewire.almacen-general.cierre-gestion.modalEditarCierreAlmacenGeneral')
    @endcan
    @can('añadir_precio.cierre_gestion_almacen_general')
        @include('livewire.almacen-general.cierre-gestion.modalAgregarPrecioAlmGeneral')
    @endcan
    <div wire:loading>
        <x-cargando-dos />
    </div>

</div>

<script>
    document.addEventListener('verCierreAlmacenGeneral', function() {
        $('#modalVerCierreAlmacenGeneral').modal('show');
    });
    document.addEventListener('editarCierreAlmacenGeneral', function() {
        $('#modalEditarCierreAlmacenGeneral').modal('show');
    });
</script>
