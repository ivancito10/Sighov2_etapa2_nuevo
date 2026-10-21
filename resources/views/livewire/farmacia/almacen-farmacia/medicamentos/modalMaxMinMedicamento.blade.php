<div>
    <x-modal-sin-footer id="modalMaxMinMedicamentoModal" labelledby="modalMaxMinMedicamentoModalLabel"
        title="CANTIDAD DE MEDICAMENTO POR SERVICIO">
        @livewire('farmacia.almacen-farmacia.medicamentos.medicamentos-max-min')
    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('maxMinServicioMedicamentoModal', function() {
        $('#modalMaxMinMedicamentoModal').modal('show');
    });

    document.addEventListener('cerrarModalRegistrarNuevoMedicamento', function() {
        $('#modalEditarMedicamentoModal').modal('hide');
    });
</script>
