<div>
    <x-modal-sin-footer id="modalLabList" labelledby="modalLabListLabel" title="LISTADO DE LABORATORIOS REGISTRADOS">
        @if ($id_persona)
            @if ($lab == 1)
                @livewire('atencion-medica.consulta-externa.laboratorio.laboratorio-resultados-table', ['id_persona' => $id_persona])
            @elseif($lab == 2)
                @livewire('atencion-medica.consulta-externa.laboratorio.laboratorio-resultados-main-table', ['id_persona' => $id_persona])
            @elseif($lab == 3)
                @livewire('atencion-medica.otras-consultas.laboratorio-main-table', ['id_persona' => $id_persona])
            @elseif($lab == 4)
                @livewire('atencion-medica.otras-consultas.laboratorio-table', ['id_persona' => $id_persona])
            @endif
        @endif

    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modalLaboratorioListado', event => {
        $('#modalLabList').modal('show');
    });

    window.addEventListener('cerrarModalPlanLaboratorio', event => {
        $('#modalLabList').modal('hide');
    });
</script>
