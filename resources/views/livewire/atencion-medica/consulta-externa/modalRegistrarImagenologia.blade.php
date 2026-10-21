<div>

    <x-modal-sin-footer id="modalRImagenologia" labelledby="modalRImagenologiaLabel"
        title="AGREGAR IMAGENOLOGÍA">
       
        @livewire('atencion-medica.consulta-externa.imagenologia.registrar-imagenologia')

    </x-modal-sin-footer> 
</div>
<script>
    window.addEventListener('abrirModalImagenologia', event => {
        $('#modalRImagenologia').modal('show');
    });
</script>