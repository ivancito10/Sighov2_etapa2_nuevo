<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDEN BAJA MÉDICA</title>

    <style>
        body , html{
            margin: 15px 25px;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;
            display: flex;
            flex-direction: column;
        }

        .document-container {
            /* Mantenemos el padding lateral, pero nos aseguramos de no tener padding-top que genere espacio */
            padding-left: 0.7mm; /* Ajusta este valor para el margen izquierdo general del contenido */
            padding-right: 0.7mm; /* Ajusta este valor para el margen derecho general del contenido */
             
            background-color: white;
            /* border-radius: 5px; /* Comentado, si no necesitas bordes redondeados en el contenedor */
            position: relative;
            box-sizing: border-box; /* Para que el padding no aumente el ancho total */
            margin: 0; 
            padding: 0;
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px; /* Puedes ajustar este margen si afecta el espacio con el contenido debajo */
            font-family: 'Times New Roman', Times, serif
        }

        .document-content {
            line-height: 1.1; /* Corrección: usa coma en vez de punto si es una separación decimal */
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            padding-bottom: 1px;
        }

        .highlight {
            font-weight: bold;
        }

        .recommendation {
            margin-top: 20px;
            font-style: italic;
            color: #555;
        }

        #header {
            position: fixed;
            left: 0px;
            top: -30px;
            right: 0px;
            height: 150px;
            background-color: white;
            text-align: center;
            margin-left: 35px;
        }

        #watermark {
            position: fixed;
            bottom: 0px;
            left: 50px;
            top: 120px;
            opacity: 0.5;
            width: 15cm;
            height: 15cm;
            z-index: -1000;
        }

        i.subrayado {
            text-decoration: overline;
        }

        .interlineado {
            line-height: 80%;
            text-align: center;
        }

        .logo-right {
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .logo-left {
            position: absolute;
            top: 15px;
            left: 30px;
        }

        /* Estilos para eliminar el espacio entre los títulos */
        .document-title h4 {
            margin-bottom: 0; /* Elimina el margen inferior del h4 */
        }

        .document-title h5 {
            margin-top: 0; /* Elimina el margen superior del h5 */
        }
        /* Estilos generales para TODAS las tablas */
        table {
            width: 100%;
            border-collapse: collapse; /* Colapsa los bordes de las celdas */
            margin-bottom: 0px; /* Por defecto, las tablas no tendrán margen inferior, se controlará individualmente */
            font-family: Arial, sans-serif;
            font-size: 12px;
            border: none; /* Quitamos el borde solid que afectaba a todas las tablas por defecto */
            margin: 0;
            padding: 0;
        }

        /* Estilos para las celdas del encabezado (<th>) de las tablas de datos */
        th {
            background-color: #f2f2f2;
            border: 1px solid #383a3b; /* Ajustado a tu color de borde deseado */
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        /* Estilos para las celdas de datos (<td>) */
        td {
            border: 1px solid #383a3b; /* Ajustado a tu color de borde deseado */
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
        /* ESTILOS PARA LA TERCERA TABLA (LISTADO DE PRODUCTOS) */
        .data-table-products {
            border: 1px solid #000; /* Borde exterior de la tabla */
            font-size: 12px;
            margin-bottom: 0px; /* Espacio entre esta tabla y la nota */
        }

        .data-table-products th,
        .data-table-products td {
            border: 1px solid #383a3b; /* Bordes internos para las celdas de esta tabla */
        }

        /* Estilos para filas pares (opcional, para mejor legibilidad) */
        .data-table-products tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Estilo al pasar el ratón por encima de una fila (opcional) */
        .data-table-products tr:hover {
            background-color: #f1f1f1;
        }

        /* **IMPORTANTE: ESTILOS PARA LAS CELDAS DE LA TABLA data-table-products** */
        /* Aseguramos que los anchos se apliquen tanto a TH como a TD */
        .data-table-products th:nth-child(1),
        .data-table-products td:nth-child(1) {
            width: 55%; /* Motivo de Baja */
        }

        .data-table-products th:nth-child(2),
        .data-table-products td:nth-child(2) {
            width: 30%; /* Periodo */
        }

        .data-table-products th:nth-child(3),
        .data-table-products td:nth-child(3) {
            width: 15%; /* Nro. de Días */
        }
        /* -------------------------------------------------------- */
        /* ESTILOS ESPECÍFICOS PARA LA TABLA DE INFORMACIÓN (MATRICULA, ASEGURADO, ETC.) */
        
        .info-table thead {
            margin: 0;
            padding: 0;
        }
        .data-table-products,
        .info-table {
            border-collapse: collapse; /* Elimina el espacio entre los bordes de las celdas */
            border-spacing: 0; /* Asegura que no haya espacio entre las celdas */
            margin-bottom: 0px;
        }

        .info-table {
            margin-top: 0px; /* Elimina el margen superior */
            margin-bottom: 2px; /* Espacio para separar de la siguiente tabla de datos */
            border: none; /* Quitamos el borde sólido de esta tabla */
            font-size:12px;
        }

        .info-table td {
            border: none !important; /* Aseguramos que las celdas no tengan bordes */ 
            vertical-align: top; /* Alinea el texto en la parte superior si la celda es más alta */
        }
        /* Para la imagen del logo específicamente */
        .info-table img {
            display: block; /* Asegura que se comporte como un bloque y 'margin: 0;' funcione */
            margin: 0px !important;
            padding: 0px !important;
        }

        /* -------------------------------------------------------- */ 
        /* NUEVOS ESTILOS PARA LA SECCIÓN DE FIRMAS */
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1px; /* Espacio después de las firmas */
            border: none; /* Asegura que la tabla no tenga bordes */
        }

        .signatures-table td {
            border: none !important; /* Asegura que las celdas no tengan bordes */
            padding-top: 2px; /* Espacio para la línea de firma y el texto "Firma..." */
            text-align: center; /* Centra el texto "Firma Médico" y "Firma Paciente" */
            vertical-align: bottom; /* Alinea el texto en la parte inferior de la celda */
            font-size: 10px; /* Tamaño de fuente para las etiquetas de firma */
            position: relative; /* Necesario para posicionar la línea horizontal */
        }

        /* Línea horizontal para la firma */
        .signatures-table td::before {
            content: '';
            position: absolute;
            bottom: 50px; /* Aumenta este valor para subir la línea de firma. Prueba con 50px o más. */
            left: 15%; /* Inicia la línea más hacia la izquierda dentro de la celda */
            right: 15%; /* Termina la línea más hacia la derecha dentro de la celda */
            border-bottom: 1px solid #000; /* Estilo de la línea */
            width: 70%; /* Ancho de la línea (100 - left - right) */
            margin: 0 auto; /* Centra la línea dentro del espacio disponible */
        }

        .logo-left-placeholder {
            border: 2px dashed #ccc; /* Un borde punteado para ver el contorno */
            background-color: #f0f0f0; /* Un color de fondo para llenar el espacio */
            display: block; /* Asegura que el elemento ocupe su propio espacio */
            /* Opcional: puedes agregar texto o un icono para mayor claridad */
            text-align: center;
            line-height: 150px; /* Centra verticalmente si es un solo texto */
            font-size: 14px;
            color: #888;
        }
         
         
    </style>

</head>
<body>
      
        <div class="document-container" style="text-align: justify; padding: 15px; border: 3px solid #1a5276; border-radius: 10px; ">
            <div style="display: flex; align-items: center; justify-content: space-between;"> 

                <div class="document-title" style="text-align: center; flex-grow: 1;">
                    <img src="logo/ssu.png" class="logo-left" width="100" height="70" alt="Logo"
                    style="padding-left: 20px; margin-top: 0px;">

                    <h4 style="font-family: Arial, sans-serif; letter-spacing: 1px; color: #1a5276; margin-top: 1;">
                        HOSPITAL DEL SEGURO SOCIAL  <br>UNIVERSITARIO LA PAZ
                    </h4>
                    
                    <h5 style="text-decoration: underline; font-family: Arial, sans-serif; letter-spacing: 1px; color: #a93226;">
                        FORMULARIO DE INCAPACIDAD TEMPORAL   
                    </h5>
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="85" height="85"
                    class="qr" style="position: absolute; right: 40px; top: 10px; "/>
                </div>

            </div>
            
            <div class="document-content"> 
                <table class="info-table"> 
                    <thead>
                        <tr>
                            <td colspan="3" class="codigo-baja" style="text-align:right;"> <b> Código de Baja Médica: </b>
                                <span style="font-size:19px;">  BM/N°/{{$baja_medica->nro_baja_medica}}/{{ \Carbon\Carbon::parse($fecha_creacion)->format('Y') }}  </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 55%;" style="text-align:left;">
                                 
                                <b>Paciente:</b> 
                                {{$historia_clinica->HistoriaClinicaPersona->p_apellido}} 
                                {{$historia_clinica->HistoriaClinicaPersona->s_apellido}}
                                {{$historia_clinica->HistoriaClinicaPersona->nombres}} <br>
                                <b>Matrícula: </b> {{$historia_clinica->HistoriaClinicaPersona->matricula_seguro}}   {{$codigo_institucional}} <br>
                                <b>Carnet:</b> {{$historia_clinica->HistoriaClinicaPersona->ci}}  {{$historia_clinica->HistoriaClinicaPersona->complemento}} <br>
                                
                                @if($nro_instituciones == 1)
                                <b>Empleador: </b>
                                    @foreach ($instituciones as $ins)
                                        {{ $ins['nombre_institucion'] }}  
                                    @endforeach

                                <br>
                                @else
                                    <b>Empleador: </b>  <br> 
                                    - {{$institucion}} <br>
                                    @foreach ($instituciones as $ins)
                                        @if ($ins['nombre_institucion'] != $institucion)
                                            -  {{ $ins['nombre_institucion'] }} <br>
                                        @endif
                                    @endforeach
                                 
                                @endif
 
                                
                                

                            </td>
                            <td style="width: 30%;" style="text-align:left;">
                                 
                                <b>Edad: </b> {{ \Carbon\Carbon::parse($historia_clinica->HistoriaClinicaPersona->fecha_nacimiento)->age }} <br>
                                <b>Sexo: </b>
                                    @if($historia_clinica->HistoriaClinicaPersona->sexo == 'F' )
                                        Femenino
                                    @else
                                        Masculino
                                    @endif
                                <br>
                                <b>Tipo Paciente: </b> @if($tipo_asegurado == 'TITULAR') ASEGURADO @else {{$tipo_asegurado}} @endif
                                <br>
                                @php
                                    $nombre = $baja_medica->tipoBajaMedicaBajaMedica->nombre;
                                @endphp 
                                <b>Incapacidad Laboral:</b>

                                @if (Str::contains($nombre, 'ACCIDENTE'))
                                    <br>
                                @endif

                                <b>{{ $nombre }}</b> 
                                
                                 
                            </td>
                            <td style="width: 15%; vertical-align: top;"> 
                                    @if (!empty($foto_asegurado))
                                        <img src="{{ $foto_asegurado }}"
                                            style="max-width: 100%; height: auto; display: block; margin-left: auto; margin-right: auto;"
                                            width="150"
                                            height="150"
                                            alt="Foto Asegurado">

                                    @else
                                        <img src="images/default_afiliacion.jpg"
                                            style="max-width: 100%; height: auto; display: block; margin-left: auto; margin-right: auto;"
                                            width="130" height="130" alt="Foto Asegurado">
                                        <!-- <div style="width: 90px; height: 90px; border: 1px dashed #ccc;
                                                    display: block; margin-left: auto; margin-right: auto;
                                                    text-align: center; line-height: 90px; color: #999;">
                                            Sin foto
                                        </div> -->
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
                            <th style="width: 50%;" style="text-align:center;">Motivo de Baja</th>
                            <th style="width: 30%;" style="text-align:center;">Periodo</th>
                            <th style="width: 20%;" style="text-align:center;">Nro. de Días</th> 
                        </tr>
                    </thead>
                    <tbody> 
                        <tr>
                            <td> {{$baja_medica->descripcion}} </td>
                            <td style="text-align:center;"> 
                                <span> <b>Del:</b> <span style="font-size:13px;"> {{ \Carbon\Carbon::parse($baja_medica->fecha_ini_baja)->format('d/m/Y') }}</span></span>  
                                <span> <b>Al:</b> <span style="font-size:13px;"> {{ \Carbon\Carbon::parse($baja_medica->fecha_fin_baja)->format('d/m/Y') }}</span> </span>
                            </td>
                            <td style="text-align:center;"> {{$baja_medica->dias_baja}} </td> 
                        </tr>  
                    </tbody>
                    
                        
                </table>
                <table class="data-table-products">
                    <tfoot>
                        <tr>
                            <td colspan="2"><em><b> Diagnostico descriptivo: </b></em> {{$hc_planes->HistoriaClinica->descriptivo}} </td>
                        </tr>
                        <tr>
                            <td colspan="2"><em><b>Diagnostico CIE10: </b></em>   </td>
                        </tr>
                        @foreach ($hc_cie10 as $hc_cie)
                        <tr>
                            <td style="width:15%;"> {{ $hc_cie->datosCie10->codigo }}  </td>
                            <td style="width:85%;"> {{ $hc_cie->datosCie10->descripcion }}  </td>
                        </tr>
                        @endforeach
                         
                    </tfoot>
                </table>
 
            </div>
             
            <table style="font-family: Arial, sans-serif;   border-collapse: collapse;">
                <thead>
                    <tr>
                        <td style="width:60%; font-size:10px; padding: 0; border: 0; vertical-align: top;">
                            <b>Fecha de Impresión:</b> {{ \Carbon\Carbon::parse($fecha_creacion)->format('d/m/Y') }}
                            <b>Hora:</b> {{ \Carbon\Carbon::parse($fecha_creacion)->format(' H:i') }}
                              
                            <br>
                            <b>Tipo de Atención:</b> {{$historia_clinica->historiaClinicaTipoConsulta->nombre}}
                        </td>
                        <td style="width:60%;   padding: 0; border: 0; text-align:right;">
                            <img src="images/sighoV2_transparente_calidad.png" 
                                width="60" 
                                height="16"
                                style="display: block; margin-left: auto; margin-right: 0;">
                                 
                        </td> 
                    </tr>
                </thead>
            </table>
            <br>
            <br> 

            <table class="signatures-table">
                <tr>
                    <td> 
                        Vo. Bo. GERENTE DE SALUD.
                        
                    </td>
                    <td> 
                        Dr. {{$nombre_especialista}}  
                    </td> 
                </tr>
                 
            </table>
             
            <table class="info-table" style=" font-family: Arial, sans-serif;">
                <thead>
                    <tr> 
                        <td style="width: 100%; text-align:left; font-size:10px;">
                            <em><b>Nota:</b> Los partes de baja otorgados por los médicos del seguro, deberán ser presentados al empleador por el 
                                trabajador u otra persona, en el término máximo de 24 horas de haberse practicado la consulta. En caso contrario perderá el salario
                            o el subsidio por el tiempo de retraso. Art. 60 R.C.S.G</em></td>
                          
                    </tr> 
                </thead>
            </table>
            
                        
        </div>
        
     
</body>
</html>