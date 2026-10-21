<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> ALMACEN GENERAL - <b> LISTADO DE REINGRESOS DE MATERIALES Y SUMINISTROS</b></h5>
        </div>

        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 text-end">
            @can('registrar.reingresos_almacen_general')
                <x-agregar-button wire:click="registrar_reingreso">REGISTRAR</x-agregar-button>
            @endcan
        </div>
    </div>
    <br>
    <div class="row">
        @can('lista.reingresos_almacen_general')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('almacen-general.reingreso.reingreso-table')
            </div>
        @endcan
    </div>
    <!-- Modal para Ver datos de Reingreso de medicamentos -->
    @can('ver.reingresos_almacen_general')
        @include('livewire.almacen-general.reingreso.modalVerReingreso')
    @endcan
</div>
