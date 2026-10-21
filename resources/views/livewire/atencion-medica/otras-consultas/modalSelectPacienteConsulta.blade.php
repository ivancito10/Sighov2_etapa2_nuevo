<x-ver-modal id="modalSelectPacienteConsulta" labelledby="modalSelectPacienteConsulta" 
title="LISTA DE POBLACIÓN  ASEGURADA" saveAction="">
    <div class="content">
        @livewire('atencion-medica.otras-consultas.select-paciente-table')
    </div>
</x-ver-modal>

<script>
    window.addEventListener('modalSelectPacienteConsulta', event => {
            $('#modalSelectPacienteConsulta').modal('show');
        });
</script>