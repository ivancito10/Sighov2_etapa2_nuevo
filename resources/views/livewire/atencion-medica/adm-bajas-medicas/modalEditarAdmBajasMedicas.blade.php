<div>
    <x-modal-sin-footer id="editarAdmBajaMedica" labelledby="editarAdmBajaMedicaLabel"
        title="MODIFICAR DATOS DE LA BAJA MEDICA" saveAction="funcionEditarProveedor()">
        @livewire('atencion-medica.adm-bajas-medicas.baja-medica-edit')

    </x-modal-sin-footer>
</div>


<script>
    document.addEventListener('editarBajaMedicaModal', function() {
        $('#editarAdmBajaMedica').modal('show');
    });

    document.addEventListener('cerrarModalEditarBajaMedica', function() {
        $('#editarAdmBajaMedica').modal('hide');
    });
</script>
