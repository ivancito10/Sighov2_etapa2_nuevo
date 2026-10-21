<div>
    <x-modal-sin-footer id="registroMedicamentoModal" labelledby="registroMedicamentoModalLabel"
        title="REGISTRO DE MEDICAMENTO">
        @livewire('farmacia.almacen-farmacia.medicamentos.medicamentos-register')
    </x-modal-sin-footer>
</div>

<script>
    window.addEventListener('modalRegistrarNuevoMedicamento', event => {
        $('#registroMedicamentoModal').modal('show');
    });

    window.addEventListener('cerrarModalRegistrarNuevoMedicamento', event => {
        $('#registroMedicamentoModal').modal('hide');
    });
</script>
