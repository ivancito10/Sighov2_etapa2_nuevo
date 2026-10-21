<div>
    <x-ver-modal id="medicamentosPorAgotarseModal" labelledby="medicamentosPorAgotarseModalLabel"
        title="MEDICAMENTOS POR AGOTARSE">
        @livewire('farmacia.AlmacenFarmacia.MedicamentosPorVencer.MedicamentoStockPorAgotarse')

    </x-ver-modal>
</div>
<script>
    window.addEventListener('modalVerMedicsAgotados', event => {
        $('#medicamentosPorAgotarseModal').modal('show');
    });
</script>
