<div>
    <x-modal-sin-footer id="modalEditarMaterialModal" labelledby="modalEditarMaterialModalLabel"
        title="MODIFICAR MATERIAL">
        @livewire('almacen-general.materiales.materiales-edit')
    </x-modal-sin-footer>
</div>

<script>
    document.addEventListener('editarMaterialModal', function() {
        $('#modalEditarMaterialModal').modal('show');
    });

    document.addEventListener('cerrarModalRegistrarNuevoMaterial', function() {
        $('#modalEditarMaterialModal').modal('hide');
    });
</script>
