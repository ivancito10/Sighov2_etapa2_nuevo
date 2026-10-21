<div>
    <x-modal-sin-footer id="registroMaterialModal" labelledby="registroMaterialModalLabel"
        title="REGISTRO DE MATERIAL O SUMINISTRO">
        @livewire('almacen-general.materiales.materiales-register')
    </x-modal-sin-footer>
</div>

<script>
    window.addEventListener('modalRegistrarNuevoMaterial', event => {
        $('#registroMaterialModal').modal('show');
    });

    window.addEventListener('cerrarModalRegistrarNuevoMaterial', event => {
        $('#registroMaterialModal').modal('hide');
    });
</script>
