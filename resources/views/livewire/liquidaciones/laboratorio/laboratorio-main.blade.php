<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4> LABORATORIO - <b> LISTA DE SOLICITUDES </b></h4>
    </div>
    <br>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('listado.laboratorio-liquidacion')
            @livewire('liquidaciones.laboratorio.laboratorioLiqTable')
        @endcan

    </div>
    @can('ver.laboratorio-liquidacion')
        @include('livewire.liquidaciones.laboratorio.modalVerLaboratorioLiquidacion')
    @endcan
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
<script>
    document.addEventListener('noHayResultado', function() {
        Swal.fire({
            title: "Sin resultados disponibles",
            text: "El laboratorio aún no registró resultados para esta solicitud. Por favor, vuelva a intentarlo más tarde.",
            icon: "warning",
            confirmButtonColor: '#008f39',
        });
    });
</script>
