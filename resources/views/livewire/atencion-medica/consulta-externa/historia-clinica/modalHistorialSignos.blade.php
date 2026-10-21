<x-ver-modal id="modalFichaExtraRegister" labelledby="modalFichaExtraRegister" title="DATOS POBLACION ASEGURADA" saveAction="">
    <div class="content">
        @livewire('atencion-medica.consulta-externa.agenda.agenda-extra-select')
    </div>
</x-ver-modal>

<script>
    window.addEventListener('modalFichaExtraRegister', event => {
            $('#modalFichaExtraRegister').modal('show');
        });
</script>