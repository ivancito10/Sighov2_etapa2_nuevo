<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> ALMACEN DE MEDICAMENTOS - <b> LISTADO DE REINGRESOS DE MEDICAMENTOS</b></h5>
        </div>

        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 text-end">
            <x-agregar-button wire:click="registrar_reingreso">REGISTRAR</x-agregar-button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @livewire('farmacia.almacen-farmacia.reingreso.reingreso-table')
        </div>
    </div>
    <!-- Modal para Ver datos de Reingreso de medicamentos -->
    @include('livewire.farmacia.almacen-farmacia.reingreso.modalVerReingreso')
</div>
