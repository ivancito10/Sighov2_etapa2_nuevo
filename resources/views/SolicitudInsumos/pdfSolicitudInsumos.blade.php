<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLICITUD DE MEDICAMENTOS E INSUMOS</title>
    <style>
        @page {
            background: transparent;
        }

        header {
            margin: 10px 30px 0 30px;
            /* Márgenes específicos para el encabezado */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }

        html,
        body {
            margin: 0;
            padding-top: 10mm;
            padding-bottom: 30mm;
            padding-left: 10mm;
            padding-right: 10mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            background-size: cover;
        }

        table.items {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .items th {
            background: #9B9592;
            color: #FFFFFF;
            font-weight: 700;
            padding: 2px;
            border: 1px solid #e5e7eb;
            text-align: center;

            word-wrap: break-word;
            /* Rompe palabras largas */
            overflow-wrap: break-word;
            /* Compatibilidad moderna */
            word-break: break-all;
            /* Rompe incluso si no hay espacios */
            white-space: normal;
        }

        .items td {
            padding: 2px;
            border: 1px solid #e5e7eb;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;

            word-wrap: break-word;
            /* Rompe palabras largas */
            overflow-wrap: break-word;
            /* Compatibilidad moderna */
            word-break: break-all;
            /* Rompe incluso si no hay espacios */
            white-space: normal;
        }

        .courier {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
        }

        /* Pie de página */
        footer {
            margin: 0;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8f9fa;
            text-align: center;
            font-size: 14px;
            width: 100%;
            height: 25mm;
            padding-left: 15px;
            padding-right: 20px;
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            border-collapse: collapse;
        }

        /* utilidades */
        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .vertical-top {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fechaHoy }} <br>
                    <b>Usuario: </b> {{ $nombre_usuario }}<br>
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
                    <img src="images/logo_fondo_blanco.png" width="140" height="90" alt="1Logo"
                        style="opacity: 0.7;">
                </td>
            </tr>
        </table>
    </footer>

    <div>
        <table style="width: 100%;">
            <tr>
                <td colspan="4" style="width: 74%; font-size: 12px;" class="courier text-right"><b>Nº:</b>
                    {{ $cod_solicitud }}</td>
            </tr>
            <tr>
                <td class="vertical-top" style="width: 10%;"><img src="logo/ssu.png" width="100" height="75"
                        alt="Logo" class="logo">
                </td>

                <td class="vertical-top" style="width: 20%; font-size: 16px;"><b><span
                            style="color:  #1a5276;">SOLICITUD DE
                            MEDICAMENTOS E
                            INSUMOS</span><br><span style="color: #a93226;"><u>{{ $nombreUnidad }}</u></span></b></td>
                <td style="width: 3%;"></td>
                <td class="vertical-top" style="width: 67%;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="vertical-top" style="width: 32%; color:#3a5260;"><strong> UNIDAD
                                    SOLICITANTE:</strong></td>
                            <td style="width: 68%;" class="courier vertical-top">
                                {{ $unidadSolicitante }}</td>
                        </tr>
                        <tr>
                            <td class="vertical-top" style="color:#3a5260;"><strong>SOLICITANTE:</strong></td>
                            <td class="courier vertical-top">{{ $usuarioSolicita }}</td>
                        </tr>

                        <tr>
                            <td class="vertical-top" style="color:#3a5260;"><strong>CARGO:</strong></td>
                            <td class="courier vertical-top">{{ $cargoSolicitante }}</td>
                        </tr>
                    </table>
                </td>
        </table>
    </div>

    <hr style="border: 0; height: 1px; background-color: #54504E">

    <table style="width: 100%;">
        <tr>
            <td class="vertical-top" style="width: 20%; color:#3a5260;"><strong>FECHA DE
                    SOLICITUD:<br>MOTIVO:</strong></td>
            <td class="courier vertical-top" style="width: 26%;">{{ $fecha_solicitud }}<br>{{ $motivo }}
            </td>
            <td class="vertical-top" style="width: 18%; color:#3a5260;"><strong>ESTADO
                    SOLICITUD:<br>DESCRIPCIÓN:</strong></td>
            <td class="courier vertical-top" style="width: 26%;">{{ $estadoValidacion }}
                <br>{{ $descripcion }}
            </td>
            <td class="vertical-top text-right" style="width: 10%;" rowspan="2"><img src="{{ $imagenQr }}"
                    height="75" width="75" alt="QR SOL"></td>
        </tr>

        <tr>
            <td class="text-left" colspan="4" style="color: #3a5260; font-size: 16px;">
                <b><ins>DETALLE DE SOLICITUD</ins></b>
            </td>
        </tr>
    </table>

    <table class="items" style="width: 100%; font-size: 10px;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th rowspan="2" style="width: 6%;">Nº</th>
                <th rowspan="2" style="width: 11%;">CÓDIGO MEDICAMENTO</th>
                <th rowspan="2" style="width: 28%;">MEDICAMENTO / INSUMO</th>
                <th rowspan="2" style="width: 20%;">FORMA FARMACÉUTICA</th>
                <th rowspan="2" style="width: 15%;">CONCENTRACIÓN</th>
                <th colspan="2" style="width: 20%;">CANTIDAD</th>
            </tr>
            <tr>
                <th style="width: 10%;">SOLICITADA</th>
                <th style="width: 10%;">ENTREGADA</th>
            </tr>
        </thead>
        <tbody>
            @if ($medicamentos)
                @foreach ($medicamentos as $index => $medicamentoSolicitud)
                    <tr>
                        <td scope="row" style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">
                            {{ $medicamentoSolicitud->medicamentoMedicamentoSolicitud->codigo_medicamento }}</td>
                        <td>{{ $medicamentoSolicitud->medicamentoMedicamentoSolicitud->nombre }}</td>
                        <td>{{ $medicamentoSolicitud->medicamentoMedicamentoSolicitud->forma_farmaceutica }}</td>
                        <td>{{ $medicamentoSolicitud->medicamentoMedicamentoSolicitud->concentracion }}</td>
                        <td style="text-align: center;">{{ $medicamentoSolicitud->cantidad_solicitada }}</td>
                        <td style="text-align: center;">{{ $medicamentoSolicitud->cantidad_entregada ?? 0 }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center;">No hay medicamentos disponibles</td>
                </tr>
            @endif
            <tr>
                <td colspan="2" style="height: 80px; vertical-align: bottom; font-weight: bold; font-size: 12px;">
                    UNIDAD SOLICITANTE</td>
                <td colspan="2" style="height: 80px; vertical-align: bottom; font-weight: bold; font-size: 12px;">
                    V°B° JEFE DE UNIDAD SOLICITANTE</td>
                <td colspan="3" style="height: 80px; vertical-align: bottom; font-weight: bold; font-size: 12px;">
                    V°B° GERENTE DE SALUD</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
