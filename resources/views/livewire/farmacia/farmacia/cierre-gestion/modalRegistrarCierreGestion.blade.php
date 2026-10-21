<div>
    <x-modal-sin-footer id="modalRegistrarCierreFarmacia" labelledby="modalRegistrarCierreFarmaciaLabel"
        title="CIERRE DE GESTIÓN">
        <div>
            @livewire('farmacia.farmacia.cierre-gestion.cierre-gestion-nuevo')
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>

    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('registrar_cierre_farmacia', function() {
        // console.log('entra asbgs?');
        $('#modalRegistrarCierreFarmacia').modal('show');
    });

    document.addEventListener('ocultar_registrar_cierre_farmacia', function() {
        $('#modalRegistrarCierreFarmacia').modal('hide');
    });
</script>
