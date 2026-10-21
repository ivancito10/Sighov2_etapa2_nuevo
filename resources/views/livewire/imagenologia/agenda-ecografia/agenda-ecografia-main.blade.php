<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 style="color:black;">IMAGENOLOGÍA - <b> LISTA DE AGENDAS DE ECOGRAFÍA</b></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end w-100">


                @can('ver_agendas_anteriores.agenda_ecografia_etapa2')
                    <div class="ml-2">
                        <input type="date" wire:model="fechaFiltro" wire:keydown.enter="filtrarPorFecha"
                            class="form-control" style="border-radius:8px;" />
                    </div>
                    <div class="ml-2">
                        <button wire:click="filtrarPorFecha" class="btn btn-primary"
                            style="border-radius:8px;">Buscar</button>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div>

        @can('lista.agenda_ecografia_etapa2')
            <div class="card-body pt-2 pr-0 pl-0">
                @livewire('imagenologia.agenda-ecografia.agenda-ecografia-table')
            </div>
        @endcan
        @can('ver.agenda_ecografia_etapa2')
            @include('livewire.imagenologia.agenda-ecografia.modalVerAgendaEcografia')
        @endcan
    </div>

</div>
<script>
    document.addEventListener('fechaFiltradaEcografia', function() {
        Swal.fire({
            title: '¡Lista Actualizada!',
            icon: 'success',
            text: 'La lista de atención médica se actualizó correctamente.',
        });
    });
</script>
