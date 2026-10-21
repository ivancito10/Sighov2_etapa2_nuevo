<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>RECETA MÉDICA</title>
    <style>
        @page {
            margin-top: 19px;
            margin-right: 35px;
            margin-bottom: 10px;
            margin-left: 35px;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .document-container {
            /* Mantenemos el padding lateral, pero nos aseguramos de no tener padding-top que genere espacio */
            padding-left: 0.7mm;
            /* Ajusta este valor para el margen izquierdo general del contenido */
            padding-right: 0.7mm;
            /* Ajusta este valor para el margen derecho general del contenido */
            /* Asegura que el contenedor no añada espacio abajo */
            background-color: white;
            /* border-radius: 5px; /* Comentado, si no necesitas bordes redondeados en el contenedor */
            position: relative;
            box-sizing: border-box;
            /* Para que el padding no aumente el ancho total */
            margin: 0;
            padding: 0;
        }

        /* Estilos para el texto de las notas al pie */
        .notas {
            text-align: right;
            padding: 0px 0;
            /* Menos padding vertical para compactar */
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin-top: 1px;
            /* Pequeño espacio para separarlo de la tabla anterior */
            margin-bottom: 25px;
            /* Espacio antes de las firmas, ajusta este valor si necesitas más/menos */
        }

        /* Estilos generales para TODAS las tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            /* Colapsa los bordes de las celdas */
            margin-bottom: 0px;
            /* Por defecto, las tablas no tendrán margen inferior, se controlará individualmente */
            font-family: Arial, sans-serif;
            font-size: 12px;
            border: none;
            /* Quitamos el borde solid que afectaba a todas las tablas por defecto */
        }

        /* Estilos para las celdas del encabezado (<th>) de las tablas de datos */
        th {
            background-color: #f2f2f2;
            border: 1px solid #383a3b;
            /* Ajustado a tu color de borde deseado */
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        /* Estilos para las celdas de datos (<td>) */
        td {
            border: 1px solid #383a3b;
            /* Ajustado a tu color de borde deseado */
            padding: 3px;
            text-align: left;
        }

        /* Estilos para el caption */
        caption {
            font-size: 0.7em;
            font-family: Arial, sans-serif;
            font-weight: bold;
            margin-bottom: 9px;
            text-align: left;
            text-decoration: underline;
        }

        /* -------------------------------------------------------- */
        /* ESTILOS ESPECÍFICOS PARA LA TABLA DEL ENCABEZADO (LOGO Y RECETA) */
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
            width: 32%;
            padding-left: 5px;
            /* Mantenemos este padding para separar del logo */
            font-size: 14px;
        }

        .header-table .third-col {
            width: 60%;
            text-align: right;
            padding-right: 0;
        }

        /* Ajustes para los párrafos dentro de la segunda columna del encabezado */
        .header-table .second-col p {
            margin: 0;
            /* Elimina los márgenes predeterminados de los <p> */
            line-height: 1.2;
            /* Ajusta el espacio entre las líneas de texto */
        }

        /* Ajustes para el span dentro de la tercera columna del encabezado */
        .header-table .third-col span {
            font-size: 12px;
            line-height: 1.3;
            display: block;
            /* Para que cada línea de texto ocupe su propio espacio */
        }

        /* -------------------------------------------------------- */
        /* ESTILOS ESPECÍFICOS PARA LA TABLA DE INFORMACIÓN (MATRICULA, ASEGURADO, ETC.) */
        .info-table {
            margin-top: 0px;
            /* Elimina el margen superior */
            margin-bottom: 2px;
            /* Espacio para separar de la siguiente tabla de datos */
            border: none;
            /* Quitamos el borde sólido de esta tabla */
            font-size: 12px;
        }

        .info-table td {
            border: none !important;
            /* Aseguramos que las celdas no tengan bordes */
            vertical-align: top;
            /* Alinea el texto en la parte superior si la celda es más alta */
        }

        /* -------------------------------------------------------- */
        /* ESTILOS PARA LA TERCERA TABLA (LISTADO DE PRODUCTOS) */
        .data-table-products {
            border: 1px solid #000;
            /* Borde exterior de la tabla */
            font-size: 10px;
            margin-bottom: 2px;
            /* Espacio entre esta tabla y la nota */
        }

        .data-table-products th,
        .data-table-products td {
            border: 1px solid #383a3b;
            /* Bordes internos para las celdas de esta tabla */
        }

        /* Estilos para filas pares (opcional, para mejor legibilidad) */
        .data-table-products tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Estilo al pasar el ratón por encima de una fila (opcional) */
        .data-table-products tr:hover {
            background-color: #f1f1f1;
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
    @foreach ($recetas_data as $receta)
        <div class="document-container">
            <div style="text-align:center;">
                <span
                    style=" text-decoration:underline; font-family: Arial, sans-serif; font-size:15px; font-weight:bold;">
                    HOSPITAL DEL SEGURO SOCIAL UNIVERSITARIO LA PAZ
                </span>
            </div>

            <table class="header-table">
                <thead>
                    <tr>
                        <td class="first-col">
                            <img src="logo/ssu.png" width="90px" height="65px" alt=" ">
                        </td>
                        <td class="second-col">
                            <p><b style="color:  #1a5276;">RECETA MÉDICA </b> <b
                                    style="text-decoration: underline; color: #a93226; letter-spacing: 1px;">FARMACIA</b>
                            </p>

                        </td>
                        <td class="third-col">
                            <span style="font-size:18px;"><b style="background-color: yellow">N°
                                    {{ $receta['nro_receta'] }} /
                                    {{ \Carbon\Carbon::parse($receta['created_at'])->format('Y') }} </b></span><br>
                            <span>
                                <b>Especialidad: </b> {{ $receta['especialidad'] }}<br>
                                <b>Médico:</b> {{ $receta['nombres_especialista'] }}<br>
                                <b>Tipo Atención:</b> {{ $receta['nombre_consulta'] }}
                            </span>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="document-container" style="margin-top:4px;">
            <table class="info-table">
                <thead>
                    <tr>
                        <td style="width: 55%;"><b>Matrícula: </b> {{ $receta['matricula'] }}
                            {{ $receta['codigo_institucional'] }} </td>
                        <td style="width: 45%;"><b>Paciente: </b> {{ $receta['nombres'] }} </td>
                    </tr>
                    <tr>
                        <td style="width: 55%;"><b>Empleador: </b> {{ $receta['institucion'] }}</td>
                        <td style="width: 45%;"><b>Tipo Paciente: </b>
                            @if ($receta['tipo_asegurado'] == 'TITULAR')
                                ASEGURADO
                            @else
                                {{ $receta['tipo_asegurado'] }}
                            @endif
                        </td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="document-container">
            <table class="data-table-products">
                <thead>
                    <tr>
                        <th style="width: 5%;">Código</th>
                        <th style="width: 18%;">Producto</th>
                        <th style="width: 13%;">Concentración</th>
                        <th style="width: 15%;">Forma Farm.</th>
                        @if ($receta['manual'] == false)
                            @if (
                                $receta['nombre_consulta'] != 'QUIROFANO' &&
                                    $receta['nombre_consulta'] != 'TERAPIA INTENSIVA' &&
                                    $receta['nombre_consulta'] != 'HOSPITALIZACION')
                                <th style="width: 10%;">Dosificación</th>
                            @endif

                            <th style="width: 15%;">Forma Adm.</th>
                        @endif
                        <th style="width: 5%;">Cant.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receta['datos'] as $dato)
                        <tr>
                            <td style="text-align:center;">
                                {{ $dato->medicamentoRecetaMedicamento->codigo_medicamento }}</td>
                            <td>{{ $dato->medicamentoRecetaMedicamento->nombre }}</td>
                            <td>{{ $dato->medicamentoRecetaMedicamento->concentracion }}</td>
                            <td>{{ $dato->medicamentoRecetaMedicamento->forma_farmaceutica }}</td>
                            @if ($receta['manual'] == false)
                                @if (
                                    $receta['nombre_consulta'] != 'QUIROFANO' &&
                                        $receta['nombre_consulta'] != 'TERAPIA INTENSIVA' &&
                                        $receta['nombre_consulta'] != 'HOSPITALIZACION')
                                    <td>{{ $dato->indicaciones }}</td>
                                @endif

                                <td>{{ $dato->forma_adm }}</td>
                            @endif

                            <td style="text-align:center;">{{ $dato->cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @if (
                    $receta['nombre_consulta'] != 'QUIROFANO' &&
                        $receta['nombre_consulta'] != 'TERAPIA INTENSIVA' &&
                        $receta['nombre_consulta'] != 'HOSPITALIZACION')
                    @if ($receta['manual'] == false)
                        <tfoot>
                            <tr>
                                <td colspan="7"><em><b>Indicaciones: </b></em></td>
                            </tr>
                            @foreach ($receta['datos'] as $dato)
                                <tr>
                                    <td>{{ $dato->medicamentoRecetaMedicamento->codigo_medicamento }}</td>
                                    <td colspan="6">{{ $dato->recomendacion }}</td>
                                </tr>
                            @endforeach

                            {{-- <tr>
                            <td colspan="7"><em><b> Diagnóstico descriptivo: </b></em> {{ $descriptivo }}</td>
                        </tr> --}}
                        </tfoot>
                    @endif
                @endif


                @if ($receta['manual'] == true)
                    @if ($receta['nombre_consulta'] == 'QUIROFANO' || $receta['nombre_consulta'] == 'TERAPIA INTENSIVA')
                        <tfoot>
                            <tr>
                                <td colspan="5"><em><b> Observaciones Quirófano: </b></em> {!! $receta['observacion_quirofano'] !!}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                @endif
            </table>
        </div>

        <table style="font-family: Arial, sans-serif; border-collapse: collapse;">
            <thead>
                <tr>
                    <td style="width:30%; font-size:10px; padding: 0; border: 0; vertical-align: top;">
                        <b>Fecha Prescripción:</b>
                        {{ \Carbon\Carbon::parse($receta['fecha_prescripcion'])->format('d/m/Y') }}
                        <br>
                        @if ($receta['fecha_proxima_consulta'] != null)
                            <span style="background-color: yellow; padding: 1px;">
                                <b>Fecha proxima consulta:</b> {{ $receta['fecha_proxima_consulta'] }}
                            </span>
                        @endif
                        <br>
                        @if ($receta['manual'] == true)
                            <b>Prescrito por: </b> {{ $receta['prescrito_por'] ?? 'Sin prescribir' }}
                        @endif
                        <br>
                    </td>

                    <td
                        style="width: 40%;padding: 0; border: 0;  text-align:center; font-size:10px; vertical-align:top;">
                        @php
                            $fecha_caducidad = \Carbon\Carbon::parse($receta['fecha_caducidad']);
                        @endphp


                        @if ($receta['manual'] == false)
                            <em style="font-size: 13px">Nota: La Receta tiene validez de <b>48 </b> horas. </em>
                            <br>
                            Válido hasta:
                            <b>
                                <em style="font-size: 13px">{{ $fecha_caducidad->format('d/m/Y') }} </em>

                            </b>
                            a las
                            <em><b>
                                    {{ $fecha_caducidad->format('H:i') }}
                                </b></em>
                        @else
                            Nro. Receta Manual : {{ $receta['nro_receta'] }}
                        @endif

                    </td>
                    <td
                        style="width: 15%;padding: 0; border: 0;  text-align:right; font-size:10px; vertical-align:top;">
                        <img src="images/sighoV2_transparente_calidad.png" width="60" height="16"
                            style="display: block; margin-left: auto; margin-right: 0;">
                    </td>
                    <td style="width:15%; padding: 0; border: 0; text-align:right;">
                        <img src="data:image/png;base64,{{ $receta['qrCode'] }}" alt="Código QR" width="60px"
                            height="60px" class="qr" style="display:block; margin:0; padding:0;" />
                    </td>
                </tr>
            </thead>
        </table>

        <br>

        <table class="signatures-table">
            <tr>
                <td>
                    Firma/Sello Médico
                </td>
                <td>
                    <span style="font-size:11px;">
                        FIRMA PACIENTE<br> Nombre: ................................................... <br>
                        C.I.: ......................................................
                    </span>
                </td>
                <td>
                    Firma/Sello Farmacéutica
                </td>
            </tr>
        </table>

        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach



    {{-- Documento pdf copia para el paciente 
    <div style="page-break-after: always;"></div>

    <div class="document-container">
        <div style="text-align:center;">
            <span style=" text-decoration:underline; font-family: Arial, sans-serif; font-size:15px; font-weight:bold;">
                HOSPITAL DEL SEGURO SOCIAL UNIVERSITARIO LA PAZ
            </span>
        </div>

        <table class="header-table">
            <thead>
                <tr>
                    <td class="first-col">
                        <img src="logo/ssu.png" width="90px" height="65px" alt=" ">
                    </td>
                    <td class="second-col">
                        <p><b style="color:  #1a5276;">RECETA MÉDICA </b> <b
                                style="text-decoration: underline; color: #a93226; letter-spacing: 1px;">FARMACIA</b>
                        </p>

                    </td>
                    <td class="third-col">
                        <span style="font-size:18px;"><b style="background-color: yellow">COPIA PACIENTE
                            </b></span><br>
                        <span>
                            <b>Especialidad: </b> {{ $recetas_data[0]['especialidad'] }}<br>
                            <b>Médico:</b> {{ $recetas_data[0]['nombres_especialista'] }}<br>
                            <b>Tipo Atención:</b> {{ $recetas_data[0]['nombre_consulta'] }}
                        </span>
                    </td>
                </tr>
            </thead>
        </table>
    </div>

    <div class="document-container" style="margin-top:4px;">
        <table class="info-table">
            <thead>
                <tr>
                    <td style="width: 55%;"><b>Matrícula: </b> {{ $recetas_data[0]['matricula'] }}
                        {{ $recetas_data[0]['codigo_institucional'] }} </td>
                    <td style="width: 45%;"><b>Paciente: </b> {{ $recetas_data[0]['nombres'] }} </td>
                </tr>
                <tr>
                    <td style="width: 55%;"><b>Empleador: </b> {{ $recetas_data[0]['institucion'] }}</td>
                    <td style="width: 45%;"><b>Tipo Paciente: </b>
                        @if ($recetas_data[0]['tipo_asegurado'] == 'TITULAR')
                            ASEGURADO
                        @else
                            {{ $recetas_data[0]['tipo_asegurado'] }}
                        @endif
                    </td>
                </tr>
            </thead>
        </table>
    </div>

    <div class="document-container">
        <table class="data-table-products">
            <thead>
                <tr>
                    <th style="width: 5%;">Código</th>
                    <th style="width: 18%;">Producto</th>
                    <th style="width: 13%;">Concentración</th>
                    <th style="width: 15%;">Forma Farm.</th>
                    @if ($recetas_data[0]['manual'] == false)
                        <th style="width: 10%;">Dosificación</th>
                        <th style="width: 15%;">Forma Adm.</th>
                    @endif
                    <th style="width: 5%;">Cant.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recetas_data as $receta)
                    @foreach ($receta['datos'] as $dato)
                        <tr>
                            <td style="text-align:center;">
                                {{ $dato->medicamentoRecetaMedicamento->codigo_medicamento }}</td>
                            <td>{{ $dato->medicamentoRecetaMedicamento->nombre }}</td>
                            <td>{{ $dato->medicamentoRecetaMedicamento->concentracion }}</td>
                            <td>{{ $dato->medicamentoRecetaMedicamento->forma_farmaceutica }}</td>
                            @if ($receta['manual'] == false)
                                <td>{{ $dato->indicaciones }}</td>
                                <td>{{ $dato->forma_adm }}</td>
                            @endif

                            <td style="text-align:center;">{{ $dato->cantidad }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>

            @if ($recetas_data[0]['manual'] == false)
                <tr>
                    <td colspan="7"><em><b>Indicaciones: </b></em></td>
                </tr>
                @foreach ($recetas_data as $receta)
                    @foreach ($receta['datos'] as $dato)
                        @if ($dato->recomendacion != null)
                            <tr>
                                <td colspan="7">{{ $dato->medicamentoRecetaMedicamento->codigo_medicamento }} -
                                    {{ $dato->recomendacion }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach

                 <tr>
                        <td colspan="7"><em><b> Diagnóstico descriptivo: </b></em> {{ $descriptivo }}</td>
                    </tr> 
            @endif

            @if ($recetas_data[0]['manual'] == true)
                @if ($recetas_data[0]['nombre_consulta'] == 'QUIROFANO')
                    <tfoot>
                        <tr>
                            <td colspan="7"><em><b> Observaciones Quirófano: </b></em> {!! $recetas_data[0]['observacion_quirofano'] !!}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            @endif


        </table>
    </div>

    <table style="font-family: Arial, sans-serif; border-collapse: collapse;">
        <thead>
            <tr>
                <td style="width:30%; font-size:10px; padding: 0; border: 0; vertical-align: top;">
                    <b>Fecha Prescripción:</b>
                    {{ \Carbon\Carbon::parse($recetas_data[0]['fecha_prescripcion'])->format('d/m/Y') }}
                    <br>
                    @if ($fechaProximaConsulta != null)
                        <span style="background-color: yellow; padding: 1px;">
                            <b>Fecha proxima consulta:</b> {{ $fechaProximaConsulta }}
                        </span>
                    @endif
                    <br>
                    @if ($recetas_data[0]['manual'] == true)
                        <b>Prescrito por: </b> {{ $recetas_data[0]['prescrito_por'] ?? 'Sin prescribir' }}
                    @endif
                    <br>

                </td>

                <td style="width: 40%;padding: 0; border: 0;  text-align:center; font-size:10px; vertical-align:top;">
                    @php

                        $fecha_caducidad = \Carbon\Carbon::parse($recetas_data[0]['fecha_caducidad']);
                    @endphp


                    @if ($recetas_data[0]['manual'] == false)
                        <em style="font-size: 13px">Nota: La Receta tiene validez de <b>48 </b> horas. </em>
                        <br>
                        Válido hasta:
                        <b>
                            <em style="font-size: 13px">{{ $fecha_caducidad->format('d/m/Y') }} </em>
                        </b>
                        a las
                        <em><b>
                                {{ $fecha_caducidad->format('H:i') }}
                            </b></em>
                    @else
                        Nro. Receta Manual : {{ $recetas_data[0]['nro_receta'] }}
                    @endif

                </td>
                <td style="width: 15%;padding: 0; border: 0;  text-align:right; font-size:10px; vertical-align:top;">
                    <img src="images/sighoV2_transparente_calidad.png" width="60" height="16"
                        style="display: block; margin-left: auto; margin-right: 0;">
                </td>
                {{-- <td style="width:15%; padding: 0; border: 0; text-align:right;">
                    <img src="data:image/png;base64,{{ $recetas_data[0]['qrCode'] }}" alt="Código QR" width="60px"
                        height="60px" class="qr" style="display:block; margin:0; padding:0;" />
                </td> 
            </tr>
        </thead>
    </table>




    <br>

    <table class="signatures-table">
        <tr>
            <td>
                Firma/Sello Médico
            </td>
            <td>
                <span style="font-size:11px;">
                    FIRMA PACIENTE<br> Nombre: ................................................... <br>
                    C.I.: ......................................................
                </span>
            </td>
            <td>
                Firma/Sello Farmacéutica
            </td>
        </tr>
    </table> --}}



</body>

</html>
