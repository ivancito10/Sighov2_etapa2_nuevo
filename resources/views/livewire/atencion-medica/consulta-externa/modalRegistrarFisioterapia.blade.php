<div>

    <x-modal-sin-footer id="modalRFisioterapia" labelledby="modalRFisioterapiaLabel"
        title="AGREGAR FISIOTERAPIA">
        
        @livewire('atencion-medica.consulta-externa.fisioterapia.registrar-fisioterapia')

    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modal_fisioterapia', event => {
        $('#modalRFisioterapia').modal('show');
    });
</script>