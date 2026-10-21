<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> FARMACIA - <b> STOCK DE MEDICAMENTOS </b></h5>
        </div>
        <!--
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 text-end">
            <x-agregar-button wire:click="redirigirRegistrarSolicitudAlmancen">REGISTRAR</x-agregar-button>
        </div>
        -->
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @livewire('farmacia.farmacia.stock.stock-farmacia-table')
            
        </div>
    </div>
    @include('livewire.farmacia.farmacia.stock.modalVerStockMedFarmacia')
    @include('livewire.farmacia.farmacia.stock.modalBinCardFarmacia')
</div>
