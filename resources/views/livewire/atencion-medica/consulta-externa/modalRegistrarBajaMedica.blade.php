<div>

    <x-modal-sin-footer id="modalRBMedica" labelledby="modalRBMedicaLabel"
        title="AGREGAR BAJA MÉDICA">
        
        @livewire('atencion-medica.consulta-externa.baja-medica.registrar-baja-medica')

    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modal_baja_medica', event => {
        $('#modalRBMedica').modal('show');
    });
</script>