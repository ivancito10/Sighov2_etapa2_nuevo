    <x-modal-sin-footer id="modalRegistrarPlacasRX" labelledby="modalRegistrarPlacasRXLabel"
        title=" REGISTRO DE PLACAS RAYOS X ">

        @livewire('rayos-x.solicitudes.solicitudes-register')
    </x-modal-sin-footer>

    <script>
        window.addEventListener('abrirModalCrearSolicitudRX', event => {
            $('#modalRegistrarPlacasRX').modal('show');
        });
    </script>
