<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INGRESO DE MATERIALES</title>
    <style>
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
            padding-left: 15px;
            padding-right: 15px;
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
                <td class="vertical-top" style="width: 20%;"><img src="logo/ssu.png" width="100" height="70"
                        alt="Logo" class="logo">
                </td>

                <td class="vertical-top text-center" style="width: 60%; font-size: 16px;"><b><span
                            style="color:  #1a5276;">ACTA DE
                            CONFORMIDAD Y</span><br><span style="color: #a93226;"><u>RECEPCIÓN DEFINITIVA</u></span></b>
                </td>
                <td style="width: 10%;">
                    <strong> CODIGO: </strong><br>
                    {{ $ingreso->codigo_ingreso }}
                </td>

                <td class="vertical-top text-right" style="width: 10%;"><img src="{{ $imagenQr }}" height="75"
                        width="75" alt="QR SOL"></td>
            </tr>
        </table>
    </div>

    <table class="table table-bordered" style="width: 100%;text-align:justify; font-size: 10px;">
        <tr>
            <td colspan="12">
                En la ciudad de La Paz, en instalaciones del Seguro Social Universitario de La Paz se procedió a la
                recepción de bienes/servicios de acuerdo a las siguientes características.
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>RESOLUCION / MEMORANDUM Nº</strong><br>
                Documento de designación a la Comisión de Recepción
            </td>
            <td colspan="3">
                {{ $ingreso->nro_resolucion_memorandum }}
            </td>
            <td colspan="4">
                <strong>DESIGNACIÓN DEL RPA:</strong><br>
                Indicar en Nº de Orden de Compra/Servicio
            </td>
            <td colspan="3">

            </td>
        </tr>
        <tr>
            <td class="bold-text">OBJETO DE LA RECEPCIÓN:</td>
            <td colspan="11">{{ $ingreso->objeto_recepcion }}</td>
        </tr>

        <tr>
            <!-- Primera columna que abarca dos filas -->
            <td rowspan="2" style="width: 25%;"><strong>ÁREA DE RECEPCIÓN Y MODALIDAD DE
                    CONTRATACIÓN:</strong><br> (Marcar con una "x")
            </td>
            <td colspan="6" rowspan="2" style="width: 40%;">
                SECCIÓN DE ALMACEN GENERAL
            </td>
            <td colspan="5" style="width: 30%;">
                MODALIDAD DE ADQUISICION
            </td>
        </tr>

        <tr>
            <!-- Segunda fila sin la primera columna -->
            <td colspan="5">{{ $tipoProceso }}</td>
        </tr>

        <tr>
            <!-- Primera columna que abarca dos filas -->
            <td rowspan="2" class="bold-text" style="width: 25%;">FECHA Y NÚMERO DE CONTRATO U ORDEN
                DE COMPRA/SERVICIO</td>
            <td class="bold-text" style="width: 5%;">DÍA</td>
            <td class="bold-text" style="width: 5%;">MES</td>
            <td class="bold-text" style="width: 5%;">AÑO</td>
            <td class="bold-text" style="width: 10%;">N° ADQUISICIÓN</td>
            <td colspan="2" style="width: 15%;"><strong>CUCE</strong> (Si corresponde):</td>
            <td colspan="5" style="width: 35%;">{{ $codigoCuce }}</td>
        </tr>
        <tr>
            <!-- Segunda fila sin la primera columna -->
            <td style="width: 5%;">{{ $fechaContrato ? $fechaContrato->format('d') : '' }}</td>
            <td style="width: 5%;">{{ $fechaContrato ? $fechaContrato->format('m') : '' }}</td>
            <td style="width: 5%;">{{ $fechaContrato ? $fechaContrato->format('Y') : '' }}</td>
            <td style="width: 10%;">{{ $codIngreso }}</td>
            <td colspan="2" class="bold-text" style="width: 10%;">EMPRESA ADJUDICADA</td>
            <td colspan="5">{{ $proveedor }}</td>
        </tr>

        <tr>
            <!-- Primera columna que abarca dos filas -->
            <td rowspan="2" class="bold-text" style="width: 25%;">FECHA Y HORA DE LA RECEPCIÓN</td>
            <td class="bold-text" style="width: 5%;">DÍA</td>
            <td class="bold-text" style="width: 5%;">MES</td>
            <td class="bold-text" style="width: 5%;">AÑO</td>
            <td class="bold-text" style="width: 10%;">HORA</td>
            <td colspan="2" class="bold-text" style="width: 15%;">N° DE FACTURA</td>

            <td colspan="5">
                {{ $facturas->where('nota_remision', false)->pluck('nro_factura')->filter()->implode(' - ') }}
            </td>

        </tr>
        <tr>
            <!-- Segunda fila sin la primera columna -->
            <td>{{ $fechaEntrega ? $fechaEntrega->format('d') : '' }}</td>
            <td>{{ $fechaEntrega ? $fechaEntrega->format('m') : '' }}</td>
            <td>{{ $fechaEntrega ? $fechaEntrega->format('Y') : '' }}</td>
            <td>{{ $fechaEntrega ? $horaEntrega->format('H:i') : '' }}
            </td>
            <td colspan="4" class="bold-text">N° DE NOTA DE REMISIÓN O ENTREGA</td>
            <td colspan="3">
                {{ $facturas->where('nota_remision', true)->pluck('nro_factura')->filter()->implode(' - ') }}
            </td>
        </tr>
        <tr>
            <td colspan="12" class="bold-text" style="text-align:justify;">RESPONSABLE O COMISIÓN DE RECEPCIÓN</td>
        </tr>
        <tr>
            <td colspan="12" style="text-align:justify; height:10px;">{!! $responsables !!}</td>
        </tr>
    </table>
    <br>
    <h4 style="text-align:left; margin: 0; color:#3a5260;">DESCRIPCIÓN DE BIENES A SER RECEPCIONADOS</h4>

    <table class="table table-bordered" style="width: 100%; font-size: 10px;">
        <thead style="background-color:#f2f2f2;">
            <tr>
                <th>N°</th>
                <th>CÓDIGO</th>
                <th>MATERIAL / SUMINISTRO</th>
                <th>UNIDAD DE MEDIDA</th>
                <th>DESCRIPCIÓN</th>
                <th>CANTIDAD</th>
                <th>PRECIO UNITARIO EN BS.</th>
                <th>PRECIO TOTAL EN BS.</th>
                <th>PAÍS DE ORIGEN O MARCA</th>
            </tr>
        </thead>
        <tbody style="text-align:center;">
            @if ($materialesIngreso)
                @foreach ($materialesIngreso as $index => $material)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        <td>{{ $material->materialIngresoMaterial->codigo }}</td>
                        <td class="text-left">{{ $material->materialIngresoMaterial->nombre_material }}</td>
                        <td class="text-left">
                            {{ $material->materialIngresoMaterial->descripcion }}</td>
                        <td>{{ $material->materialIngresoMaterial->unidad }}</td>
                        <td>{{ $material->pais_marca }}</td>
                        <td>{{ number_format($material->cantidad ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($material->precio_unitario ?? 0, 2, ',', '.') }}
                        </td>
                        <td class="text-right">{{ number_format($material->precio_total ?? 0, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center">No hay materiales registrados.</td>
                </tr>
            @endif
            <tr>
                <td colspan="3" class="bold-text text-right">TOTAL (Literal): </td>
                <td colspan="5" class="bold-text">{{ $numeroEnteroEnLetras }} {{ $parteDecimal }}/100
                    BOLIVIANOS.
                </td>
                <td class="bold-text text-right">{{ number_format($montoTotal ?? 0, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table
        style="border: 1px solid black; width: 100%; font-size: 10px; text-align:center; border-collapse: collapse;">
        <tr>
            <td colspan="5" style="border-bottom: none; border-bottom: none; text-align:justify;">De acuerdo a la
                solicitud, los bienes
                entregados por el proveedor <strong>cumplen</strong> con: (marcar
                con una "x")</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td style="border-bottom: none; text-align:justify;">Observaciones (Si no es llenado se
                asume
                como que no hay observaciones):</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:left;">Tiempo de entrega:</td>
            <td style="border-style: double; width: 50px;">
                {{ $ingreso->proveedorCumple?->tiempo_entrega ? 'X' : '' }}
            </td>
            <td style="width: 20px;"></td>
            <td style="border: 1px solid black; width: 400px; text-align:left;">
                {{ $ingreso->proveedorCumple->tiempo_entrega_obs }}
            </td>

        </tr>
        <tr>
            <td colspan="2" style="text-align:left;">Cantidad según pedido:</td>
            <td style="border-style: double;">
                {{ $ingreso->proveedorCumple?->cantidad_pedido ? 'X' : '' }}
            </td>
            <td></td>
            <td style="border: 1px solid black; text-align:left;">{{ $ingreso->proveedorCumple->cantidad_pedido_obs }}
            </td>
        </tr> 
        <tr>
            <td colspan="2" style="text-align:left;">Cantidad según lo solicitado:</td>
            <td style="border-style: double;">
                {{ $ingreso->proveedorCumple?->calidad_solicitada ? 'X' : '' }}
            </td>
            <td></td> 
            <td style="border: 1px solid black; text-align:left;">
                {{ $ingreso->proveedorCumple->cantidad_solicitada_obs }}</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:left;">Esp. Técnicas/Términos de referencia:</td>
            <td style="border-style: double;">
                {{ $ingreso->proveedorCumple?->terminos_referencia ? 'X' : '' }}
            </td>
            <td></td>
            <td style="border: 1px solid black; text-align:left;">
                {{ $ingreso->proveedorCumple->terminos_referencia_obs }}</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:left;">Capacitación:</td>
            <td style="border-style: double;">
                {{ $ingreso->proveedorCumple?->capacitacion ? 'X' : '' }}
            </td>
            <td></td>
            <td style="border: 1px solid black; text-align:left;">{{ $ingreso->proveedorCumple->capacitacion_obs }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:left;">Garantías:</td>
            <td style="border-style: double;">
                {{ $ingreso->proveedorCumple?->garantias ? 'X' : '' }}
            </td>
            <td></td>
            <td style="border: 1px solid black; text-align:left;">{{ $ingreso->proveedorCumple->garantias_obs }}</td>
        </tr>
        <tr>
            <td style=" border: 1px solid black; width: 15%; text-align:left;" class="bold-text" colspan="1">Otros:</td>
            <td colspan="4" style="border: 1px solid black;">
                {{ $ingreso->proveedorCumple?->otras_observaciones ? $ingreso->proveedorCumple->otras_observaciones : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:justify;">No teniendo observaciones y cumplida las condiciones
                descritas
                de acuerdo con lo
                adjudicado se da
                conformidad de lo recepcionado y se realiza la recepción definitiva por lo que se firma al pie del
                documento. Así mismo se solicita el pago respectivo.</td>
        </tr>
        <tr style="height: 50px;" class="bold-text">
            <td colspan="5" style="border: 1px solid black;">FIRMAS Y SELLOS DEL
                RESPONSABLE O COMISIÓN DE RECEPCIÓN - Recibí(mos) Conforme</td>
        </tr>
        <tr>
            <td colspan="5" style="height: 150px;"></td>
        </tr>
        <tr>
            <td colspan="5" class="bold-text" style="border: 1px solid black;">EMPRESA
                ADJUDICADA - Entregué conforme</td>
        </tr>
        <tr>
            <!-- Primera fila de la primera columna -->
            <td colspan="4" style="border: 1px solid black; height: 40px; text-align:justify;"><strong> Nombres y
                    Apellidos:</strong></td>
            <!-- Segunda columna que abarca dos filas -->
            <td rowspan="2" style="border: 1px solid black; vertical-align: bottom;"><strong>Firma y/o
                    Sello</strong></td>
        </tr>
        <tr>
            <!-- Segunda fila de la primera columna -->
            <td colspan="4" style="border: 1px solid black; height: 40px; text-align:justify;"><strong>Cédula de
                    Identidad:</strong></td>
        </tr>
    </table>
</body>

</html>
