<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4> RAYOS X - <b> LISTA DE SOLICITUDES </b></h4>
    </div>
    <br>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('listado.imagenologia-liquidacion')
            @livewire('liquidaciones.rayos-x.ImagenologiaLiqTable')
        @endcan
    </div>
    @can('ver.rayosx-liquidacion')
        @include('livewire.liquidaciones.rayos-x.modalVerRayosXLiquidacion')
    @endcan

    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
