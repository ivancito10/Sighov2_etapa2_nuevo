<x-modal-sin-footer id="registroPorveedorModal" labelledby="registroPorveedorModalLabel"
    title="REGISTRO DE NUEVO PROVEEDOR">
    @livewire('farmacia.almacen-farmacia.proveedores.proveedores-register')
</x-modal-sin-footer>

<script>
    window.addEventListener('modalRegistrarNuevoProveedor', event => {
        $('#registroPorveedorModal').modal('show');
    });

    window.addEventListener('cerrarModalRegistrarNuevoProveedor', event => {
        $('#registroPorveedorModal').modal('hide');
    });
</script>
