<div>

    <x-modal-sin-footer id="modalRInterconsulta" labelledby="modalRInterconsultaLabel"
        title="AGREGAR INTERCONSULTA">
        
        @livewire('atencion-medica.consulta-externa.interconsulta.registrar-interconsulta')

    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modal_interconsulta', event => {
        $('#modalRInterconsulta').modal('show');
    });
</script>