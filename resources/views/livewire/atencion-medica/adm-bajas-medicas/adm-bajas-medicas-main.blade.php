<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 style="color:black;"> ATENCIÓN MÉDICA - <b> LISTA DE BAJAS MÉDICAS</b></h4>
        </div>
    </div>
    <br>
    @can('lista.bajas_medicas')
        <div>
            @livewire('atencion-medica.adm-bajas-medicas.bajas-medicas-table')
            @include('livewire.atencion-medica.adm-bajas-medicas.modalEditarAdmBajasMedicas')
        </div>
    @endcan


    @can('ver.bajas_medicas')
        <x-ver-modal id="modalVerBajaMedica" labelledby="modalVerBajaMedica" title="VISTA DE BAJA MÉDICA">

            <div class="col-lg-12 text-center">
                <strong style=" letter-spacing: 1px; color: #a93226;font-size:20px">
                    FORMULARIO DE INCAPACIDAD TEMPORAL
                </strong>
            </div>
            <div style="height: 0.5rem;"></div>
            <div class="col-lg-12">
                <div class="card-borde p-2">
                    <table style="width: 100%">
                        <tr>
                            <td class="text-right" colspan="2"><b> Código de baja Médica: </b> <b
                                    style="font-size:20px">BM/N°/{{ $cod_baja }}/{{ \Carbon\Carbon::parse($fecha_creacion)->format('Y') }}
                                </b> </td>
                        </tr>
                        <tr>
                            <td colspan="1"><b>Paciente: </b> {{ $paciente }}</td>
                            <td colspan="1"><b>Edad:</b> {{ $edad }} años </td>
                        </tr>
                        <tr>
                            <td colspan="1"><b>Matrícula:</b> {{ $matricula }} {{ $codigo_institucional }} </td>
                            <td colspan="1"><b>Sexo:</b> {{ $sexo }} </td>

                        </tr>
                        <tr>
                            <td colspan="1"><b>Carnet:</b> {{ $ci }} </td>
                            <td colspan="1"><b>Tipo Paciente:</b>
                                @if ($tipo_asegurado === 'TITULAR')
                                    ASEGURADO
                                @else
                                    {{ $tipo_asegurado }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"><b>Empleador:</b> {{ $instituciones }} </td>
                            <td colspan="1"><b>Incapacidad Laboral:</b>{{ $incapacidad_laboral }} </td>
                        </tr>

                    </table>
                </div>
            </div>

            <div style="height: 0.5rem;"></div>
            <div class="col-lg-12">
                <table class="tabla-ploma " style="width: 100%">
                    <thead class="text-center">
                        <th>Motivo de Baja</th>
                        <th>Periodo</th>
                        <th>Nro. de dias</th>
                    </thead>
                    <tr>
                        <td>{{ $motivo }} </td>
                        <td>{{ $periodo }} </td>
                        <td class="text-center">{{ $nro_dias }} </td>
                    </tr>
                </table>

            </div>

            <div style="height: 0.5rem;"></div>
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <div class="p-2 card card-borde mb-2 h-100">
                        <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                            <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Nombre de usuario:</strong>
                                {{ $usuarioCreador }}
                            </li>
                            <li>
                                <strong>Fecha de registro:</strong>
                                {{ Carbon\Carbon::parse($fecha_creacion)->format('d/m/Y') }} <b> Hrs:</b>
                                {{ Carbon\Carbon::parse($fecha_creacion)->format('H:i A') }}
                            </li>

                        </ul>
                    </div>
                </div>
                @if ($fue_editado == true)
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-orange font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> MODIFICADO POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $usuario_edita }}
                                </li>
                                <li>
                                    <strong>Fecha de registro:</strong>
                                    {{ Carbon\Carbon::parse($fecha_edita)->format('d/m/Y') }} <b> Hrs:</b>
                                    {{ Carbon\Carbon::parse($fecha_edita)->format('H:i A') }}
                                </li>

                            </ul>
                        </div>
                    </div>
                @endif
                @if ($esta_anulado == true)
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-red font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> ANULADO POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $usuario_anula }}
                                </li>
                                <li>
                                    <strong>Fecha de registro:</strong>
                                    {{ Carbon\Carbon::parse($fecha_anula)->format('d/m/Y') }} <b> Hrs:</b>
                                    {{ Carbon\Carbon::parse($fecha_anula)->format('H:i A') }}
                                </li>
                                <li>
                                    <strong> Motivo:</strong>
                                    {{ $motivo_anula }}
                                </li>

                            </ul>
                        </div>
                    </div>
                @endif
            </div>

        </x-ver-modal>
    @endcan
    <style>
        .scroll-container-epi {
            max-height: 550px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
</div>

<script>
    document.addEventListener('Alerta_anular', function(event) {

        Swal.fire({
            title: '¿Anular Baja Médica?',
            icon: 'warning',
            text: 'Se anulará la baja médica registrada:',
            input: "text",
            showCancelButton: true,
            confirmButtonText: "GUARDAR",
            cancelButtonText: "CANCELAR",
            showLoaderOnConfirm: true,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Introduzca el motivo porfavor`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('funcion_anular_baja', {
                    motivo: result.value
                });
            }
        });
    });

    document.addEventListener('anulado_alerta', function() {

        Swal.fire({
            icon: 'success',
            title: 'Anulado!',
            text: 'El estado ha sido anulado correctamente.',
            confirmButtonText: 'OK'
        });
    })
    document.addEventListener('modalMostrarBajaMedica', () => {
        $('#modalVerBajaMedica').modal('show');
    });
</script>
