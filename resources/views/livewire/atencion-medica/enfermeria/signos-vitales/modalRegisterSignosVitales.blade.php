<div>
    <x-modal-sin-footer id="registroSignosVitalesModal" labelledby="registroSignosVitalesModalLabel"
        title="REGISTRO DE SIGNOS VITALES">
        @livewire('atencion-medica.enfermeria.signos-vitales.signos-vitales-register')
    </x-modal-sin-footer>
</div>


<script>
    window.addEventListener('modalRegistrarSignosVitales', event => {
        $('#registroSignosVitalesModal').modal('show');
    });

    window.addEventListener('cerrarModalRegistrarNuevoMedicamento', event => {
        $('#registroMedicamentoModal').modal('hide');
    });
</script>
