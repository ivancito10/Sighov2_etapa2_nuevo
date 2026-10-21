<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">ADMINISTRACIÓN - <b> VIDEOS</b></h4>
        <div class="d-flex">
            <div class="mr-2">
                <x-agregar-button wire:click="registrarVideo()" title="REGISTRAR ESPECIALISTA">
                    REGISTRAR</x-agregar-button>
            </div>
        </div>
    </div>
    <br>
    <div class="card-body pt-2 pr-0 pl-0">
        @livewire('Administracion.videos-file.videos-file-table')
    </div>
    @include('livewire.Administracion.videos-file.modalVerVideosFile')
</div>
<script>
    document.addEventListener('preguntaHabilitarEstadoVideo', function() {
        //alert("asd");
        Swal.fire({
            title: '¿Desea habilitar el registro de video?',
            text: `Se habilitará el video`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, habilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarCambioEstadoVideo');
            }
        });
    });

    document.addEventListener('preguntaInhabilitarEstadoVideo', function() {
        //alert("asd");
        Swal.fire({
            title: '¿Desea inhabilitar el video?',
            text: `Se inhabilitará el video`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, inhabilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarCambioEstadoVideo');
            }
        });
    });

    document.addEventListener('estadoCambiadoVideo', function() {
        Swal.fire({
            title: "¡Actualizado!",
            text: "Se cambió el estado del video correctamente",
            icon: "success",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('estadoNoCambiadoVideo', function() {
        Swal.fire({
            title: "Error al intentar actualizar video",
            text: "Verifique e intente nuevamente",
            icon: "error",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });
</script>
