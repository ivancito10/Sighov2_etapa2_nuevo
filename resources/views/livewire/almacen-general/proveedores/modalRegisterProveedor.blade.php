<x-modal-sin-footer id="registroPorveedorAlmacenGeneralModal" labelledby="registroPorveedorAlmacenGeneralModalLabel"
    title="REGISTRO DE NUEVO PROVEEDOR">
    @livewire('almacen-general.proveedores.proveedores-register')
</x-modal-sin-footer>

<script>
    window.addEventListener('modalRegistrarNuevoProveedorAlmacenGeneral', event => {
        $('#registroPorveedorAlmacenGeneralModal').modal('show');
    });

    window.addEventListener('cerrarModalRegistrarNuevoProveedorAlmacenGeneral', event => {
        $('#registroPorveedorAlmacenGeneralModal').modal('hide');
    });
</script>
