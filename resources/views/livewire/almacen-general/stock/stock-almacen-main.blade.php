<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> ALMACEN GENERAL - <b> STOCK DE MATERIALES </b></h5>
        </div>
        {{--
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 text-end">
            <x-agregar-button wire:click="redirigirRegistrarSolicitudAlmancen">REGISTRAR</x-agregar-button>
        </div>
        --}}
    </div>
    <br>
    <div class="row">
        @can('lista.stock.almacen_general')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('almacen-general.stock.stock-almacen-table')
            </div>
        @endcan

        <div>
            @can('ver.stock_almacen_general')
                @include('livewire.almacen-general.stock.modalVerStockMatAlmacen')
            @endcan
            @can('bindcard.stock_almacen_general')
                @include('livewire.almacen-general.stock.modalBinCardAlmacen')
            @endcan
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>
</div>
