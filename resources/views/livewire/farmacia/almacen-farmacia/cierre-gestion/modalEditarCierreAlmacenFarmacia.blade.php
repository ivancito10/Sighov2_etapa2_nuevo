<div>
    <x-modal-sin-footer id="modalEditarCierreAlmacenFarmacia" labelledby="modalEditarCierreAlmacenFarmaciaLabel"
        title="MODIFICAR CIERRE DE GESTIÓN - ALMACEN DE MEDICAMENTOS">
        @livewire('farmacia.almacen-farmacia.cierre-gestion.cierre-gestion-edit')
    </x-modal-sin-footer>
</div>

<script>
    document.addEventListener('editarCierreAlmacenFarmacia', function() {
        $('#modalEditarCierreAlmacenFarmacia').modal('show');
    });

    //   document.addEventListener('cerrarModalEditarProveedor', function() {
    //       $('#editarModalProveedor').modal('hide');
    //   });
</script>
