<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4> AGENDAS - <b> LISTA DE AGENDAS </b></h4>
        <div class="d-flex">
            <div class="mr-2">
                <x-pdf2-button wire:click="abrirModalReportesAgendas">REPORTES</x-pdf2-button>
                @include('livewire.liquidaciones.agendas.modalAbrirReportes')
            </div>
        </div>
    </div>
    <br>
    <div class="card-body pt-2 pr-0 pl-0">

        @can('listado.agendas-liquidacion')
            @livewire('liquidaciones.agendas.DetalleAgendasLiqTable')
        @endcan

    </div>
    @can('ver.agendas-liquidacion')
        @include('livewire.liquidaciones.agendas.modalVerAgendasLiquidacion')
    @endcan
    <div wire:loading>
        <x-cargando-dos />
    </div>

</div>
