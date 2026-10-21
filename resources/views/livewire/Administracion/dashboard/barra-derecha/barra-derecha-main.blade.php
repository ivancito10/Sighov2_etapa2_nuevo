<div>
    <!--     @can('super-admin-only')
    <div class="card card-outline card-primary bg-transparent">
                                            <div class="card-body bg-transparent">
                                                <x-subir-archivo-button wire:click="actualizarCache" title="REGISTRAR INSTITUCION"
                                                    class="btn btn-primary btn-block">
                                                    RECARGAR CACHE
                                                </x-subir-archivo-button>
                                            </div>
                                        </div>
@endcan -->

    {{-- Drive Rayos X --}}
    <div class="border rounded shadow-sm p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0 text-white">Drive Rayos X</h5>
        </div>
        <button class="btn btn-outline-light w-100"
            onclick="window.open('https://drive.google.com/drive/u/0/folders/1daoMexYsHt_gwJkfHCbTIQxra9uyd41Y', '_blank', 'width=800,height=600')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-folder-symlink me-2"
                fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M11.798 9.53a.5.5 0 0 1 0 .94l-2.5 1.25a.5.5 0 0 1-.447-.894L10.118 10H6a.5.5 0 0 1 0-1h4.118l-1.267-.826a.5.5 0 1 1 .557-.83l2.39 1.188z" />
                <path
                    d="M9.828 4a.5.5 0 0 0-.354.146L8 5.621 6.525 4.146A.5.5 0 0 0 6.171 4H2.5a.5.5 0 0 0-.5.5V13a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V5a1 1 0 0 0-1-1H9.828zM2 5.5a.5.5 0 0 1 .5-.5H6l1.5 1.5L9 5h2a.5.5 0 0 1 .5.5v7.5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5.5z" />
            </svg>
            Ver en Drive
        </button>
    </div>

    <!--     @can('personalssu.normativas_asuss')
    -->
        <div class="border rounded shadow-sm p-3 bg-transparent">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0 text-white">Documentos técnicos normativos</h5>
            </div>
            <button class="btn btn-outline-light w-100"
                onclick="window.open('https://esalud.asuss.gob.bo/', '_blank', 'width=800,height=600')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-box-arrow-up-right me-2"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M6.364 13.364a.5.5 0 0 1-.708 0l-4.95-4.95a.5.5 0 0 1 0-.708l4.95-4.95a.5.5 0 0 1 .708.708L2.707 7.5H13.5a.5.5 0 0 1 0 1H2.707l3.657 3.657a.5.5 0 0 1 0 .707z" />
                </svg>
                Ir al sitio
            </button>
        </div>
        <!--
@endcan -->


    <!--  Botones para farmacia  -->
    @can('menu.almacen_farmacia_stock')
        <div class="p-3">
            @livewire('Farmacia.AlmacenFarmacia.MedicamentosPorVencer.MedicamentosPorVencerMain')
        </div>
    @endcan

    @can('menu.almacen_farmacia_stock')
        <div class="p-3">
            @livewire('Farmacia.AlmacenFarmacia.MedicamentosPorVencer.MedicamentosPorAgotarseMain')
        </div>
    @endcan



    <!--     <script>
        document.addEventListener('mostrarSalida', function(data) {
            Swal.fire({
                title: 'El cache fue actualizado',
                text: data.detail.message,
                icon: 'success',
            });
        });
    </script>
 -->
