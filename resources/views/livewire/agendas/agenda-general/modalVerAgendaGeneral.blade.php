<div>
    <x-ver-dos-modal id="verModalAgendaGeneral" labelledby="verModalAgendaGeneralLabel" title=" DETALLE DE AGENDA ">
        <div class="row ">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 ">
                <div class="card-tipico p-2">
                    <p style="background-color:#c4dff3 ;text-align:center;border-radius:6px">
                        <b>AGENDADO</b>
                    </p>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>NOMBRE COMPLETO:</strong>
                            <p>{{ $nombre_asegurado }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <strong>EDAD:</strong>
                            <p>{{ $edad }}</p>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                            <strong>GÉNERO:</strong>
                            <p>{{ $genero }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <strong> ASISTENCIA:</strong>
                            @if ($asistencia === true)
                                <p class="parpadeo"
                                    style="display: inline-block; background-color: #28a745; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 1rem;">
                                    ASISTIÓ
                                </p>
                            @elseif ($asistencia === true)
                                <p class="parpadeo"
                                    style="display: inline-block; background-color: #dc3545; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                                    NO ASISTIÓ
                                </p>
                            @else
                                <p class="parpadeo"
                                    style="display: inline-block; background-color: #f9c80d; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                                    PENDIENTE
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 ">
                <div class="card-tipico p-2">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 ">
                            <p style="background-color:#c4dff3 ;text-align:center;border-radius:6px">
                                <b>AGENDA</b>
                            </p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 ">

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right ">

                            @if ($ficha_anticipada === true)
                                <strong>Tipo:</strong>
                                <span class=" h4 font-weight-bold "> FICHA ANTICIPADA</span>
                            @elseif ($ficha_extra === true)
                                <strong>Tipo:</strong>
                                <span class=" h4 font-weight-bold "> FICHA EXTRA</span>
                            @endif
                        </div>
                    </div>
                    <div class="div-tabla-responsive">
                        <table class="table table-bordered" sytle="width:100%;">
                            <tbody>
                                <tr>
                                    <td> <b>HORA :</b> {{ $hora_agenda }}</td>
                                    <td> <b>FECHA :</b> {{ $fecha_agenda }}</td>
                                    <td><b> MEDIANTE:</b> {{ $tipo_ficha }}</td>
                                    <td><b> REGISTRADO POR:</b> {{ $creado_por }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"> <b>ESPECIALIDAD: </b> {{ $especialidad }}</td>
                                    <td colspan="2"> <b>ESPECIALISTA: </b> {{ $especialista }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="div-tabla-responsive">
                        <table class="table table-bordered" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td> <b>IP :</b> {{ $ip }}</td>
                                    <td> <b>EQUIPO :</b> {{ $nombre_equipo }} </td>
                                </tr>
                                <tr>
                                    <td colspan="4"><b> OBSERVACIONES:</b> {{ $observacion }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($tiene_suspension)
                    <br>
                    <div class="card-tipico p-2">
                        <p style="color:#0367bf "> <b>DATOS DE SUSPENSIÓN:</b></p>
                        <div class="div-tabla-responsive">
                            <table class="table table-bordered" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td> <b>CREADO POR :</b> {{ $suspension_creador }}</td>
                                        <td> <b>FECHA :</b> {{ $fecha_suspension }}</td>
                                        <td> <b>NRO:</b> {{ $nro_suspension }} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b> OBSERVACIONES:</b> {{ $observacion_suspension }} </td>
                                        <td><b> MOTIVO:</b> {{ $motivo_suspension }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($ficha_anulada)
                    <br>
                    <div class="card-tipico p-2">
                        <p style="color:#d60000"> <b>DATOS DE ANULACIÓN:</b></p>
                        <div class="div-tabla-responsive">
                            <table class="table table-bordered" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td> <b>FECHA:</b> {{ $fecha_anulacion }}</td>
                                        <td colspan="3"><b>MOTIVO :</b> {{ $motivo_anulacion }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-ver-dos-modal>
    <script>
        window.addEventListener('modalVerAgendaGeneral', event => {
            $('#verModalAgendaGeneral').modal('show');
        });
    </script>
    <style>
        @keyframes parpadeo {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .parpadeo {
            animation: parpadeo 1s infinite;
        }

        .card-tipico {
            border-radius: 10px;
            padding: 2px;
            box-shadow: 1px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .div-tabla-responsive {
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>

</div>
