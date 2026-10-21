<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> FARMACIA - <b> SOLICITUD A ALMACEN DE MEDICAMENTOS</b></h5>
        </div>
        @can('registrar.solicitud_almacen')
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 text-end">
                <x-agregar-button wire:click="redirigirRegistrarSolicitudAlmancen">REGISTRAR</x-agregar-button>
            </div>
        @endcan
    </div>
    <br>
    <div class="row">
        @can('lista.solicitud_almacen')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('farmacia.farmacia.solicitudes-almacen.solicitudes-almacen-table')
            </div>
        @endcan
    </div>
    @can('ver.solicitud_almacen')
        @include('livewire.farmacia.farmacia.solicitudes-almacen.modalVerSolcitudesAlmacen')
    @endcan
    @can('pdf.solicitud_almacen')
        @include('livewire.farmacia.farmacia.solicitudes-almacen.modalVerPdfSolicitud')
    @endcan

</div>



<script>
    document.addEventListener('alerta_anular_solicitud', function() {
        Swal.fire({
            title: '¿Rechazar la solicitud?',
            text: "Se rechazará la solicitud seleccionada.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, rechazar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, pedir el motivo
                Swal.fire({
                    title: ' Motivo de rechazo',
                    icon: 'question',
                    text: 'Describa el motivo del rechazo de la solicitud:',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'on'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    confirmButtonColor: '#27ae60',
                    cancelButtonColor: '#c0392b',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: async (motivo_texto) => {
                        if (!motivo_texto) {
                            Swal.showValidationMessage(
                                'Introduzca el motivo del rechazo de la solicitud, por favor.'
                            );
                            return false;
                        }
                        return motivo_texto;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((motivoResult) => {
                    if (motivoResult.isConfirmed) {
                        // Aquí se despacha la acción con el motivo
                        Livewire.dispatch('guardar_anular_solicitud', [motivoResult.value]);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Cancelado!',
                    text: 'Usted canceló el registro.',
                    icon: 'error'
                });
            }
        });

    });


    document.addEventListener('rechazo_guardado', function() {
        Swal.fire({
            title: 'Guardado!',
            text: "La solicitud de medicamento fue rechazada con éxito",
            icon: 'success'
        });
    });
</script>
