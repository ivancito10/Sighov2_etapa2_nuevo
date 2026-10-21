<div>
    <x-modal-sin-footer id="binCardAlmacen" labelledby="binCardAlmacenlLabel"
        title="BIN CARD DE MEDICAMENTOS - ALMACEN DE MEDICAMENTOS">

        @livewire('farmacia.almacen-farmacia.bind-card.bind-card-almacen')


    </x-modal-sin-footer>
</div>

<script>
    document.addEventListener('abrirBinCardAlmacen', function() {
        $('#binCardAlmacen').modal('show');
    });

    document.addEventListener('cerrarModalEditarProveedor', function() {
        $('#binCardAlmacen').modal('hide');
    });
</script>
