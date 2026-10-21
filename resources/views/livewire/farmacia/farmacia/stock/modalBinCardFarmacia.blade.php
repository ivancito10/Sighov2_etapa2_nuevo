<div>
    <x-modal-sin-footer id="binCArdModal" labelledby="binCArdModallLabel" title="BIN CARD DE MEDICAMENTOS - FARMACIA">

        @livewire('farmacia.farmacia.bincard.bincard-farmacia')


    </x-modal-sin-footer>
</div>

<script>
    document.addEventListener('abrirModalBinCard', function() {
        $('#binCArdModal').modal('show');
    });

    document.addEventListener('cerrarModalEditarProveedor', function() {
        $('#binCArdModal').modal('hide');
    });
</script>
