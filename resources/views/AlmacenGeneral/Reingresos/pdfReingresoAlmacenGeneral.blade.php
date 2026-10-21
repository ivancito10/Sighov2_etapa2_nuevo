<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REINGRESO DE MATERIALES</title>
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

        .firmas-container {
            margin-top: 1cm;
            width: 100%;
            border-collapse: collapse;
        }

        .firmas-container td {
            width: 33.33%;
            text-align: center;
            padding: 10px 0;
            font-weight: bold;
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
                <td colspan="4" style="width: 74%; font-size: 12px;" class="courier text-right"><b>Nº:</b>
                    {{ $codigoReingreso }}</td>
            </tr>
            <tr>
                <td class="vertical-top" style="width: 10%;"><img src="logo/ssu.png" width="100" height="75"
                        alt="Logo" class="logo">
                </td>

                <td class="vertical-top" style="width: 20%; font-size: 16px;"><b><span
                            style="color:  #1a5276;">FORMULARIO DE
                            REINGRESOS DE MATERIALES A</span><br><span style="color: #a93226;">ALMACEN
                            GENERAL</span></b></td>
                <td style="width: 3%;"></td>
                <td class="vertical-top" style="width: 67%;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="vertical-top" style="width: 32%; color:#3a5260;"><strong> UNIDAD
                                    ORIGEN:</strong>
                            </td>
                            <td style="width: 68%;" class="courier vertical-top">
                                {{ $unidadReingresante }}</td>
                        </tr>

                        <tr>
                            <td class="vertical-top" style="color:#3a5260;"><strong>FECHA DE REINGRESO:</strong></td>
                            <td class="courier vertical-top">{{ $fechaIngreso }}</td>
                        </tr>
                    </table>
                </td>
        </table>
    </div>

    <hr style="border: 0; height: 1px; background-color: #54504E">

    <table style="width: 100%;">
        <tr>
            <td style="text-align: left; color: #3a5260;">
                <h3><ins>DETALLE DE REINGRESO</ins></h3>
            </td>

            <td style="text-align: right; vertical-align: top;"><img src="{{ $imagenQr }}" height="75"
                    width="75" alt="QR SOL"></td>
        </tr>
    </table>


    <table class="items" style="width: 100%; font-size: 10px;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th style="width: 2%">Nº</th>
                <th style="width: 5%">CÓDIGO</th>
                <th style="width: 15%">MATERIAL O SUMINISTRO</th>
                <th style="width: 15%">UNIDAD DE MEDIDA</th>
                <th style="width: 15%">DESCRIPCIÓN</th>
                <th style="width: 10%">CANTIDAD</th>
                <th style="width: 8%">FECHA DE VENC.</th>
                <th style="width: 30%">MOTIVO DE REINGRESO</th>
            </tr>
        </thead>
        <tbody>
            @if ($reingreso->detallesMaterial && $reingreso->detallesMaterial->count() > 0)
                @foreach ($reingreso->detallesMaterial as $index => $detalle)
                    <tr>
                        <td scope="row" style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">
                            {{ $detalle->materialReingresoMaterial->codigo }}</td>
                        <td>{{ $detalle->materialReingresoMaterial->nombre_material }}</td>
                        <td>{{ $detalle->materialReingresoMaterial->unidad }}</td>
                        <td>{{ $detalle->materialReingresoMaterial->descripcion }}</td>
                        <td style="text-align: center;">{{ $detalle->cantidad_ingreso }}</td>
                        <td style="text-align: center;">
                            {{ \Carbon\Carbon::parse($detalle->fecha_vencimiento)->format('d/m/Y') }}
                        </td>
                        <td>{{ $reingreso->motivo_reingreso }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" style="text-align: center;">No hay materiales disponibles</td>
                </tr>
            @endif

        </tbody>
    </table>
    <table class="firmas-container">
        <tr>
            <td>PERSONAL RESPONSABLE<br>Firma y sello</td>
            <td>Vo. Bo. JEFE DE UNIDAD <br>Firma y sello</td>
            <td>Vo. Bo. ENCARGADO BIENES Y SERVICIOS<br>Firma y sello</td>
        </tr>
    </table>
</body>

</html>
