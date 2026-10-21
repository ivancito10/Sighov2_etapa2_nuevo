<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLICITUD A ALMACEN DE MEDICAMENTOS</title>
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
            border-collapse: collapse;
            margin-top: 2px;
            table-layout: fixed;
        }

        .items th {
            background: #e7e7e7;
            color: black;
            font-weight: 700;
            padding: 2px;
            border: 1px solid #141414;
            text-align: center;
        }

        .items td {
            padding: 2px;
            border: 1px solid #202020;
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;


            word-wrap: break-word;
            /* Rompe palabras largas */
            overflow-wrap: break-word;
            /* Compatibilidad moderna */
            word-break: break-all;
            /* Rompe incluso si no hay espacios */
            white-space: normal;
            /* Evita que el texto se mantenga en una sola línea */
        }

        .courier {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
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


        .document-container {
            /* Mantenemos el padding lateral, pero nos aseguramos de no tener padding-top que genere espacio */
            padding-left: 0.7mm;
            /* Ajusta este valor para el margen izquierdo general del contenido */
            padding-right: 0.7mm;
            /* Ajusta este valor para el margen derecho general del contenido */

            background-color: white;
            /* border-radius: 5px; /* Comentado, si no necesitas bordes redondeados en el contenedor */
            position: relative;
            box-sizing: border-box;
            /* Para que el padding no aumente el ancho total */
            margin: 0;
            padding: 0;
        }

        .header-table {
            margin-bottom: 0px;
            /* Elimina el margen inferior */
            margin-top: 0px;
            /* Aseguramos que no haya margen superior en la tabla */
            padding-top: 0px;
            /* Aseguramos que no haya padding superior en la tabla */
            border: none;
            /* Quitamos el borde sólido de esta tabla */
        }

        .header-table td {
            border: none !important;
            /* Aseguramos que las celdas no tengan bordes */
            padding: 0;
            /* Quitamos padding de las celdas */
            vertical-align: middle;
        }

        .header-table img {
            display: block;
            /* Ayuda a controlar el espacio si hay algún margen invisible alrededor de la imagen */
            /* Si la imagen tiene un margen inferior por alguna razón, puedes añadir: */
            /* margin-bottom: -5px; */
            /* Ajusta si es necesario */
        }

        .header-table .first-col {
            width: 8%;
            /* Ajuste de ancho si es necesario */
            padding-right: 0;
        }

        .header-table .second-col {
            width: 44%;
            padding-left: 5px;
            /* Mantenemos este padding para separar del logo */
            font-size: 14px;
        }

        .header-table .third-col {
            width: 48%;
            text-align: right;
            padding-right: 0;
        }

        /* -------------------------------------------------------- */
        /* NUEVOS ESTILOS PARA LA SECCIÓN DE FIRMAS */
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1px;
            border: none;
        }

        .signatures-table td {
            border: none !important;
            padding-top: 8px;
            text-align: center;
            vertical-align: top;
            font-size: 12px;
            position: relative;
            line-height: 1.2;
        }


        .signatures-table td::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            border-bottom: 1px solid #000;
            width: 70%;
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
        <div class="document-container">
            <table class="header-table" style="width: 100%;">
                <thead>
                    <tr>
                        <td class="first-col">
                            <img src="logo/ssu.png" width="90px" height="65px" alt="Logo">
                        </td>
                        <td class="second-col">
                            <span style="color: #1a5276;"><b> SOLICITUD DE MEDICAMENTOS E INSUMOS </b></span><br>
                            <span style="text-decoration: underline; color:#a93226; letter-spacing: 1px;"> <b>
                                    {{ $nombreUnidad }}</b> </span>
                        </td>
                        <td class="third-col">
                            <span style="font-size:18px;"><b>N° </b> {{ $cod_solicitud }}</span> <br>
                            <span>
                                <b>Unidad Solicitante: </b> {{ $unidadSolicitante }} <br>
                                <b>Solicitante:</b> {{ $usuarioSolicita }} <br>
                                <b>Cargo:</b> {{ $cargoSolicitante }}
                            </span>
                        </td>

                    </tr>
                </thead>
            </table>
        </div>
    </div>


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
            <td class="text-left" colspan="4" style="color: #3a5260; font-size: 14px;">
                <b><ins>DETALLE DE SOLICITUD</ins></b>
            </td>
        </tr>
    </table>


    <table class="items" style="width: 100%; font-size: 10px;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th style="width: 5%">Nº</th>
                <th style="width: 11%">CÓDIGO MEDICAMENTO</th>
                <th style="width: 34%">MEDICAMENTO / INSUMO</th>
                <th style="width: 25%">FORMA FARMACÉUTICA</th>
                <th style="width: 15%">CONCENTRACIÓN</th>
                <th style="width: 10%">CANTIDAD ENTREGADA</th>
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
                        <td style="text-align: center;">
                            {{ $medicamentoSolicitud->cantidad_entregada !== null
                                ? number_format($medicamentoSolicitud->cantidad_entregada, 0, ',', '.')
                                : '' }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center;">No hay medicamentos disponibles</td>
                </tr>
            @endif


        </tbody>
    </table>
    <br><br><br><br>
    <table class="signatures-table">
        <tr>
            <td>
                Firma/Sello Farmacéutica
            </td>

            <td>
                Firma/Sello Encargado de <br>
                Almacen de Medicamentos
            </td>
        </tr>
    </table>
</body>

</html>
