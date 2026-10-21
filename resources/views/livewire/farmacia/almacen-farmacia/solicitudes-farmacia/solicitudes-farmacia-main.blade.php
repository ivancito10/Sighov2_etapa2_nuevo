<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#212f3c ;">ALMACEN DE MEDICAMENTOS - <b> LISTA DE SOLICITUDES DE FARMACIA</b></h4>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @can('lista.almacen_farmacia_solicitudes_pendientes_farmacia')
                @livewire('farmacia.almacen-farmacia.solicitudes-farmacia.solicitudes-farmacia-table')
            @endcan
            @can('ver.almacen_farmacia_solicitudes_pendientes_farmacia')
                @include('livewire.farmacia.almacen-farmacia.solicitudes-farmacia.modalVerSolicitud')
            @endcan
            @can('pdf.almacen_farmacia_solsicitudes_pendientes_farmacia')
                @include('livewire.farmacia.almacen-farmacia.solicitudes-farmacia.modalVerPdfSolicitud')
            @endcan

        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>
</div>


<script>
    window.addEventListener('anular_solicitud', event => {
        Swal.fire({
            title: '¿Rechazar la solicitud?',
            text: "Se rechazará la solicitud seleccionada",
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
                                'Introduzca el motivo del rechazo de la solicitud, por favor'
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
                    title: '¡Cancelado!',
                    text: 'Se canceló la anulación',
                    icon: 'error'
                });
            }
        });
    });
</script>
