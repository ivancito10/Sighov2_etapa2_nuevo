<!DOCTYPE html>
<html>

</html>

<head>
    <meta charset="UTF-8">
    <title>ORDEN LABORATORIO</title>
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
            width: 40%;
            padding-left: 5px;
            /* Mantenemos este padding para separar del logo */
            font-size: 14px;
        }

        .header-table .third-col {
            width: 52%;
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

        .data-table-products,
        .info-table {
            border-collapse: collapse;
            /* Elimina el espacio entre los bordes de las celdas */
            border-spacing: 0;
            /* Asegura que no haya espacio entre las celdas */
            margin-bottom: 0px;
        }

        .info-table thead {
            margin: 0;
            padding: 0;
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
            font-size: 12px;
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
            margin-bottom: 5px;
            /* Espacio después de las firmas */
            border: none;
            /* Asegura que la tabla no tenga bordes */
        }

        .signatures-table td {
            border: none !important;
            /* Asegura que las celdas no tengan bordes */
            padding-top: 1px;
            /* Espacio para la línea de firma y el texto "Firma..." */
            text-align: center;
            /* Centra el texto "Firma Médico" y "Firma Paciente" */
            vertical-align: bottom;
            /* Alinea el texto en la parte inferior de la celda */
            font-size: 12px;
            /* Tamaño de fuente para las etiquetas de firma */
            position: relative;
            /* Necesario para posicionar la línea horizontal */
        }

        /* Línea horizontal para la firma */
        .signatures-table td::before {
            content: '';
            position: absolute;
            bottom: 25px;
            /* Aumenta este valor para subir la línea de firma. Prueba con 25px o más. */
            left: 15%;
            /* Inicia la línea más hacia la izquierda dentro de la celda */
            right: 15%;
            /* Termina la línea más hacia la derecha dentro de la celda */
            border-bottom: 1px solid #000;
            /* Estilo de la línea */
            width: 70%;
            /* Ancho de la línea (100 - left - right) */
            margin: 0 auto;
            /* Centra la línea dentro del espacio disponible */
        }
    </style>

</head>

<body>
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
                        <img src="logo/ssu.png" width="90px" height="65px" alt="Logo">
                    </td>
                    <td class="second-col">
                        <p style="color: #1a5276;"><b> ORDEN DE LABORATORIO </b></p>
                        <p style="text-decoration: underline; color:#a93226; letter-spacing: 1px;"> <b>
                                {{ $tipo_laboratorio }} </b> </p>
                    </td>
                    <td class="third-col">

                        <span style="font-size:18px;"><b>N° </b> {{ $solicitudes->id_solicitud_s1 }} /
                            {{ \Carbon\Carbon::parse($solicitudes->created_at)->format('Y') }} </span>
                        <span>
                            <b>Especialidad: </b> {{ $especialidad }} <br>
                            <b>Médico:</b> {{ $nombre_especialista }} <br>
                            <b>Tipo Atención:</b> {{ $historia_clinica->historiaClinicaTipoConsulta->nombre }}
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
                    <td style="width: 10%;"><b>Matrícula: </b> {{ $datos_persona['matricula_seguro'] ?? '' }}
                        {{ $codigo_institucional }} </td>
                    <td style="width: 90%;"><b>Paciente: </b> {{ $datos_persona['p_apellido'] ?? '' }}
                        {{ $datos_persona['s_apellido'] ?? '' }} {{ $datos_persona['nombres'] ?? '' }} </td>
                </tr>
                <tr>
                    <td style="width: 50%;"><b>Empleador: </b> {{ $datos_persona['institucion'] ?? '' }} </td>
                    <td style="width: 50%;"><b>Tipo Paciente: </b>
                        @if (($datos_persona['tipo_asegurado'] ?? null) == 'TITULAR')
                            ASEGURADO
                        @else
                            {{ $datos_persona['tipo_asegurado'] ?? '' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%;"><b>Fecha Emisión: </b>
                        {{ \Carbon\Carbon::parse($solicitudes->created_at)->format('d/m/Y') }} </td>
                </tr>
            </thead>
        </table>
    </div>

    <div class="document-container">
        <table class="data-table-products">
            <thead>
                <tr>
                    <th colspan="6" style="text-align:center;"> ORDEN - <b> {{ $tipo_laboratorio }}</b></th>
                </tr>


            </thead>
            <tbody>
                <tr style="background-color: #f2f4f4 ; ">
                    <td colspan="6"><b>GRUPO </b> - EXAMEN : <b> </b></td>
                </tr>

                <tr>

                    <td colspan="6">
                        @foreach ($solicitud_examen as $s_e)
                            <span style="display: inline-block; margin-left: 30px;">
                                <b>{{ $s_e['grupo'] }}</b> - {{ $s_e['nombre_examen'] }}
                            </span><br>
                        @endforeach
                        <br>
                        <span style="display: block; text-align: left;">
                            <em><b>Indicaciones:</b></em> {{ $solicitudes->observacion_solicitud }}
                        </span>
                    </td>

                </tr>


            </tbody>

            <tfoot>

                <tr style="background-color: #f2f4f4 ;">
                    <td colspan="6"><em><b>Diagnostico CIE10: </b></em> </td>
                </tr>

                @foreach ($hc_cie10 as $hc_cie)
                    <tr>
                        <td colspan="1" style="width:15%;"> {{ $hc_cie->datosCie10->codigo }} </td>
                        <td colspan="5" style="width:85%;"> {{ $hc_cie->datosCie10->descripcion }} </td>
                    </tr>
                @endforeach


                <tr style="background-color: #e5e8e8 ;">
                    <td colspan="6"><em><b> Diagnostico descriptivo: </b></em> {{ $historia_clinica->descriptivo }}
                    </td>
                </tr>

            </tfoot>
        </table>


    </div>
    <table style="font-family: Arial, sans-serif;   border-collapse: collapse;">
        <thead>
            <tr>
                <td style="width:40%; font-size:10px; padding: 0; border: 0; vertical-align: top;">
                    <b>Fecha de Impresión:</b> {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    <b>Hora:</b> {{ \Carbon\Carbon::now()->format(' H:i') }}
                </td>
                <td style="width:60%;   padding: 0; border: 0; text-align:right;">
                    <img src="images/sighoV2_transparente_calidad.png" width="60" height="16"
                        style="display: block; margin-left: auto; margin-right: 0;">

                </td>
            </tr>
        </thead>
    </table>
    <br><br>

    <table class="signatures-table">
        <tr>
            <td>
                _________________________<br>
                Firma/Sello Médico
            </td>
            <td>
                _________________________<br>
                Firma/Sello VV.DD./Plataforma
            </td>
        </tr>
    </table>


</body>

</html>
