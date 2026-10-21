<div>

    <x-modal-sin-footer id="modalRReferencia" labelledby="modalRReferenciaLabel"
        title="AGREGAR REFERENCIA">
        
        @livewire('atencion-medica.consulta-externa.referencia.registrar-referencia')

    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modal_referencia', event => {
        $('#modalRReferencia').modal('show');
    });
</script>