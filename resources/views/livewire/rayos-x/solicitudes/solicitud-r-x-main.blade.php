<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">RAYOS X - <b> LISTA DE SOLICITUDES </b></h4>

    </div>
    <br>
    @can('registrar.resultados_imagenologia')
        @include('livewire.rayos-x.solicitudes.modalRegistrarPlacasRX')
    @endcan
    @can('ver.resultados_imagenologia')
        @include('livewire.rayos-x.solicitudes.modalVerSolicitudPlacasRX')
    @endcan
    @can('lista.resultados_imagenologia')
        <div class="card-body pt-2 pr-0 pl-0">
            <livewire:rayos-x.solicitudes.solicitudes-r-x-table />

        </div>
    @endcan
</div>
