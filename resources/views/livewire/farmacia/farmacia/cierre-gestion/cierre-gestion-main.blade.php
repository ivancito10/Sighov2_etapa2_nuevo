<div>

    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4> FARMACIA - <b> LISTA DE CIERRE DE GESTIÓN</b></h4>
        <div class="d-flex">
            <div class="mr-2">
                <x-pdf2-button wire:click="reporteCierreFarmacia" style="padding:6px">
                    <b>REPORTES</b>
                </x-pdf2-button>
            </div>
            <div class="mr-2">
                <x-ver-excel-button wire:click="reporteExcelCierreFarmacia" style="padding:6px">
                    <b>REPORTES</b>
                </x-ver-excel-button>
            </div>
            @livewire('Farmacia.Farmacia.Cierre-gestion.Cierre-gestion-register')
        </div>
    </div>


    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @livewire('farmacia.farmacia.cierre-gestion.cierre-gestion-farmacia-table')
        </div>
    </div>
    @include('livewire.farmacia.farmacia.cierre-gestion.modalVerCierreFarmacia')
    @include('livewire.farmacia.farmacia.cierre-gestion.modalEditarCierreFarmacia')
    @include('livewire.farmacia.farmacia.cierre-gestion.modalAgregarPrecioFarmacia')

    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
