<div>
    <x-modal-sin-footer id="modalEditarCierreFarmacia" labelledby="modalEditarCierreAlmacenFarmaciaLabel"
        title="MODIFICAR CIERRE DE GESTIÓN - FARMACIA">
        @livewire('farmacia.farmacia.cierre-gestion.cierre-gestion-edit')
    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('editarcierreFarmacai', function() {
        $('#modalEditarCierreFarmacia').modal('show');
    });
</script>
