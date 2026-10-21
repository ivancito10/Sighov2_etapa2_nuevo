<div>
    <x-modal-sin-footer id="modalAgendaAnticipada" labelledby="modalRBMedicaLabel" title="AGREGAR AGENDA ANTICIPADA">
        <div>
            @livewire('atencion-medica.consulta-externa.agenda.agenda-anticipada-main')
        </div>
    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('modalAgendaAnticipada', function() {
        $('#modalAgendaAnticipada').modal('show');
    });
</script>


