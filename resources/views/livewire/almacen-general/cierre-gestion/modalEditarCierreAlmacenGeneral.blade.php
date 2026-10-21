<div>
    <x-modal-sin-footer id="modalEditarCierreAlmacenGeneral" labelledby="modalEditarCierreAlmacenGeneralLabel"
        title="MODIFICAR CIERRE DE GESTIÓN - ALMACEN GENERAL">
        @livewire('almacen-general.cierre-gestion.cierre-gestion-edit')
    </x-modal-sin-footer>
</div>

<script>
    document.addEventListener('editarCierreAlmacenGeneralModalcito', function() {
        $('#modalEditarCierreAlmacenGeneral').modal('show');
    });

    //   document.addEventListener('cerrarModalEditarProveedor', function() {
    //       $('#editarModalProveedor').modal('hide');
    //   });
</script>
