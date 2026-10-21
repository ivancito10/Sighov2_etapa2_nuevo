<div>
    <x-modal-sin-footer id="registrarModal" labelledby="registrarModalLabel" title="AGREGAR NUEVA IMAGEN">

    @livewire('administracion.imagenes-file.imagenes-register')
    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modalRegistrarImagen', event => {
        $('#registrarModal').modal('show');
    });
</script>