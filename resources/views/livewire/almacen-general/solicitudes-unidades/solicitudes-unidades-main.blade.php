<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#212f3c ;">ALMACEN GENERAL - <b> LISTA DE SOLICITUDES DE UNIDADES</b></h4>

    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @can('lista.solicitudes_unidades_almacen_general')
                @livewire('almacen-general.solicitudes-unidades.solicitudes-unidades-table')
            @endcan

            @can('ver.solicitudes_unidades_almacen_general')
                @include('livewire.almacen-general.solicitudes-unidades.modalVerSolicitud')
            @endcan
            {{--  
            @can('pdf.solicitudes_unidades_almacen_general')
                @include('almacen-general.solicitudes-unidades.modalVerPdfSolicitud')
            @endcan --}}
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>

    </div>
</div>


<script>
    window.addEventListener('anular_solicitud', event => {
        Swal.fire({
            title: `¿Desea rechazar la solicitud?`,
            icon: 'question',
            text: 'Describa el motivo a continuación:',
            input: "text",
            //inputLabel: "OBSERVACIÓN: ",
            inputAttributes: {
                autocapitalize: "on"
            },
            showCancelButton: true,
            confirmButtonText: "Si, rechazar",
            cancelButtonText: "No, cancelar",
            showLoaderOnConfirm: true,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Introduzca el motivo de la anulación porfavor`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()


        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardar_anular_solicitud', [result.value]);
            }
            /*else {
                           Swal.fire({
                               title: "Anulacion cancelada",
                               text: "No se anuló la solicitud.",
                               icon: "warning"
                           });
                       }*/
        });
    });

    window.addEventListener('preguntarEnviarAprobacion', event => {
        Swal.fire({
            title: `<b>¿Desea marcar como revisada la solicitud?</b>`,
            icon: 'question',
            text: 'La solicitud será enviada directamente para su aprobación',
            showCancelButton: true,
            confirmButtonText: "Si, marcar como revisado",
            cancelButtonText: "No, cancelar",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarEnviarAprobacionSolicitud');
            } else {
                Swal.fire({
                    title: "Se canceló en envío",
                    text: "Aun puede revisar la solicitud",
                    icon: "warning"
                });
            }
        });
    });

    document.addEventListener('solicitudRevisada', function() {
        Swal.fire({
            title: '<b>Solicitud enviada correctamente</b>',
            text: "La solicitud se encuentra disponible para su aprobación",
            icon: 'success'
        });
    });

    document.addEventListener('errorRevision', function() {
        Swal.fire({
            title: "Cancelado",
            text: "Error inesperado",
            icon: "error"

        });
    });

    document.addEventListener('solicitudAnulada', function() {
        Swal.fire({
            title: 'Solicitud rechazada',
            text: "Se rechazó la solicitud",
            icon: 'success'

        });
    });

    document.addEventListener('errorAnulacion', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "No se anuló la solicitud.",
            icon: "error"

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
