<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> ALMACEN GENERAL - <b> SOLICITUDES PENDIENTES</b></h5>
        </div>
    </div>
    <div class="row">
        @can('lista.solicitudes_pendientes_almacen_general')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('solicitudes-pendientes-materiales.solicitudes-pendientes-materiales-table')
            </div>
        @endcan

        @can('ver.solicitudes_pendientes_almacen_general')
            <!-- Modal para Ver datos del proveedor -->
            @include('livewire.solicitudes-pendientes-materiales.modalVerSolicitudesPendientesMateriales')
        @endcan
    </div>
</div>
<script>
    document.addEventListener('anulaSolicitud', function() {
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
                        Livewire.dispatch('anularSol', [motivoResult.value]);
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

    document.addEventListener('aprobarSolicitudFuncion', function() {
        Swal.fire({
            title: '¿Aprobar solicitud?',
            text: "Se aprobará la solicitud seleccionada",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, aprobar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('aprobandoSol');
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Se canceló la operación",
                    icon: "error"
                });
            }
        });
    });


    document.addEventListener('solicitudRechazada', function() {
        Swal.fire({
            title: '¡Rechazado con éxito!',
            text: "La solicitud de material fue rechazada con éxito",
            icon: 'success'
        });
    });

    document.addEventListener('solicitudAprobada', function() {
        Swal.fire({
            title: '¡Aprobado con éxito!',
            text: "La solicitud de material fue aprobada con éxito",
            icon: 'success'
        });
    });

    document.addEventListener('errorFuncion', function() {
        Swal.fire({
            title: '¡Ha ocurrido un error!',
            text: "Ocurrió un error, intente de nuevo. Si el error continúa, contactese con la unidad de desarrollo",
            icon: 'warning'
        });
    });


    document.addEventListener('cantidadSuperaStock', function() {
        Swal.fire({
            title: "<b>La cantidad es mayor al Stock</b>",
            text: "Las cantidades introducidas superan el stock existente. Verifique e intente nuevamente.",
            icon: "warning",
            allowEscapeKey: false,
            allowOutsideClick: false
        });
    });

    document.addEventListener('cantidadSuperaSolicitado', function() {
        Swal.fire({
            title: "<b>La cantidad supera lo solicitado</b>",
            text: "Las cantidades introducidas superan la cantidad solicitada. Verifique e intente nuevamente.",
            icon: "warning",
            allowEscapeKey: false,
            allowOutsideClick: false
        });
    });

    document.addEventListener('cantidadVacia', function() {
        Swal.fire({
            title: "<b>La cantidad esta vacía</b>",
            text: "Las cantidades no tienen un valor valido. Verifique e intente nuevamente.",
            icon: "warning",
            allowEscapeKey: false,
            allowOutsideClick: false
        });
    });
</script>
