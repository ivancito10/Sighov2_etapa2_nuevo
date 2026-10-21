<div>

    <x-modal-sin-footer-grande id="modalListaBajaMedica" labelledby="modalListaBajaMedicaLabel" title="LISTADO DE BAJAS MÉDICAS">

        @if ($id_persona)
            @livewire('atencion-medica.consulta-externa.baja-medica.baja-medica-table', ['id_persona' => $id_persona])
        @endif



    </x-modal-sin-footer-grande>
</div>

<script>
    window.addEventListener('abrirModalBajaMedica', event => {
        $('#modalListaBajaMedica').modal('show');
    });

    window.addEventListener('cerrarModalPlanLaboratorio', event => {
        $('#modalLabList').modal('hide');
    });
</script>
