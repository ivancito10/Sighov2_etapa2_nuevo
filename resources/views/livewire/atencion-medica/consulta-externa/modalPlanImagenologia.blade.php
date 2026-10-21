<div>

    <x-modal-sin-footer id="modalImgList" labelledby="modalImgListLabel"
        title="LISTADO DE IMAGENOLOGÍA">
        
       @livewire('atencion-medica.consulta-externa.imagenologia.list-ima-persona')

    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modalImagenologiaListado', event => {
        $('#modalImgList').modal('show');
    });
</script>