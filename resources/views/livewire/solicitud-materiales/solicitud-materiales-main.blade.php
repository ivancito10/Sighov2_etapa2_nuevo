<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> ALMACEN GENERAL - <b> SOLICITUD DE MATERIALES Y SUMINISTROS</b></h5>
        </div>
        @if ($esPersonal == true)
            @can('registrar.solicitudes_materiales_almacen_general')
                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">
                    <x-agregar-button wire:click="redirigirRegistrarSolicitud">REGISTRAR</x-agregar-button>
                </div>
            @endcan
        @endif
    </div>
    <br>
    <div class="row">
        @can('lista.solicitudes_materiales_almacen_general')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('solicitud-materiales.solicitud-materiales-table')
            </div>
        @endcan

    </div>
    <!-- Modal para Ver datos de ingreso de medicamentos -->
    @can('ver.solicitudes_materiales_almacen_general')
        @include('livewire.solicitud-materiales.modalVerSolicitudMateriales')
    @endcan
</div>

<script>
    window.addEventListener('registrar_solicitud', event => {
        Swal.fire({
            title: `<b>¿Enviar solicitud para su revisión?</b>`,
            icon: 'warning',
            text: 'Al enviar la solicitud para su revisión ya no podrá editarla',
            showCancelButton: true,
            confirmButtonText: "Si, enviar",
            cancelButtonText: "No, cancelar",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarRegistroSolicitud', [result.value]);
            } else {
                Swal.fire({
                    title: "Envío cancelada",
                    text: "Se canceló el envío",
                    icon: "warning"
                });
            }
        });
    });

    document.addEventListener('solicitudEnviadaRevision', function() {
        Swal.fire({
            title: '<b>Solicitud enviada para su revisión</b>',
            text: "Se envió la solicitud correctamente",
            icon: 'success'

        });
    });

    document.addEventListener('errorEnviarRevision', function() {
        Swal.fire({
            title: "¡Cancelado!",
            text: "No se envió la solicitud.",
            icon: "error"
        });
    });


    window.addEventListener('anular_solicitud', event => {
        Swal.fire({
            title: `<b>¿Anular solicitud?</b>`,
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
