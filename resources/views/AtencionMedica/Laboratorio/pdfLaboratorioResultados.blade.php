<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LABORATORIO</title>
    <style>
        @page {
            margin: 120px 80px;
        }

        #header {
            position: fixed;
            left: 0px;
            top: -100px;
            right: 0px;
            height: 100px;
            text-align: center;
        }

        #footer {
            position: fixed;
            left: 0px;
            bottom: -120px;
            right: 0px;
            height: 100px;
        }

        #footer .page:after {
            content: counter(page, decimal);
        }
    </style>
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body FONT FACE="roman">
    @php
        setlocale(LC_TIME, 'Spanish');
        \Carbon\Carbon::setUtf8(true);
    @endphp
    <div id="header">
        <table width="100%" style= " border-bottom:5px double #070e99 ;">
            <tr>
                <td width="15%"><img src="images/sighoV2_transparente_calidad.png" height="30px" width="100px"></td>
                <td width="85%" VALIGN=middle align="margin-left" VALIGN=TOP><b
                        style="font-size: 7px;color:#070e99">SEGURO SOCIAL UNIVERSITARIO<br>AV. 6 DE AGOSTO Nº
                        2630<br>Telf. Piloto: 2434262 - Fax: 2434777<br>Casilla de Correo: 8227 <br> Email:
                        contactos@ssulapaz.org</b></td>
            </tr>
        </table>

    </div>
    <div id="footer">
        <div align="center" style="font-size: 10px;">RESULTADOS DE LABORATORIO SOLICITUD Nro.
            <b><i>{{ $solicitud['id_solicitud'] }}</i></b><br><i>{{ $solicitud['persona_asegurado']['asegurado']['matricula'] }}
                - {{ $solicitud['persona_asegurado']['asegurado']['codigo'] }}&nbsp;&nbsp;&nbsp;<span>{{ $solicitud['persona_asegurado']['ap_Paterno'] }}
                    {{ $solicitud['persona_asegurado']['ap_Materno'] }}
                    {{ $solicitud['persona_asegurado']['nombres'] }}</i></span>
        </div>
        <div align="right" style="font-size: 9px;">
            Transcrito por : {{ Auth::user()->name }}

        </div>
    </div>

    <div id="content">
        <div align="center" style="font-size: 10px;"><strong>RESULTADOS DE LABORATORIO SOLICITUD Nro.
                {{ $solicitud['id_solicitud'] }}</strong><br><b>ALTA DE RESULTADOS </b>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label
                align="rigth"><i><b>{{ $solicitud['tipo_atencion']['tipo_consulta'] }}</b></i></label></div>
        <table width="100%" class="table table-condensed"
            style="font-size: 10px;font-family: Courier, arial, helvetica size=8px;border-bottom:3px double ;">

            <tr>
                <td class="text-info" colspan="2">
                    <i><b>DATOS DEL ASEGURADO</b></i>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="text-danger">Fecha de Prescripción: <i
                            class="text-primary"><b>{{ $fecha_pdf }}</b></i></label>
                </td>
                <td>
                    <label class="text-danger pull-right">Fecha de Alta Resultados: <i
                            class="text-primary"><b>{{ $dato_fecha_modificacion }}</b></i></label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="text-danger">Asegurado: <i
                            class="text-primary"><b>{{ $solicitud['persona_asegurado']['ap_Paterno'] }}
                                {{ $solicitud['persona_asegurado']['ap_Materno'] }}
                                {{ $solicitud['persona_asegurado']['nombres'] }}</b></i></label>
                </td>
                <td>
                    <label class="text-danger">Matrícula: <i
                            class="text-primary"><b>{{ $solicitud['persona_asegurado']['asegurado']['matricula'] }}
                                @if ($solicitud['persona_asegurado']['asegurado']['codigo'] != null)
                                    - {{ $solicitud['persona_asegurado']['asegurado']['codigo'] }}
                                @endif
                            </b></i></label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="text-danger">Tipo Asegurado: <i
                            class="text-primary"><b>{{ $solicitud['persona_asegurado']['asegurado']['tipo_asegurado'] }}</b></i></label>
                </td>
                <td>
                    <label class="text-danger">Institucion: <i
                            class="text-primary"><b>{{ $solicitud['persona_asegurado']['asegurado']['institucion'] }}</b></i></label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="text-danger">Solicitante: <i
                            class="text-primary"><b>{{ $solicitud['persona_solicitante']['ap_Paterno'] }}
                                {{ $solicitud['persona_solicitante']['ap_Materno'] }}
                                {{ $solicitud['persona_solicitante']['nombres'] }}</b></i></label>
                </td>
                <td>
                    <label class="text-danger">Especialidad: <i
                            class="text-primary"><b>{{ $nombre_especialidad }}</b></i></label>
                </td>
            </tr>
        </table>
        <div class="text text-danger" style="font-size: 10px;"><strong>RESULTADOS:</strong></div>

        <table width="100%" style="font-size:10px;font-family:verdana, arial, helvetica;border-bottom:3px double ;">
        </table>
        <?php $n = 0; ?>


        <table width="100%">
            <tr>
                <td>

                    <!-- HEMATOLIGIA -->
                    @foreach ($parametro as $examen_maestro)

                        @if ($examen_maestro['id_examen'] == 1)
                            <table width="100%"
                                style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                                <thead class="text-danger" style="font-size:10px; ">
                                    <tr>
                                        <th style="border-bottom: 1px double #000000; " colspan="7">
                                            <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                        </th>
                                    </tr>
                                    @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                        @if ($detail_resul['id_examen_maestro'] == 1)
                                            <tr>
                                                <th style="border: 1px dotted #000000; " colspan="3">
                                                    FECHA RECEPCION
                                                    MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                                </th>

                                                <th s style="border: 1px dotted #000000; " colspan="4">
                                                    PROFESIONAL
                                                    RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                            {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                            {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                                </th>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <th colspan="4">

                                        </th>
                                        <th colspan="3" style="border-bottom: 1px dotted #000000;">
                                            <i>VALORES DE REFERENCIA</i>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <i>NRO</i>
                                        </th>
                                        <th>
                                            <i>PARAMETRO</i>
                                        </th>
                                        <th>
                                            <i>VALOR</i>
                                        </th>
                                        <th>
                                            <i>UNIDAD</i>
                                        </th>
                                        <!-- <th >
  <i>GRUPO</i>
  </th> -->
                                        <th>
                                            <i>VARONES</i>
                                        </th>
                                        <th>
                                            <i>NIÑOS</i>
                                        </th>
                                        <th>
                                            <i>MUJERES</i>
                                        </th>
                                        <!-- <th >
  <i>V. NORMAL A</i>
  </th>
  <th >
  <i>V. NORMAL B</i>
  </th>  -->
                                    </tr>
                                </thead>
                                <tbody style="font-size:10px;">

                                    @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                        <?php $sw = 0; ?>
                                        @if ($solicitud['resultados'])
                                            @foreach ($solicitud['resultados'] as $resultado)
                                                @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                    <?php $n = $n + 1; ?>
                                                    <?php $sw = 1; ?>
                                                    <tr>
                                                        <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                            align="center"> <b>{{ $n }}</b></td>
                                                        <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                            align="left"> {{ $parametro['etiqueta'] }}</td>
                                                        <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                            align="center">
                                                            <b><i>{{ $resultado['valor_resultado'] }}</i></b>
                                                        </td>
                                                        <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                            align="center">
                                                            {{ $parametro['unidades']['unidad'] ? $parametro['unidades']['unidad'] : '' }}
                                                        </td>

                                                        <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                            align="center">
                                                            {{ $parametro['varones'] ? $parametro['varones'] : '' }}
                                                        </td>
                                                        <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                            align="center">
                                                            {{ $parametro['ninos'] ? $parametro['ninos'] : '' }}</td>
                                                        <td style="border-bottom: 1px dotted #000000" align="center">
                                                            {{ $parametro['mujeres'] ? $parametro['mujeres'] : '' }}
                                                        </td>

                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                    @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                        @if ($detail_resul['id_examen_maestro'] == 1)
                                <tfoot>
                                    <tr>
                                        <td style="border: 1px dotted #000000; " align="left" colspan="4">
                                            <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                        </td>

                                        <th s style="border: 1px dotted #000000; " colspan="3">
                                            <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                    {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                    {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                        </th>
                                    </tr>
                                </tfoot>
                        @endif
                    @endforeach
                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>
                    <!-- QUIMICA SANGUINEA -->
                    @if ($examen_maestro['id_examen'] == 2)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="7">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 2)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="3">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="4">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <th colspan="4">

                                    </th>
                                    <th colspan="3" style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <th>
                                        <i>UNIDAD</i>
                                    </th>
                                    <!--<th >
  <i>GRUPO</i>
  </th> -->
                                    <th>
                                        <i>VARONES</i>
                                    </th>
                                    <th>
                                        <i>NIÑOS</i>
                                    </th>
                                    <th>
                                        <i>MUJERES</i>
                                    </th>
                                    <!-- <th >
  <i>V. NORMAL A</i>
  </th>
  <th >
  <i>V. NORMAL B</i>
  </th>  -->
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center"> <b>{{ $n }}</b></td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="left"> {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        <b><i>{{ $resultado['valor_resultado'] }}</i></b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        {{ $parametro['unidades']['unidad'] ? $parametro['unidades']['unidad'] : '' }}
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        {{ $parametro['varones'] ? $parametro['varones'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        {{ $parametro['ninos'] ? $parametro['ninos'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; " align="center">
                                                        {{ $parametro['mujeres'] ? $parametro['mujeres'] : '' }}</td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 2)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="5">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="2">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>
                    <!-- UROANALISIS -->
                    @if ($examen_maestro['id_examen'] == 3)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="4">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 3)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="2">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th style="border: 1px dotted #000000; " colspan="2">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <th colspan="3">

                                    </th>
                                    <th style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <!--<th >
  <i>UNIDAD</i>
  </th>
        <th >
  <i>GRUPO</i>
  </th>
  <th >
  <i>VARONES</i>
  </th>
  <th >
  <i>NIÑOS</i>
  </th>
  <th >
  <i>MUJERES</i>
  </th>-->
                                    <th>
                                        <i>V. NORMAL A</i>
                                    </th>
                                    <!--<th >
  <i>V. NORMAL B</i>
  </th>  -->
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center"> <b>{{ $n }}</b></td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="left"> {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        <b><i>{{ $resultado['valor_resultado'] }}</i></b>
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_a']) ? $parametro['valor_normal_a'] : '' }}
                                                    </td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 3)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="3">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="1">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>

        <table width="100%">
            <tr>
                <td>

                    <!-- COPROPARASITOLOGIA -->
                    @if ($examen_maestro['id_examen'] == 4)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="4">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 4)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="2">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="2">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach

                                <tr>
                                    <th colspan="3">

                                    </th>
                                    <th style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <!-- <th >
  <i>UNIDAD</i>
  </th> -->
                                    {{-- <th >
		        					<i>GRUPO</i>
		        				</th> --}}
                                    <!-- <th >
  <i>VARONES</i>
  </th>
  <th >
  <i>NIÑOS</i>
  </th>
  <th >
  <i>MUJERES</i>
  </th> -->
                                    <th>
                                        <i>V. NORMAL</i>
                                    </th>
                                    <!-- <th >
  <i>V. NORMAL B</i>
  </th>  -->
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $n }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="left">
                                                        {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $resultado['valor_resultado'] }}</b>
                                                    </td>

                                                    {{-- <td style="border-bottom: 1px dotted #000000" align="center">  {{isset($parametro['grupo'])?$parametro['grupo']:''}}</td> --}}

                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_a']) ? $parametro['valor_normal_a'] : '' }}
                                                    </td> --> -->

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 4)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="3">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="1">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>

                    <!-- HORMONAS -->
                    @if ($examen_maestro['id_examen'] == 6)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="9">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 6)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="3">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="6">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach

                                <tr>
                                    <th colspan="4">

                                    </th>
                                    <th colspan="4" style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <th>
                                        <i>UNIDAD</i>
                                    </th>
                                    <!-- <th >
           <i>GRUPO</i>
          </th> -->
                                    <th>
                                        <i>VARONES</i>
                                    </th>
                                    <th>
                                        <i>NIÑOS</i>
                                    </th>
                                    <th>
                                        <i>MUJERES</i>
                                    </th>
                                    <th>
                                        <i>V. NORMAL</i>
                                    </th>
                                    <th>
                                        <i>V. NORMAL B</i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $n }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="left">
                                                        {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $resultado['valor_resultado'] }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['unidades']['unidad'] ? $parametro['unidades']['unidad'] : '' }}
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['varones'] ? $parametro['varones'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['ninos'] ? $parametro['ninos'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['mujeres'] ? $parametro['mujeres'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_a']) ? $parametro['valor_normal_a'] : '' }}
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_b']) ? $parametro['valor_normal_b'] : '' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 6)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="6">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="3">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>

                    <!-- PUNCION_ASPIRATIVA->8 -->
                    @if ($examen_maestro['id_examen'] == 8)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="9">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 8)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="3">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="6">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach

                                <tr>
                                    <th colspan="4">

                                    </th>
                                    <th colspan="4" style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <th>
                                        <i>UNIDAD</i>
                                    </th>
                                    <!-- <th >
           <i>GRUPO</i>
          </th> -->
                                    <th>
                                        <i>VARONES</i>
                                    </th>
                                    <th>
                                        <i>NIÑOS</i>
                                    </th>
                                    <th>
                                        <i>MUJERES</i>
                                    </th>
                                    <th>
                                        <i>V. NORMAL</i>
                                    </th>
                                    <th>
                                        <i>V. NORMAL B</i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $n }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="left">
                                                        {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $resultado['valor_resultado'] }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['unidades']['unidad'] ? $parametro['unidades']['unidad'] : '' }}
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['varones'] ? $parametro['varones'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['ninos'] ? $parametro['ninos'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['mujeres'] ? $parametro['mujeres'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_a']) ? $parametro['valor_normal_a'] : '' }}
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_b']) ? $parametro['valor_normal_b'] : '' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 8)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="6">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="3">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>

                    <!-- LIQUIDOS->10 -->
                    @if ($examen_maestro['id_examen'] == 10)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="7">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 10)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="3">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="4">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach

                                <tr>
                                    <th colspan="4">

                                    </th>
                                    <th colspan="3" style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <th>
                                        <i>UNIDAD</i>
                                    </th>
                                    <!-- <th >
           <i>GRUPO</i>
          </th> -->
                                    <th>
                                        <i>VARONES</i>
                                    </th>
                                    <th>
                                        <i>NIÑOS</i>
                                    </th>
                                    <th>
                                        <i>MUJERES</i>
                                    </th>
                                    <!--<th >
           <i>V. NORMAL</i>
          </th>
          <th >
           <i>V. NORMAL B</i>
          </th>-->
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $n }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="left">
                                                        {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $resultado['valor_resultado'] }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['unidades']['unidad'] ? $parametro['unidades']['unidad'] : '' }}
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['varones'] ? $parametro['varones'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['ninos'] ? $parametro['ninos'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ $parametro['mujeres'] ? $parametro['mujeres'] : '' }}</td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 10)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="4">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="3">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>

                    <!-- HEMA->11-->
                    @if ($examen_maestro['id_examen'] == 212)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="5">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 11)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="2">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="3">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach

                                <tr>
                                    <th colspan="4">

                                    </th>
                                    <th style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <th>
                                        <i>UNIDAD</i>
                                    </th>
                                    <!-- <th >
           <i>GRUPO</i>
          </th>
          <th >
           <i>VARONES</i>
          </th>
          <th >
           <i>NIÑOS</i>
          </th>
          <th >
           <i>MUJERES</i>
          </th>-->
                                    <th>
                                        <i>V. NORMAL</i>
                                    </th>
                                    <!--<th >
           <i>V. NORMAL B</i>
          </th>-->
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $n }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="left">
                                                        {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $resultado['valor_resultado'] }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{-- UNIDAaaaaaaaaaaaaaaaaaaaaaaaaaD --}}
                                                        {{ $parametro['unidades']['unidad'] ?? '' }}
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_a']) ? $parametro['valor_normal_a'] : '' }}
                                                    </td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 11)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="3">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="2">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>

                    <!-- SEROLOGICOS->11-->
                    @if ($examen_maestro['id_examen'] == 11)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="5">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 11)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="2">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="3">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach

                                <tr>
                                    <th colspan="4">

                                    </th>
                                    <th style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <th>
                                        <i>UNIDAD</i>
                                    </th>
                                    <!-- <th >
           <i>GRUPO</i>
          </th>
          <th >
           <i>VARONES</i>
          </th>
          <th >
           <i>NIÑOS</i>
          </th>
          <th >
           <i>MUJERES</i>
          </th>-->
                                    <th>
                                        <i>V. NORMAL</i>
                                    </th>
                                    <!--<th >
           <i>V. NORMAL B</i>
          </th>-->
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $n }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="left">
                                                        {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        <b>{{ $resultado['valor_resultado'] }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{-- {{ $parametro['unidades']['unidad'] ? $parametro['unidades']['unidad'] : '' }} --}}
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_a']) ? $parametro['valor_normal_a'] : '' }}
                                                    </td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 11)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="3">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="2">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>
                    <!-- UROANALISIS DE 24 horas 194-->
                    @if ($examen_maestro['id_examen'] == 194)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="4">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 194)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="2">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="2">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <th colspan="3">

                                    </th>
                                    <th style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>PARAMETRO</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <!--<th >
           <i>UNIDAD</i>
          </th>
          <th >
           <i>GRUPO</i>
          </th>
          <th >
           <i>VARONES</i>
          </th>
          <th >
           <i>NIÑOS</i>
          </th>
          <th >
           <i>MUJERES</i>
          </th>-->
                                    <th>
                                        <i>V. NORMAL A</i>
                                    </th>
                                    <!--<th >
           <i>V. NORMAL B</i>
          </th>  -->
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center"> <b>{{ $n }}</b></td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="left"> {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        <b><i>{{ $resultado['valor_resultado'] }}</i></b>
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000" align="center">
                                                        {{ isset($parametro['valor_normal_a']) ? $parametro['valor_normal_a'] : '' }}
                                                    </td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 194)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="3">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="1">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
    @else
        </td>
        </tr>
        </table>
        <table width="100%">
            <tr>
                <td>
                    <!-- EXAMENES_COVID->184 no se reportan por aqui bacterio ni patologia  -->
                    @if ($examen_maestro['id_examen'] == 184)
                        <table width="100%" class="table table-condensed"
                            style="font-size:10px;font-family:Courier, arial, helvetica;border-bottom:3px double ;">
                            <thead class="text-danger" style="font-size:10px; ">
                                <tr>
                                    <th style="border-bottom: 1px double #000000; " colspan="8">
                                        <i><b>{{ $examen_maestro['nombre_examen'] }}</b></i>
                                    </th>
                                </tr>
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 184)
                                        <tr>
                                            <th style="border: 1px dotted #000000; " colspan="4">
                                                FECHA RECEPCION
                                                MUESTRA:<br><i><b>{{ \Carbon\Carbon::parse($detail_resul['fecha_recepcion_muestra'])->formatLocalized('%A %d %B %Y') }}</b></i>
                                            </th>

                                            <th s style="border: 1px dotted #000000; " colspan="4">
                                                PROFESIONAL
                                                RESPONSABLE:<br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                        {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <th colspan="3">

                                    </th>
                                    <th colspan="5" style="border-bottom: 1px dotted #000000;">
                                        <i>VALORES DE REFERENCIA</i>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <i>NRO</i>
                                    </th>
                                    <th>
                                        <i>ETIQUETA</i>
                                    </th>
                                    <th>
                                        <i>VALOR</i>
                                    </th>
                                    <!--<th >
  <i>UNIDAD</i>
  </th>
  <th >
  <i>GRUPO</i>
  </th>-->
                                    <th>
                                        <i>VARONES</i>
                                    </th>
                                    <th>
                                        <i>NIÑOS</i>
                                    </th>
                                    <th>
                                        <i>MUJERES</i>
                                    </th>
                                    <th>
                                        <i>V. NORMAL</i>
                                    </th>
                                    <th>
                                        <i>V. NORMAL B</i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody style="font-size:10px;">

                                @foreach ($examen_maestro['examen_parametro'] as $parametro)
                                    <?php $sw = 0; ?>
                                    @if ($solicitud['resultados'])
                                        @foreach ($solicitud['resultados'] as $resultado)
                                            @if ($resultado['id_parametro'] == $parametro['id_estudio_parametro'])
                                                <?php $n = $n + 1; ?>
                                                <?php $sw = 1; ?>
                                                <tr>
                                                    <td style="border-bottom: 1px dotted #000000" align="left">
                                                        <b>{{ $n }}</b>
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="left"> {{ $parametro['etiqueta'] }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center"> <b>{{ $resultado['valor_resultado'] }}</b>
                                                    </td>

                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        {{ $parametro['varones'] ? $parametro['varones'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        {{ $parametro['ninos'] ? $parametro['ninos'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        {{ $parametro['mujeres'] ? $parametro['mujeres'] : '' }}</td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        {{ isset($parametro['valor_normal_a']) ? $parametro['valor_normal_a'] : '' }}
                                                    </td>
                                                    <td style="border-bottom: 1px dotted #000000; border-right: 1px dotted #000000"
                                                        align="center">
                                                        {{ isset($parametro['valor_normal_b']) ? $parametro['valor_normal_b'] : '' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @foreach ($solicitud['detalle_resultado'] as $detail_resul)
                                    @if ($detail_resul['id_examen_maestro'] == 184)
                            <tfoot>
                                <tr>
                                    <td style="border: 1px dotted #000000; " align="left" colspan="6">
                                        <b>Nota</b> :<i>{{ $detail_resul['observaciones'] }}</i>
                                    </td>

                                    <th s style="border: 1px dotted #000000; " colspan="2">
                                        <br><br><br><i><b>{{ $detail_resul['persona_responsable']['nombres'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Paterno'] }}
                                                {{ $detail_resul['persona_responsable']['ap_Materno'] }}</b></i>
                                    </th>
                                </tr>
                            </tfoot>
                    @endif
                    @endforeach

                    </tbody>
        </table>
        @endif
        @endif
        </td>
        </tr>
        </table>
        @endforeach

    </div>
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf['page_script']('
                $font = $fontMetrics['get_font']("Courier, Helvetica, sans-serif", "normal");
                $pdf['text'](270, 750, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    	</script>

</body>

</html>
