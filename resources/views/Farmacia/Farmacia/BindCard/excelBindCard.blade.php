<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BINDCARD DE FARMACIA</title>
    <style>
        header {
            margin: 0 30px 0 30px;
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

        .bold-text {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        table.items {
            width: 100%;
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
        }

        .items td {
            padding: 2px;
            border: 1px solid #e5e7eb;
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
        }

        .courier {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            border-collapse: collapse;
        }

        .logo {
            display: block;
            /* Asegura que las imágenes se comporten como bloques */
            margin: 0 auto;
            /* Centra las imágenes horizontalmente */
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
            height: 85px;
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
</head>

<body>
    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fecha_actual }} <br>
                    <b>Usuario: </b> {{ $user_name }}<br>
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
                <td colspan="5" style="width: 100%; font-size: 12px;" class="text-right"><span
                        style="color:#3a5260;"><b>CÓDIGO:</b></span>
                    <span class="courier">{{ $cod_medicamento }}</span>
                </td>
            </tr>
            <tr>
                <td class="vertical-top" style="width: 10%;"><img src="logo/ssu.png" width="100" height="75"
                        alt="Logo" class="logo">
                </td>

                <td class="vertical-top" style="width: 20%; font-size: 16px;"><b><span style="color:  #1a5276;">CONTROL
                            DE MEDICAMENTOS</span><br><span style="color: #a93226;"><u>FARMACIA BIND CARD</u></span></b>
                </td>
                <td style="width: 3%;"></td>
                <td class="vertical-top" style="width: 67%;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="vertical-top" style="width: 30%; color:#3a5260;"><strong>MEDICAMENTO:</strong>
                            </td>
                            <td style="width: 70%;" class="courier vertical-top">
                                {{ $nombre_medicamento }}</td>
                        </tr>
                        <tr>
                            <td class="vertical-top" style="color:#3a5260;"><strong>FORMA FARMACÉUTICA:</strong></td>
                            <td class="courier vertical-top">{{ $forma_farmaceutica }}</td>
                        </tr>
                        <tr>
                            <td class="vertical-top" style="color:#3a5260;"><strong>NÚMEROS DE REGISTROS:</strong></td>
                            <td class="courier vertical-top">{{ $numero_registros }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <hr style="border: 0; height: 1px; background-color: #54504E">

    <h3 class="text-center" style="color:#3a5260;"><u>DETALLE DE MOVIMIENTO DE MEDICAMENTOS</u></h3>
    <p class="text-center courier">DEL <b>{{ $fecha_inicio->format('d/m/Y') }}</b> HASTA
        <b>{{ $fecha_fin->format('d/m/Y') }}</b></p>

    <table class="items" style="width: 100%; font-size: 12px;">
        <thead style="background-color:#f2f2f2 ;">
            <tr>
                <th>N°</th>
                <th>FECHA</th>
                <th>SOLICITANTE</th>
                <th>Nª CODIGO</th>
                <th>INTERESADO</th>
                <th>INGRESO</th>
                <th>SALIDA</th>
                <th>SALDO</th>
                <th>OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody style="text-align:center;">
            @forelse ($datos as $index => $movimiento)
                <tr>
                    <th scope="row">{{ $index + 1 }} </th>
                    <td>{{ $movimiento['fecha'] }}</td>
                    <td style="text-align:left;">{{ $movimiento['solicitante'] }}</td>
                    <td style="text-align:center;">{{ $movimiento['codigo'] }}</td>
                    <td style="text-align:left;">{{ $movimiento['interesado'] }}</td>
                    <td style="text-align:right;">{{ $movimiento['ingreso'] }}</td>
                    <td style="text-align:right;">{{ $movimiento['salida'] }}</td>
                    <td style="text-align:right;">{{ $movimiento['saldo'] }}</td>
                    <td style="text-align:left;">{{ $movimiento['observacion'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No hay registros para este rango de fechas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fecha_actual }} <br>
                    <b>Usuario: </b> {{ $user_name }}<br>

                </td>
                <td class="text-left" style="font-size: 9px; vertical-align: top;">
                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630 , La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                        </b></em>
                </td>
                <td class="text-left">
                    <img src="images/logo_fondo_blanco.png" width="150" height="90" alt="1Logo"
                        style="opacity: 0.7;">
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
