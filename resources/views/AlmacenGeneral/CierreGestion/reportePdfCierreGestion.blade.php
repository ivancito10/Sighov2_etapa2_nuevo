<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE CIERRE GESTION ALMACEN GENERAL</title>
    <style>
        header {
            margin: 0 30px 0 30px;
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
            font-size: 10px;
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
            padding: 4px 2px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }

        .items td {
            padding: 3px 2px;
            border: 1px solid #e5e7eb;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
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
            margin: 0 auto;
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
            height: 25mm;
            padding-left: 15px;
            padding-right: 20px;
        }
    </style>
</head>

<body>
    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fechaActual }} <br>
                    <b>Usuario: </b> {{ $nombreUsuario }}<br>
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
                <td class="text-center">
                    <img src="images/logo_fondo_blanco.png" width="140" height="90" alt="Logo SSU"
                        style="opacity: 0.7;">
                </td>
            </tr>
        </table>
    </footer>

    <div>
        <table style="width: 100%;">
            <tr>
                <td class="vertical-top" style="width: 10%;">
                    <img src="logo/ssu.png" width="100" height="75" alt="Logo" class="logo">
                </td>

                <td class="vertical-top" style="width: 25%; font-size: 16px;">
                    <b>
                        <span style="color: #1a5276;">CIERRE DE GESTIÓN</span><br>
                        <span style="color: #a93226;"><u>ALMACEN GENERAL</u></span>
                    </b>
                </td>
                <td style="width: 3%;"></td>
                <td class="vertical-top" style="width: 62%;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="vertical-top" style="width: 35%; color:#3a5260;"><strong>USUARIO:</strong></td>
                            <td style="width: 65%;" class="courier vertical-top">{{ $nombreUsuario }}</td>
                        </tr>
                        <tr>
                            <td class="vertical-top" style="color:#3a5260;"><strong>CÉDULA DE IDENTIDAD:</strong></td>
                            <td class="courier vertical-top">{{ $ci }}</td>
                        </tr>
                        <tr>
                            <td class="vertical-top" style="color:#3a5260;"><strong>FECHA:</strong></td>
                            <td class="courier vertical-top">{{ $fechaActual }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <hr style="border: 0; height: 1px; background-color: #54504E">

    <h3 class="text-center" style="color:#3a5260; margin: 5px 0;"><u>REPORTE DE CIERRE DE GESTIÓN DE MATERIALES</u></h3>

    <table class="items" style="width: 100%;">
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">N°</th>
                <th rowspan="2" style="width: 9%;">FECHA REGISTRO</th>
                <th rowspan="2" style="width: 10%;">CÓDIGO MATERIAL</th>
                <th rowspan="2" style="width: 25%;">DESCRIPCIÓN DE MATERIAL</th>
                <th rowspan="2" style="width: 9%;">FECHA VENC.</th>
                <th rowspan="2" style="width: 8%;">UNIDAD</th>
                <th colspan="3" style="width: 27%;">CANTIDAD</th>
                <th rowspan="2" style="width: 9%;">FECHA VENC. BAJA</th>
            </tr>
            <tr>
                <th style="width: 9%;">FÍSICA</th>
                <th style="width: 9%;">SISTEMA</th>
                <th style="width: 9%;">BAJAS</th>
            </tr>
        </thead>
        <tbody style="text-align:center;">
            @forelse ($cierreGestion as $index => $cierre)
                <tr>
                    <td style="font-weight: bold;">{{ $index + 1 }}</td>
                    <td>{{ $cierre->fecha_registro ? \Carbon\Carbon::parse($cierre->fecha_registro)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $cierre->materialCierreGestion->codigo ?? '-' }}</td>
                    <td class="text-left">{{ $cierre->materialCierreGestion->nombre_material ?? '-' }}</td>
                    <td>{{ $cierre->fecha_vencimiento ? \Carbon\Carbon::parse($cierre->fecha_vencimiento)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $cierre->materialCierreGestion->unidad ?? '-' }}</td>
                    <td>{{ number_format($cierre->cantidad_fisica ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($cierre->cnatidad_sistema ?? 0, 0, ',', '.') }}</td>
                    <td>{{ number_format($cierre->cantidad_bajas ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $cierre->fecha_vencimiento_baja ? \Carbon\Carbon::parse($cierre->fecha_vencimiento_baja)->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 10px;">
                        No existen registros de cierre de gestión para Almacén General.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
