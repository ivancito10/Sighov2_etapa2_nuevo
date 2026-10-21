<div>
    <x-ver-modal id="medicamentosPorVencerModal" labelledby="medicamentosPorVencerModalLabel"
        title="SEMAFORIZACIÓN DE MEDICAMENTOS">
        @livewire('farmacia.AlmacenFarmacia.MedicamentosPorVencer.MedicamentoStockPorVencer')
    </x-ver-modal>
</div>
<script>
    window.addEventListener('modalVerMedicamentoVenciendo', event => {
        $('#medicamentosPorVencerModal').modal('show');
    });
</script>
