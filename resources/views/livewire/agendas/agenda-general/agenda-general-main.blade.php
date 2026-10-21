<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">AGENDAS - <b> AGENDA GENERAL</b></h4>
        <div class="d-flex">
            <input type="date" wire:model="fecha_busqueda" class="form-control"
                style="margin-right: 10px;border-radius:10px;" />
            <x-buscar-button style="padding:6px;" wire:click="busquedaFechaAgendado"></x-buscar-button>
        </div>
    </div>
    <br>
    <div class="card-body pt-2 pr-0 pl-0">
        @livewire('agendas.agenda-general.agenda-general-table')
        @include('livewire.agendas.agenda-general.modalVerAgendaGeneral')
    </div>
</div>
<script>
    document.addEventListener('agendasEncontradas', function() {
        Swal.fire({
            title: "Agendas encontradas",
            text: "Se han recuperado correctamente las agendas.",
            icon: "success"
        });
    });

    document.addEventListener('agendasNoEncontradas', function() {
        Swal.fire({
            title: "Sin resultados",
            text: "No se encontraron agendas para la fecha seleccionada.",
            icon: "error"
        });
    });
</script>
