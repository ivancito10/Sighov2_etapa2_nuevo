<x-modal-small-sin-footer id="reportesAgendasLiquidacion" labelledby="reportesAgendasLiquidacionLabel"
    title="GENERAR REPORTES">
    @livewire('liquidaciones.agendas.agendas-reportes')
</x-modal-small-sin-footer>

<script>
    window.addEventListener('modalLiquidacionAgendasReportes', event => {
        $('#reportesAgendasLiquidacion').modal('show');
    });

    window.addEventListener('cerrarmodalLiquidacionAgendasReportes', event => {
        $('#reportesAgendasLiquidacion').modal('hide');
    });
</script>
