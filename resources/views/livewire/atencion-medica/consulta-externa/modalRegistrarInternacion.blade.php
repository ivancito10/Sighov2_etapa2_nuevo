<div>

    <x-modal-sin-footer id="modalRInternacion" labelledby="modalRInternacionLabel"
        title="AGREGAR INTERNACIÓN">
        
        @livewire('atencion-medica.consulta-externa.internacion.registrar-internacion')

    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modal_internacion', event => {
        $('#modalRInternacion').modal('show');
    });
</script>