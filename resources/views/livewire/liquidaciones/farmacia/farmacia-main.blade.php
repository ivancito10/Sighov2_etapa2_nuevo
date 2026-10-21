<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4> FARMACIA - <b> LISTA DE RECETAS MÉDICAS</b></h4>

    </div>

    <br>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('listado.recetas-liquidacion')
            @livewire('liquidaciones.farmacia.recetas-liq-table')
        @endcan
    </div>
    @can('ver.recetas-liquidacion')
        @include('livewire.liquidaciones.farmacia.modalVerRecetaLiquidacion')
    @endcan
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
