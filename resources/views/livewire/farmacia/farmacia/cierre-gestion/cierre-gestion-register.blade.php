<div>
    <x-agregar-button wire:click="lista_medicamento_farmacia">REGISTRAR</x-agregar-button>
    <x-ver-dos-modal id="modalListaMedicamento_farmacia" labelledby="modalListaMedicamento_farmaciaLabel"
        title="LISTA DE MEDICAMENTOS">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('Farmacia.Farmacia.Cierre-gestion.Medicamentos-table')
            </div>

        </div>
    </x-ver-dos-modal>
    <div>
        @include('livewire.farmacia.farmacia.cierre-gestion.modalRegistrarCierreGestion')
    </div>
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>

<script>
    document.addEventListener('listado_modal_farmacia', function() {
        //console.log('entra?');
        $('#modalListaMedicamento_farmacia').modal('show');
    });

    document.addEventListener('cerrar_listado_modal_farmacia', function() {
        // console.log('entra?');
        $('#modalListaMedicamento_farmacia').modal('hide');
    });
</script>
