<div>
    <x-ver-modal id="verRecetaLiquidacion" labelledby="verRecetaLiquidacionLabel" title="DETALLES DE  RECETA MÉDICA">
        <div class="card-comun p-3">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
                    <div class="card cabecera">
                        <b> DETALLE DE LA RECETA MÉDICA</b>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:right;">
                    @if ($id_estado == 1)
                        <p
                            style="display: inline-block; background-color: #6c757d; color: white; font-weight: bold; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                            PRESCRITO
                        </p>
                    @elseif($id_estado == 2)
                        <p class="p-2"
                            style="display: inline-block; background-color:#2471A3; color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.86rem;">
                            ENTREGADO </p>
                    @elseif($id_estado == 3)
                        <p class="p-2"
                            style="display: inline-block; background-color:rgba(218, 77, 59, 1); color:white;  font-weight: bold;  border-radius: 20px; font-size: 0.86rem;">
                            ANULADO </p>
                    @elseif($id_estado == 4)
                        <p class="p-2"
                            style="display: inline-block; background-color:rgb(212, 103, 30); color:rgb(241, 230, 230);  font-weight: bold;  border-radius: 20px; font-size: 0.86rem;">
                            CADUCADO </p>
                    @endif
                </div>
            </div>

            <div class="document-container">
                <table class="header-table">
                    <thead>
                        <tr>
                            <td class="first-col">
                                <img src="{{ asset('logo/ssu.png') }}" width="100" height="70" alt="Logo SSU">
                            </td>
                            <td class="second-col">
                                <p><b style="color:  #1a5276; font-size:16px; letter-spacing: 1.5px;">RECETA MÉDICA </b>
                                    <b
                                        style="text-decoration: underline; color: #a93226; letter-spacing: 1px; font-size:17px;">FARMACIA</b>
                                </p>

                            </td>
                            <td class="third-col">
                                <span style="font-size:18px;">
                                    <b>N° </b>
                                    {{ $nro_receta }} / {{ $receta?->created_at?->format('Y') ?? '' }}
                                </span><br>

                                <span style="font-size:14px;">
                                    <b>Especialidad: </b> {{ $especialidad }} <br>
                                    <b>Médico:</b> {{ $nombre_especialista }} <br>
                                    <b>Tipo Atención:</b> {{ $receta->tipoConsultaReceta->nombre ?? ' ' }}
                                </span>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>

            <br>

            <div class="document-encabezado">
                <table class="info-table">
                    <tbody>
                        <tr>
                            <td class="col-left">
                                <b>Matrícula: </b>
                                {{ $receta->personaReceta->matricula_seguro ?? '' }} {{ $codigo_institucional }}
                            </td>

                            <td class="col-right">
                                <b>Paciente: </b>
                                {{ $receta->personaReceta->p_apellido ?? '' }}
                                {{ $receta->personaReceta->s_apellido ?? '' }}
                                {{ $receta->personaReceta->nombres ?? '' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="col-left">
                                <b>Empleador: </b> {{ $datos_persona['institucion'] ?? '' }}
                            </td>

                            <td class="col-right">
                                <b>Tipo Asegurado: </b> {{ $datos_persona['tipo_asegurado'] ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <td> <b>Fecha de transcripción: </b>
                                {{ Carbon\Carbon::parse($fecha_transcripcion)->format('d/m/Y') }}
                            </td>
                            <td> <b>Fecha de entrega: </b>
                                {{ $fecha_entrega }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div style="height: 0.5rem;"></div>
        <div class="document-container">
            <div style="max-height: 300px; overflow-y: auto;">
                <table class="data-table-products">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Código</th>
                            <th style="width: 48%;">Producto</th>
                            <th style="width: 15%;">Forma Farm.</th>
                            <th style="width: 10%;">Dosificación</th>
                            <th style="width: 18%;">Forma Adm.</th>
                            <th style="width: 5%;">Cant.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicamentos as $data)
                            <tr>
                                <td style="text-align:center;">
                                    {{ $data->medicamentoRecetaMedicamento?->codigo_medicamento ?? '' }}</td>
                                <td>{{ $data->medicamentoRecetaMedicamento?->nombre ?? '' }}</td>
                                <td>{{ $data->medicamentoRecetaMedicamento?->forma_farmaceutica ?? '' }}</td>
                                <td>{{ $data['indicaciones'] }}</td>
                                <td>{{ $data['forma_adm'] }}</td>
                                <td style="text-align:center;"> {{ $data['cantidad'] }} </td>
                            </tr>
                        @endforeach

                        @if ($receta?->manual == false)
                    <tfoot>
                        <tr>
                            <td colspan="6"><em><b>Indicaciones: </b></em></td>
                        </tr>
                        @foreach ($medicamentos as $datos)
                            <tr>
                                <td>{{ $datos->medicamentoRecetaMedicamento?->codigo_medicamento ?? '' }} </td>
                                <td colspan="5">{{ $datos->recomendacion }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="6"><em><b> Diagnóstico descriptivo: </b></em>
                                {{ $receta->receta_historiaClinica?->descriptivo ?? '' }} </td>
                        </tr>
                    </tfoot>
                    @endif

                    @if ($receta?->manual == true)
                        @if ($tipo_consulta == 'QUIROFANO')
                            <tfoot>
                                <tr>
                                    <td colspan="6"><em><b> Observaciones Quirófano: </b></em>
                                        {!! $receta->observacion_quirofano !!} </td>
                                </tr>
                            </tfoot>
                        @endif
                    @endif
                </table>
            </div>
        </div>
        <div style="height: 0.5rem;"></div>
        @can('super-admin-only')
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="p-2 card card-borde mb-2 h-100">
                        <h6 class="mb-3 text-grey font-weight-bold border-bottom pb-1">
                            <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Nombre de usuario:</strong>
                                {{ $usuario_registra }}
                            </li>
                            <li>
                                <strong>Fecha de registro:</strong>
                                {{ Carbon\Carbon::parse($fecha_registro)->format('d/m/Y') }} <b> Hrs:</b>
                                {{ Carbon\Carbon::parse($fecha_registro)->format('H:i A') }}
                            </li>

                        </ul>
                    </div>
                </div>

                @if ($dispensado == true)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> DISPENSADO POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $usuario_dispensa }}
                                </li>
                                <li>
                                    <strong>Fecha de registro:</strong>
                                    {{ Carbon\Carbon::parse($fecha_dispensa)->format('d/m/Y') }} <b> Hrs:</b>
                                    {{ Carbon\Carbon::parse($fecha_dispensa)->format('H:i A') }}
                                </li>

                            </ul>
                        </div>
                    </div>
                @endif
                @if ($fue_anulado == true)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-danger font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> ANULADO POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $usuario_anula }}
                                </li>
                                <li>
                                    <strong>Fecha de registro:</strong>
                                    {{ Carbon\Carbon::parse($fecha_anula)->format('d/m/Y') }} <b> Hrs:</b>
                                    {{ Carbon\Carbon::parse($fecha_anula)->format('H:i A') }}
                                </li>

                            </ul>
                        </div>
                    </div>
                @endif


            </div>
        @endcan

    </x-ver-modal>

    <style>
        .info-table {
            margin-top: 0px;
            /* Elimina el margen superior */
            margin-bottom: 2px;
            /* Espacio para separar de la siguiente tabla de datos */
            border: none;
            /* Quitamos el borde sólido de esta tabla */

        }

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
            width: 42%;
            padding-left: 5px;
        }

        .header-table .third-col {
            width: 50%;
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

        caption {
            font-size: 0.7em;
            font-family: Arial, sans-serif;
            font-weight: bold;
            margin-bottom: 9px;
            text-align: left;
            text-decoration: underline;
        }

        .document-container table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
            font-family: Arial, sans-serif;
            border: none;
            font-size: 14px;
        }

        .document-container th {
            background-color: #f2f2f2;
            border: 1px solid #383a3b;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        .document-container td {
            border: 1px solid #383a3b;
            padding: 3px;
            text-align: left;
        }

        .document-encabezado table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
            font-family: Arial, sans-serif;
            border: none;
            font-size: 14px;
        }

        .document-encabezado th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        .document-encabezado td {
            padding: 3px;
            text-align: left;
        }
    </style>

</div>
<script>
    window.addEventListener('abrirRecetaLiquidacion', event => {
        $('#verRecetaLiquidacion').modal('show');
    });
</script>
