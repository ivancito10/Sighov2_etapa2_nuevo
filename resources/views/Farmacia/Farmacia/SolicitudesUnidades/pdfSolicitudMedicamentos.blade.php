ht
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLICITUD DE MEDICAMENTOS E INSUMOS</title>
    <style>
        html,
        body {
            margin: 25px;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;
            background-size: cover;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            height: 100px;
        }

        .header img {
            float: right;
        }

        .highlight {
            font-weight: bold;
            text-decoration: underline;
        }

        .details-table td {
            text-align: left;
            padding-left: 20px;
        }

        .summary-table {
            margin-top: 20px;
            width: 100%;
            max-width: 500px;
            font-size: 12px;
            text-align: left;
        }

        .titulo {
            margin-left: 80px;
            padding-top: 20px;
        }

        .qr {
            position: absolute;
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            left: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .document-title {
            font-size: 19px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Pie de página */
        .footer {
            background-color: #f8f9fa;
            padding-top: 3px;
            text-align: center;
            font-size: 14px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 85px;
            /* Ajusta el alto */
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            border-collapse: collapse;
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <!-- Columna Izquierda -->
            <td class="logo">
                <img src="logo/ssu.png" width="80" height="90" alt="Logo">
            </td>

            <!-- Columna Central -->
            <td class="text">
                <h2><ins>SEGURO SOCIAL UNIVERSITARIO - LA PAZ</ins></h2>
                <h3>DETALLE DE SOLICITUD DE MEDICAMENTOS E INSUMOS</h3>
            </td>

            <!-- Columna Derecha -->
            <td class="logo">
                <img src="logo/logo2.png" width="90" height="55" alt="Logo">
            </td>
        </tr>
    </table>
    <br>

    <table class="table table-bordered" style=" width: 100%; font-size: 12px; text-align:left;">
        <tr>
            <td style="padding-left: 20px;"><b>UNIDAD:</b> </td>
            <td>{{ $unidadSolicitante }}</td>
            <td class="text-left" style="padding-left: 20px;"><b>NÚMERO PEDIDO:</b></td>
            <td>{{ $cod_solicitud }}</td>
        </tr>

        <tr>
            <td style="padding-left: 20px;"><b>SOLICITANTE:</b></td>
            <td>{{ $usuarioSolicita }}</td>
            <td class="text-left" style="padding-left: 20px;"><b>CARGO:</b></td>
            <td>{{ $cargoSolicitante }}</td>
        </tr>

        <tr>
            <td style="padding-left: 20px;"><b>FECHA PEDIDO:</b> </td>
            <td>{{ $fecha_solicitud }}</td>
            <td class="text-left" style="padding-left: 20px;"><b>ESTADO:</b></td>
            <td>{{ $estadoValidacion }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>MOTIVO:</b> </td>
            <td colspan="3">{{ $motivo }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>DESCRIPCIÓN:</b> </td>
            <td colspan="3">{{ $descripcion }}</td>
        </tr>
        @if ($estadoValidacion == 'APROBADO')
            <tr>
                <td style="padding-left: 20px;"><b>APROBADO POR:</b> </td>
                <td>{{ $usuarioAprueba }}</td>
                <td style="padding-left: 20px;"><b>FECHA DE APROBACIÓN:</b> </td>
                <td>{{ $fecha_aprobacion }}</td>
            </tr>
        @endif
        @if ($estadoValidacion == 'ENTREGADO')
            <tr>
                <td style="padding-left: 20px;"><b>APROBADO POR:</b> </td>
                <td>{{ $usuarioAprueba }}</td>
                <td style="padding-left: 20px;"><b>FECHA DE APROBACIÓN:</b> </td>
                <td>{{ $fecha_aprobacion }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;"><b>ENTREGADO POR:</b> </td>
                <td>{{ $usuarioEntrega }}</td>
                <td style="padding-left: 20px;"><b>FECHA DE ENTREGA:</b> </td>
                <td>{{ $fecha_entregado }}</td>
            </tr>
        @endif
        @if ($estadoValidacion == 'RECHAZADO')
            <tr>
                <td style="padding-left: 20px;"><b>ANULADO POR:</b> </td>
                <td>{{ $usuarioAnula }}</td>
                <td style="padding-left: 20px;"><b>FECHA ANULACIÓN:</b> </td>
                <td>{{ $fecha_anulacion }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;"><b>MOTIVO ANULACIÓN:</b> </td>
                <td colspan="3">{{ $motivo_anulacion }}</td>
            </tr>
        @endif
    </table>
    <br>

    <h3 style="text-align:center;"><ins>Listado de medicamentos e insumos solicitados</ins></h3>
    <br>
    <table class="table table-bordered" style="width: 100%; text-align: center; font-size: 12px;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">COD. MEDICAMENTO</th>
                <th rowspan="2">MEDICAMENTO</th>
                <th rowspan="2">FORMA FARMACÉUTICA</th>
                <th rowspan="2">CONCENTRACIÓN</th>
                <th colspan="2">CANTIDAD</th>
            </tr>
            <tr>
                <th>SOLICITADA</th>
                <th>ENTREGADA</th>
            </tr>
        </thead>
        <tbody>
            @if ($medicamentos)
                @foreach ($medicamentos as $index => $medicamentoSolicitud)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $medicamentoSolicitud->medicamentoMedicamentoSolicitud->codigo_medicamento }}</td>
                        <td>{{ $medicamentoSolicitud->medicamentoMedicamentoSolicitud->nombre }}</td>
                        <td>{{ $medicamentoSolicitud->medicamentoMedicamentoSolicitud->forma_farmaceutica }}</td>
                        <td>{{ $medicamentoSolicitud->medicamentoMedicamentoSolicitud->concentracion }}</td>
                        <td>{{ $medicamentoSolicitud->cantidad_solicitada }}</td>
                        <td>{{ $medicamentoSolicitud->cantidad_entregada ?? 0 }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center;">No hay medicamentos disponibles</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fechaActual }} <br>
                    <b>Usuario: </b> <br>
                </td>

                <td class="text-left" style="font-size: 9px; vertical-align: top;">

                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630, La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                        </b></em>
                </td>
                <td>
                    <img src="logo/ssu.png" width="80" height="90" alt="Logo">
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
