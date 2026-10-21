<div>

    <x-modal-sin-footer-grande id="modalRLabotarorio" labelledby="modalRLabotarorioLabel"
        title="AGREGAR LABORATORIO" >
    
        @livewire('atencion-medica.consulta-externa.laboratorio.registrar-laboratorio')
        
    </x-modal-sin-footer-grande>
</div>
<script>
    window.addEventListener('abrirModalLaboratorio', event => {
        $('#modalRLabotarorio').modal('show');
    });
</script>