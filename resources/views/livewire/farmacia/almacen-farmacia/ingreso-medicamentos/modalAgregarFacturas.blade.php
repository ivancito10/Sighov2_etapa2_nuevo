<div>
    <x-modal-sin-footer id="agregarFacturas" labelledby="agregarFacturasLabel" title="REGISTRO DE FACTURAS">

        @livewire('farmacia.almacen-farmacia.ingreso-medicamentos.agregar-facturas')

    </x-modal-sin-footer>
</div>

<script>
    window.addEventListener('modalAgregarFacturas', event => {
        $('#agregarFacturas').modal('show');
    });

    
</script>
