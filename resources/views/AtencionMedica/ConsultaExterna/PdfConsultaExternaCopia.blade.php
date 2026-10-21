<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>COPIA HISTORIA CLÍNICA</title>
    <style>
        @page {
            margin-top: 19px;
            margin-right: 40px;
            margin-bottom: 10px;
            margin-left: 40px;
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

        .header-block {
            page-break-inside: avoid;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        /* Estilos para el texto de las notas al pie */
        .notas {
            text-align: right;
            padding: 0px 0;
            /* Menos padding vertical para compactar */
            font-family: 'Times New Roman', serif;
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
            font-family: 'Times New Roman', serif;
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
            padding: 3px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /* Estilos para el caption */
        caption {
            font-size: 0.7em;
            font-family: 'Times New Roman', serif;
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
            width: 20%;
            padding-left: 5px;
            /* Mantenemos este padding para separar del logo */
            font-size: 14px;
        }

        .header-table .third-col {
            width: 72%;
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
        .info-table thead {
            margin: 0;
            padding: 0;
        }

        .data-table-products,
        .info-table {
            border-collapse: collapse;
            /* Elimina el espacio entre los bordes de las celdas */
            border-spacing: 0;
            /* Asegura que no haya espacio entre las celdas */
            margin-bottom: 0px;
        }

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
            font-size: 12px;
            margin-bottom: 2px;
            table-layout: auto;
            word-break: break-word;
            overflow-wrap: anywhere;
            page-break-inside: auto;
        }

        .data-table-products tr {
            page-break-inside: auto;
        }

        .data-table-products td {
            page-break-inside: auto;
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

        .content-cell {
            min-width: 0;
            white-space: normal;
            word-break: break-word;
            overflow-wrap: anywhere;
            max-width: 0;
        }
    </style>
</head>

<body>
    <div
        style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 999999;
            opacity: 0.15;
            background-image: url('{{ public_path('images/confidencialHD.png') }}');
            background-repeat: repeat;
            background-size: 300px 200px;
            background-position: center;
            pointer-events: none;
        ">

        <img src="{{ public_path('images/confidencialHD.png') }}" style="width:100%; height:100%;">

    </div>

    <div style="text-align:center;">
        <span style=" text-decoration:underline;  font-size:15px; font-weight:bold;">
            HOSPITAL DEL SEGURO SOCIAL UNIVERSITARIO LA PAZ
        </span>
    </div>
    <br>
    <div class="header-block">
        <table class="info-table">
            <thead>
                <tr>
                    <td style="width: 15%; text-align:center;">
                        {{ $historia_clinica->HistoriaClinicaPersona->p_apellido }}<br> <b>PATERNO</b>
                    </td>
                    <td style="width: 15%; text-align:center;">
                        {{ $historia_clinica->HistoriaClinicaPersona->s_apellido }} <br><b>MATERNO</b>
                    </td>
                    <td style="width: 20%; text-align:center;">
                        {{ $historia_clinica->HistoriaClinicaPersona->nombres }} <br> <b> NOMBRES</b>
                    </td>
                    <td style="width: 20%; text-align:center;">
                        {{ $historia_clinica->HistoriaClinicaPersona->matricula_seguro }} {{ $codigo_institucional }}
                        <br>
                        <b>MATRICULA </b>
                    </td>
                    <td style="width: 30%; text-align:center;">
                        {{ $institucion ?? ' ' }}
                        <br> <b> EMPLEADOR </b>
                    </td>
                </tr>
            </thead>
        </table>
    </div>


    <div class="document-container">

        <table class="data-table-products">
            <tbody>
                <tr>
                    <td style="width: 15%; vertical-align: top; white-space: normal; word-break: break-word;">
                        <div>
                            {{ \Carbon\Carbon::parse($historia_clinica->created_at)->format('d/m/Y') }}
                            <br>
                            <p>PRESIÓN:</p>
                            ...............................<br>
                            <p>TALLA:</p>
                            ...............................<br>
                            <p>PESO:</p>
                            ...............................<br>
                            <p>TEMPERATURA:</p>
                            ...............................<br>
                            TEMP. AXILAR
                            TEMP. RECTAL <br>
                            F. RESPIRATORIA <br>
                            F. CARDIACA <br>
                            SAT. OXIGENO
                            IMC
                        </div>

                    </td>
                    <td
                        style="width: 85%; vertical-align: top; min-width: 0; overflow-wrap: anywhere; word-break: break-word;">
                        <table class="data-table-products">
                            <tbody>
                                <tr>
                                    <td colspan="6" style="text-align:left;">
                                        <span><b>SUBJETIVO </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"
                                        style="text-align:left; overflow-wrap: anywhere; word-break: break-word; min-width:0;">
                                        <b>Motivo de la visita:</b> {{ $historia_clinica->motivo_consulta }}
                                    </td>
                                    <td colspan="3"
                                        style="text-align:left; overflow-wrap: anywhere; word-break: break-word; min-width:0;">
                                        <b>Enfermedad Actual:</b> {{ $historia_clinica->enfermedad_actual }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="text-align:left;">
                                        <span><b>OBJETIVO </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="text-align:left;">
                                        <span style="text-align:left;"> {{ $historia_clinica->objetivo }} </span>
                                    </td>

                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="6" style="text-align:left;">
                                        <span><b>ANALÍTICO </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="6"><em><b>Diagnostico CIE-10: </b></em> </td>
                                </tr>

                                @foreach ($hc_cie10 as $hc_cie)
                                    <tr>
                                        <td colspan="1" style="width:15%;">{{ $hc_cie->datosCie10->codigo }} </td>
                                        <td colspan="5" style="width:85%;"> {{ $hc_cie->datosCie10->descripcion }}
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="6"><em><b> Diagnóstico descriptivo: </b></em>
                                        {{ $historia_clinica->descriptivo }} </td>
                                </tr>

                            </tfoot>
                            @if ($historia_clinica->observacion_planes)
                                <tbody>
                                    <tr>
                                        <td colspan="6" style="text-align:left;">
                                            <span><b> OBSERVACIÓN PLANES: </b>
                                                {{ $historia_clinica->observacion_planes }} </span>
                                        </td>
                                    </tr>
                                </tbody>
                            @endif

                            @if (
                                $receta != null ||
                                    $imagenologias_con_estudios != null ||
                                    $baja_medica != null ||
                                    $fisioterapia != null ||
                                    $internacion != null ||
                                    $referencia != null ||
                                    $solicitudes_con_examenes != null ||
                                    $interconsulta != null)
                                <tbody>
                                    <tr>
                                        <td colspan="6" style="text-align:left;">
                                            <span><b> PLANES </b>
                                        </td>
                                    </tr>
                                    @if ($receta != null)

                                        <tr>
                                            <td colspan="1" style="width:15%; font-style:italic; text-align:center;">
                                                <b>Receta </b>
                                            </td>
                                            <td colspan="5" style="width:85%;">
                                                @foreach ($receta_medicamento_por_receta as $id_receta => $medicamentos)
                                                    @foreach ($medicamentos as $dato)
                                                        <b>Nº Receta:
                                                            {{ $dato->recetaRecetaMedicamento->nro_receta }} |</b>
                                                        <b>
                                                            {{ $dato->medicamentoRecetaMedicamento->codigo_medicamento }}</b>
                                                        - {{ $dato->medicamentoRecetaMedicamento->nombre }} -
                                                        {{ $dato->medicamentoRecetaMedicamento->forma_farmaceutica }}
                                                        {{ $dato->medicamentoRecetaMedicamento->concentracion }} <b>|
                                                            Cantidad:</b> {{ $dato->cantidad }}
                                                        <b>| Indicaciones: </b> {{ $dato->indicaciones }}
                                                        {{ $dato->forma_adm }} <b>|
                                                            Recomendaciones:</b>{{ $dato->recomendacion }}
                                                        <hr
                                                            style=" border: 0;border-top: 1px dashed #ccc;margin: 1px 0;">
                                                        <br>
                                                    @endforeach
                                                @endforeach

                                            </td>
                                        </tr>

                                    @endif

                                    @if ($solicitudes_con_examenes)

                                        <tr>
                                            <td colspan="1"
                                                style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                                Laboratorio </td>
                                            <td colspan="5" style="width:85%;">

                                                @foreach ($solicitudes_con_examenes as $item)
                                                    @php
                                                        $laboratorio = $item['laboratorio'];
                                                        $examenes = $item['examenes'];
                                                        $tipo_laboratorio = $item['tipo_laboratorio'];
                                                    @endphp
                                                    <b style="text-decoration:underline; text-align:center;">
                                                        {{ $tipo_laboratorio }}</b><br><br>

                                                    @foreach ($examenes as $dato)
                                                        <span style="margin-left: 10px; margin-bottom: 0;">
                                                            <b>{{ $loop->iteration }}.-</b><b> {{ $dato->grupo }}</b>
                                                            - {{ $dato->nombre_examen }}</span><br>
                                                    @endforeach

                                                    <br>
                                                    <b>Observaciones:</b> {{ $laboratorio->observacion_solicitud }}
                                                    <br><br>
                                                @endforeach

                                            </td>
                                        </tr>


                                    @endif

                                    @if ($imagenologias_con_estudios != null)

                                        <tr>
                                            <td colspan="1"
                                                style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                                Imagenología </td>
                                            <td colspan="5" style="width:85%;">
                                                @foreach ($imagenologias_con_estudios as $item)
                                                    @php
                                                        $imagenologia = $item['imagenologia'];
                                                        $estudios = $item['estudios'];
                                                    @endphp

                                                    <b
                                                        style="text-decoration:underline;">{{ $imagenologia->tipoImagenologia->nombre }}</b><br>

                                                    @foreach ($estudios as $dato)
                                                        <div style="margin-left:10px;">
                                                            <b>{{ $loop->iteration }}.-</b>
                                                            {{ $dato->datosEstudio->nombre }}
                                                        </div>
                                                    @endforeach

                                                    <b>Observaciones:</b> {{ $imagenologia->indicaciones }}
                                                    <br><br>
                                                @endforeach

                                            </td>
                                        </tr>


                                    @endif

                                    @if ($baja_medica != null)
                                        <tr>
                                            <td colspan="1"
                                                style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                                Baja Médica </td>
                                            <td colspan="5" style="width:85%;">
                                                <b>Tipo de Baja:</b>
                                                {{ $baja_medica->tipoBajaMedicaBajaMedica->nombre }} <br>
                                                <b>Fecha Inicio: </b>
                                                {{ \Carbon\Carbon::parse($baja_medica->fecha_ini_baja)->format('d/m/Y') }}
                                                <b>Fecha Fin:</b>
                                                {{ \Carbon\Carbon::parse($baja_medica->fecha_fin_baja)->format('d/m/Y') }}
                                                <b>Días Baja: </b> {{ $baja_medica->dias_baja }} <br>
                                                <b>Observaciones: </b> {{ $baja_medica->descripcion }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($fisioterapia != null)
                                        <tr>
                                            <td colspan="1"
                                                style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                                Fisioterapia </td>
                                            <td colspan="5" style="width:85%;"> <b>Nro. Sesiones:
                                                </b>{{ $fisioterapia->nro_sesion }} <br>
                                                <b>Observaciones: </b> {{ $fisioterapia->indicaciones }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($referencia != null)
                                        <tr>
                                            <td colspan="1"
                                                style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                                Referencia </td>
                                            <td colspan="5" style="width:85%;"> <b>Referido a: </b>
                                                {{ $especialidad_ref }} <b>Observaciones: </b>
                                                {{ $referencia->indicaciones }} </td>
                                        </tr>
                                    @endif
                                    @if ($interconsulta != null)
                                        <tr>
                                            <td colspan="1"
                                                style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                                Interconsulta </td>
                                            <td colspan="5" style="width:85%;"> <b>Referido a: </b>
                                                {{ $especialidad_inter }} <b>Observaciones: </b>
                                                {{ $interconsulta->indicaciones }} </td>
                                        </tr>
                                    @endif

                                    @if ($internacion != null)
                                        <tr>
                                            <td colspan="1"
                                                style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                                Internación </td>
                                            <td colspan="5" style="width:85%;"> <b>Fecha internación a: </b>
                                                {{ \Carbon\Carbon::parse($internacion->fecha_internacion)->format('d/m/Y') }}
                                                <b>Observaciones: </b> {{ $internacion->indicaciones }}

                                            </td>
                                        </tr>
                                    @endif

                                </tbody>
                            @endif
                        </table>


                    </td>
                </tr>
            </tbody>
        </table>
    </div>



    <table style="font-family: 'Times New Roman', serif;   border-collapse: collapse;">
        <thead>
            <tr>
                <td style="width:40%; font-size:12px; padding: 0; border: 0; vertical-align: top;">

                    <b>Especialidad: </b> {{ $especialidad }} <br>
                    <b>Médico:</b> {{ $nombre_especialista }} <br>
                    <b>Tipo Atención:</b> {{ $historia_clinica->historiaClinicaTipoConsulta->nombre }} <br>

                    <b>Fecha Emisión:</b> {{ $historia_clinica->created_at->format('d/m/Y') }}
                    <b>Hora:</b> {{ $historia_clinica->created_at->format('H:i') }}
                </td>
                <td style="width:40%; padding: 0; border: 0;vertical-align: top; text-align:right;">
                    <br><br><br><br>
                    <table class="signatures-table">
                        <tr>
                            <td>
                                _________________________<br>
                                Firma/Sello Médico
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%;   padding: 0; border: 0;vertical-align: top; text-align:right;">
                    <img src="images/sighoV2_transparente_calidad.png" width="60" height="16"
                        style="display: block; margin-left: auto; margin-right: 0;">
                </td>


            </tr>
        </thead>
    </table>

</body>

</html>
