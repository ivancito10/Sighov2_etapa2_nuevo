<div>
    <x-modal-sin-footer id="editarModalProveedor" labelledby="editarModalProveedorLabel"
        title="MODIFICAR DATOS DEL PROVEEDOR" saveAction="funcionEditarProveedor()">
        @livewire('farmacia.almacen-farmacia.proveedores.proveedores-edit')
    </x-modal-sin-footer>
</div>


<script>
    document.addEventListener('editarProveedorModal', function() {
        $('#editarModalProveedor').modal('show');
    });

    document.addEventListener('cerrarModalEditarProveedor', function() {
        $('#editarModalProveedor').modal('hide');
    });
</script>
