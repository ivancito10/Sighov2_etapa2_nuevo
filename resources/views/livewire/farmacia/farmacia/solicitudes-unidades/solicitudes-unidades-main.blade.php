<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#212f3c ;">FARMACIA - <b> LISTA DE SOLICITUDES DE UNIDADES</b></h4>

    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @can('lista.farmacia_solicitudes_pendientes_unidades')
                @livewire('Farmacia.Farmacia.Solicitudes-unidades.Solicitudes-unidades-table')
            @endcan
            @can('ver.farmacia_solicitudes_pendientes_unidades')
                @include('livewire.farmacia.farmacia.solicitudes-unidades.modalVerSolicitud')
            @endcan
            @can('pdf.farmacia_solicitudes_pendientes_unidades')
                @include('livewire.farmacia.farmacia.solicitudes-unidades.modalVerPdfSolicitud')
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
            title: `Anular solicitud?`,
            icon: 'question',
            text: 'Describa el motivo a continuación:',
            input: "text",
            //inputLabel: "OBSERVACIÓN: ",
            inputAttributes: {
                autocapitalize: "on"
            },
            showCancelButton: true,
            confirmButtonText: "Si, anular",
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

    document.addEventListener('solicitudAnulada', function() {
        Swal.fire({
            title: 'Solicitud anulada',
            text: "Se anuló la solicitud correctamente",
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
</script>
