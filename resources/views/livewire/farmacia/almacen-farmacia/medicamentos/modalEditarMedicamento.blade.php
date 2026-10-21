<div>
    <x-modal-sin-footer id="modalEditarMedicamentoModal" labelledby="modalEditarMedicamentoModalLabel"
        title="MODIFICAR MEDICAMENTO">
        @livewire('farmacia.almacen-farmacia.medicamentos.medicamentos-edit')
    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('editarMedicamentoModal', function() {
        $('#modalEditarMedicamentoModal').modal('show');
    });

    document.addEventListener('cerrarModalRegistrarNuevoMedicamento', function() {
        $('#modalEditarMedicamentoModal').modal('hide');
    });
</script>
