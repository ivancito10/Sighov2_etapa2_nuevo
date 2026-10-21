<div>
    <x-agregar-button wire:click="lista_material_almacen_general">REGISTRAR</x-agregar-button>
    <x-ver-dos-modal id="modalListarMaterial" labelledby="modalListarMateriallLabel"
        title="LISTA DE MATERIALES O SUMINISTROS">
        <div class="row">
            @can('lista_materiales.cierre_gestion_almacen_general')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @livewire('almacen-general.cierre-gestion.materiales-table')
                </div>
            @endcan
        </div>
    </x-ver-dos-modal>
    <div>
        @include('livewire.almacen-general.cierre-gestion.modalRegistrarCierreGestion')
    </div>

    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
<script>
    document.addEventListener('listar_material', function() {
        $('#modalListarMaterial').modal('show');
    });

    document.addEventListener('cerrar_listar_material', function() {
        $('#modalListarMaterial').modal('hide');
    });

    //window.addEventListener('listar_medicamento', event => {
    //    $('#modalListarMaterial').modal('show');
    //});
    //window.addEventListener('cerrar_listar_medicamento', event => {
    //    $('#modalListarMaterial').modal('hide');
    //});
</script>
