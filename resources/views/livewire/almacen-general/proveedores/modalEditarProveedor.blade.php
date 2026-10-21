<div>
    <x-modal-sin-footer id="editarModalProveedorAlmacenGeneral" labelledby="editarModalProveedorAlmacenGeneralLabel"
        title="MODIFICAR DATOS DEL PROVEEDOR" saveAction="funcionEditarProveedor()">
        @livewire('almacen-general.proveedores.proveedores-edit')
    </x-modal-sin-footer>
</div>


<script>
    document.addEventListener('editarProveedorAlmacenGeneralModal', function() {
        $('#editarModalProveedorAlmacenGeneral').modal('show');
    });

    document.addEventListener('cerrarModalEditarAlmacenGeneralProveedor', function() {
        $('#editarModalProveedorAlmacenGeneral').modal('hide');
    });
</script>
