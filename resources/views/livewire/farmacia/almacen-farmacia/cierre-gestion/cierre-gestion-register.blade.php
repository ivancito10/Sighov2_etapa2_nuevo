<div>
    <x-agregar-button wire:click="lista_medicamento_almacen">REGISTRAR</x-agregar-button>
    <x-ver-dos-modal id="modalListarMedicamento" labelledby="modalListarMedicamentolLabel" title="LISTA DE MEDICAMENTOS">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                @livewire('Farmacia.Almacen-Farmacia.Cierre-gestion.Medicamentos-table')
            </div>
        </div>
    </x-ver-dos-modal>
    <div>
        @include('livewire.farmacia.almacen-farmacia.cierre-gestion.modalRegistrarCierreGestion')
    </div>

    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
<script>
    document.addEventListener('listar_medicamento', function() {
        $('#modalListarMedicamento').modal('show');
    });

    document.addEventListener('cerrar_listar_medicamento', function() {
        $('#modalListarMedicamento').modal('hide');
    });

    //window.addEventListener('listar_medicamento', event => {
    //    $('#modalListarMedicamento').modal('show');
    //});
    //window.addEventListener('cerrar_listar_medicamento', event => {
    //    $('#modalListarMedicamento').modal('hide');
    //});
</script>
