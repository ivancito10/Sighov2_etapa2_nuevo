<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>HISTORIA CLÍNICA</title>
    <style>
        /* 1. Reseteamos los márgenes físicos para que no interfieran con la calibración */
        @page {
            margin: 0mm;
        }

        body {
            margin: 0mm;
            padding-top: 0mm;
            padding-left: 0mm;
            padding-right: 0mm;
            padding-bottom: 5mm;
        }

        /* 2. NUEVO: Contenedor maestro dinámico controlado por el médico */
        .dinamic-wrapper {
            position: relative;

            top: 5mm;
            left: 5mm;

            /* Ajustamos el ancho restando una proporción de seguridad (aprox 195mm)
               para que el contenido respete los límites de una hoja Letter standard */
            width: 195mm;
            box-sizing: border-box;
            margin-bottom: 5mm;
        }

        /* Tus estilos actuales se quedan exactamente igual desde aquí abajo */
        .document-container {
            margin: 0;

            padding-left: 0.7mm;
            padding-right: 0.7mm;
            background-color: white;
            position: relative;
            box-sizing: border-box;
        }

        .header-block {
            page-break-inside: avoid;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        .notas {
            text-align: right;
            padding: 0px 0;
            font-family: 'Times New Roman', serif;
            font-size: 10px;
            margin-top: 1px;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            border: none;
            page-break-inside: auto;
        }

        th {
            background-color: #f2f2f2;
            border: 1px solid #383a3b;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #383a3b;
            padding: 3px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        tr {
            page-break-inside: avoid !important;
        }

        caption {
            font-size: 0.7em;
            font-family: 'Times New Roman', serif;
            font-weight: bold;
            margin-bottom: 9px;
            text-align: left;
            text-decoration: underline;
        }

        .header-table {
            margin-bottom: 0px;
            margin-top: 0px;
            padding-top: 0px;
            border: none;
        }

        .header-table td {
            border: none !important;
            padding: 0;
            vertical-align: middle;
        }

        .header-table img {
            display: block;
        }

        .header-table .first-col {
            width: 8%;
            padding-right: 0;
        }

        .header-table .second-col {
            width: 20%;
            padding-left: 5px;
            font-size: 14px;
        }

        .header-table .third-col {
            width: 72%;
            text-align: right;
            padding-right: 0;
        }

        .header-table .second-col p {
            margin: 0;
            line-height: 1.2;
        }

        .header-table .third-col span {
            font-size: 12px;
            line-height: 1.3;
            display: block;
        }

        .info-table thead {
            margin: 0;
            padding: 0;
        }

        .data-table-products,
        .info-table {
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 0px;
        }

        .info-table {
            margin-top: 0px;
            margin-bottom: 2px;
            border: none;
            font-size: 12px;
        }

        .info-table td {
            border: none !important;
            vertical-align: top;
        }

        .data-table-products {
            border: 1px solid #000;
            font-size: 12px;
            margin-bottom: 2px;
            table-layout: fixed;
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
        }

        .data-table-products tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table-products tr:hover {
            background-color: #f1f1f1;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            border: none;
        }

        .signatures-table td {
            border: none !important;
            padding-top: 1px;
            text-align: center;
            vertical-align: bottom;
            font-size: 12px;
            position: relative;
        }

        .signatures-table td::before {
            content: '';
            position: absolute;
            bottom: 25px;
            left: 15%;
            right: 15%;
            border-bottom: 1px solid #000;
            width: 70%;
            margin: 0 auto;
        }

        .content-cell {
            min-width: 0;
            white-space: normal;
            word-break: break-word;
            overflow-wrap: anywhere;
            max-width: 0;
        }

        .col-signos {
            width: 15%;
            float: left;
            font-size: 10px;
            padding-right: 5px;
        }

        .col-contenido {
            width: 85%;
            float: right;
        }
    </style>
</head>

<body>
    <div class="dinamic-wrapper">


        <br> <br><br>
        <div style="text-align:center;">
            <span style=" text-decoration:underline;  font-size:15px; font-weight:bold;">
                HOSPITAL DEL SEGURO SOCIAL UNIVERSITARIO LA PAZ
            </span>
        </div>
        <br>
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
                        <b>MATRÍCULA </b>
                    </td>
                    <td style="width: 30%; text-align:center;">
                        {{ $institucion ?? ' ' }}
                        <br> <b> EMPLEADOR </b>
                    </td>
                </tr>
            </thead>
        </table>


        <div class="document-container">
            <div class="col-signos">
                <table class="data-table-products" style="border-collapse: collapse;">
                    <tr>
                        <td
                            style="width: 15%; vertical-align: top; white-space: normal; word-break: break-word; border-right: none;">
                            <div
                                style="text-align: center; margin-bottom: 10px; border-bottom: 1px dashed #383a3b; padding-bottom: 8px;">
                                <span
                                    style="font-weight: bold; display: block; font-size: 11px; text-decoration: underline;">SIGNOS
                                    VITALES</span>

                                <span style="display: block; font-size: 10px; color: #555; margin-top: 4px;">
                                    {{ \Carbon\Carbon::parse($historia_clinica->created_at)->format('d/m/Y') }}
                                </span>
                            </div>

                            <div style="line-height: 1.4; font-size: 10px;">
                                PRESIÓN ARTERIAL (mmHg):
                                <b>{{ $historia_clinica->signosVitales->presion_arterial ?? '....' }}</b><br>
                                <hr>

                                FRECUENCIA RESPIRATORIA (rpm):
                                <b>{{ $historia_clinica->signosVitales->frecuencia_respiratoria ?? '....' }}</b><br>
                                <hr>

                                TEMPERATURA (°C):
                                <b>{{ $historia_clinica->signosVitales->temperatura ?? '....' }}</b><br>
                                <hr>

                                FRECUENCIA CARDIACA (lpm):
                                <b>{{ $historia_clinica->signosVitales->frecuencia_cardiaca ?? '....' }}</b><br>
                                <hr>

                                SATURACIÓN OXÍGENO (%):
                                <b>{{ $historia_clinica->signosVitales->saturacion_oxigeno ?? '....' }}</b><br>
                                <hr>

                                PESO (KG): <b>{{ $historia_clinica->signosVitales->peso ?? '....' }}</b><br>
                                <hr>

                                TALLA (CM): <b>{{ $historia_clinica->signosVitales->talla ?? '....' }}</b><br>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-contenido">
                <table class="data-table-products">
                    <tbody>
                        <tr>
                            <td colspan="2" style="text-align:left;">
                                <span><b>SUBJETIVO </b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="text-align:justify; overflow-wrap: anywhere; word-break: break-word; min-width:0;">
                                <b>Motivo de la visita:</b> {{ $historia_clinica->motivo_consulta }} <br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="text-align:justify; overflow-wrap: anywhere; word-break: break-word; min-width:0;">
                                <b>Enfermedad Actual:</b> {{ $historia_clinica->enfermedad_actual }} </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left;">
                                <span><b>OBJETIVO </b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:left;">
                                <span style="text-align:left;"> {{ $historia_clinica->objetivo }} </span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="text-align:left;">
                                <span><b>ANALÍTICO </b>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2"><em><b>Diagnostico CIE-10: </b></em> </td>
                        </tr>

                        @foreach ($hc_cie10 as $hc_cie)
                            <tr>
                                <td style="width:15%;">{{ $hc_cie->datosCie10->codigo }}
                                </td>
                                <td style="width:85%;">
                                    {{ $hc_cie->datosCie10->descripcion }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="2"><em><b> Diagnóstico descriptivo: </b></em>
                                {{ $historia_clinica->descriptivo }} </td>
                        </tr>
                        @if ($historia_clinica->observacion_planes)
                            <tr>
                                <td colspan="2" style="text-align:left;">
                                    <span><b> OBSERVACIÓN PLANES: </b>
                                        {{ $historia_clinica->observacion_planes }} </span>
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td colspan="2" style="text-align:left;">
                                <span><b> PLANES </b> </span>
                            </td>
                        </tr>

                        @if ($receta != null)
                            {{ $receta }}
                            <tr>
                                <td style="width:15%; font-style:italic; text-align:center;">
                                    <b>Receta </b>
                                </td>
                                <td style="width:85%;">
                                    @foreach ($receta_medicamento_por_receta as $id_receta => $medicamentos)
                                        @foreach ($medicamentos as $dato)
                                            <b>Nº Receta:
                                                {{ $dato->recetaRecetaMedicamento->nro_receta }} |</b>
                                            <b>
                                                {{ $dato->medicamentoRecetaMedicamento->codigo_medicamento }}</b>
                                            - {{ $dato->medicamentoRecetaMedicamento->nombre }} -
                                            {{ $dato->medicamentoRecetaMedicamento->forma_farmaceutica }}
                                            {{ $dato->medicamentoRecetaMedicamento->concentracion }}
                                            <b>|
                                                Cantidad:</b> {{ $dato->cantidad }}
                                            <b>| Indicaciones: </b> {{ $dato->indicaciones }}
                                            {{ $dato->forma_adm }} <b>|
                                                Recomendaciones:</b>{{ $dato->recomendacion }}
                                            <hr style=" border: 0;border-top: 1px dashed #ccc;margin: 1px 0;">
                                            <br>
                                        @endforeach
                                    @endforeach
                                </td>
                            </tr>
                        @endif


                        @if ($solicitudes_con_examenes)

                            <tr>
                                <td style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                    Laboratorio </td>
                                <td style="width:85%;">

                                    @foreach ($solicitudes_con_examenes as $item)
                                        @php
                                            $laboratorio = $item['laboratorio'];
                                            $examenes = $item['examenes'];
                                            $tipo_laboratorio = $item['tipo_laboratorio'];
                                        @endphp
                                        <b style="text-decoration:underline; text-align:center;">
                                            {{ $tipo_laboratorio }}</b>

                                        @foreach ($examenes as $dato)
                                            <span style="margin-left: 10px; margin-bottom: 0;">
                                                <b>{{ $loop->iteration }}.-</b><b>
                                                    {{ $dato->grupo }}</b>
                                                - {{ $dato->nombre_examen }}</span><br>
                                        @endforeach

                                        <b>Observaciones:</b> {{ $laboratorio->observacion_solicitud }}
                                        <br><br>
                                    @endforeach
                                </td>
                            </tr>

                        @endif

                        @if ($imagenologias_con_estudios != null)

                            <tr>
                                <td style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                    Imagenología </td>
                                <td style="width:85%;">
                                    @foreach ($imagenologias_con_estudios as $item)
                                        @php
                                            $imagenologia = $item['imagenologia'];
                                            $estudios = $item['estudios'];
                                        @endphp

                                        <b
                                            style="text-decoration:underline;">{{ $imagenologia->tipoImagenologia->nombre }}</b>

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
                                <td style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                    Baja Médica </td>
                                <td style="width:85%;">
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
                                <td style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                    Fisioterapia </td>
                                <td style="width:85%;"> <b>Nro. Sesiones:
                                    </b>{{ $fisioterapia->nro_sesion }} <br>
                                    <b>Observaciones: </b> {{ $fisioterapia->indicaciones }}
                                </td>
                            </tr>
                        @endif

                        @if ($referencia != null)
                            <tr>
                                <td style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                    Referencia </td>
                                <td style="width:85%;"> <b>Referido a: </b>
                                    {{ $especialidad_ref }} <b>Observaciones: </b>
                                    {{ $referencia->indicaciones }} </td>
                            </tr>
                        @endif

                        @if ($interconsulta != null)
                            <tr>
                                <td style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                    Interconsulta </td>
                                <td style="width:85%;"> <b>Referido a: </b>
                                    {{ $especialidad_inter }} <b>Observaciones: </b>
                                    {{ $interconsulta->indicaciones }} </td>
                            </tr>
                        @endif

                        @if ($internacion != null)
                            <tr>
                                <td style="width:15%; font-style:italic; font-weight:bold; text-align:center;">
                                    Internación </td>
                                <td style="width:85%;"> <b>Fecha internación a: </b>
                                    {{ \Carbon\Carbon::parse($internacion->fecha_internacion)->format('d/m/Y') }}
                                    <b>Observaciones: </b> {{ $internacion->indicaciones }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div style="clear: both;"></div>

        <table style="font-family: 'Times New Roman', serif;   border-collapse: collapse;">
            <thead>
                <tr>
                    <td style="width:40%; font-size:12px; padding: 0; border: 0; vertical-align: top;">

                        <b>Especialidad: </b> {{ $especialidad }} <br>
                        <b>Médico:</b> {{ $nombre_especialista }} <br>
                        <b>Tipo Atención:</b> {{ $historia_clinica->historiaClinicaTipoConsulta->nombre }} <br>

                        <b>Fecha Emisión:</b>
                        {{ \Carbon\Carbon::parse($historia_clinica->created_at)->format('d/m/Y') }}
                        <b>Hora:</b> {{ \Carbon\Carbon::parse($historia_clinica->created_at)->format(' H:i') }}
                    </td>
                    <td style="width:40%;   padding: 0; border: 0;vertical-align: top; text-align:right;">
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
                    <td style="width:10%;   padding: 0; border: 0;vertical-align: top; text-align:right;">
                        <img src="images/sighoV2_transparente_calidad.png" width="60" height="16"
                            style="display: block; margin-left: auto; margin-right: 0;">
                    </td>
                    <td
                        style="width:10%; font-size:12px; padding: 0; border: 0; vertical-align: top; text-align:right;">
                        <img src="{{ $imagenQr }}" alt="Codigo QR" width="80" height="80" class="qr" />
                    </td>


                </tr>
            </thead>
        </table>
    </div>
</body>

</html>
