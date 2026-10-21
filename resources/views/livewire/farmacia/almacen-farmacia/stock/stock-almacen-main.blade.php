<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> ALMACEN DE MEDICAMENTOS - <b> STOCK DE MEDICAMENTOS </b></h5>
        </div>
        <!--
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 text-end">
            <x-agregar-button wire:click="redirigirRegistrarSolicitudAlmancen">REGISTRAR</x-agregar-button>
        </div>
        -->
    </div>
    <br>
    <div class="row">
        @can('menu.almacen_farmacia_bind_card')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('farmacia.almacen-farmacia.stock.stock-almacen-table')
            </div>
        @endcan

        <div>
            @can('menu.almacen_farmacia_bind_card')
                @include('livewire.farmacia.almacen-farmacia.stock.modalVerStockMedAlmacen')
            @endcan
            @can('menu.almacen_farmacia_bind_card')
                @include('livewire.farmacia.almacen-farmacia.stock.modalBinCArdAlmacen')
            @endcan
        </div>
    </div>

</div>
