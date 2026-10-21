<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe Diario de Consulta Externa</title>
    <style>
        body {
            margin: 0; /* Elimina el margen predeterminado del body */
            padding: 0; /* Elimina el padding predeterminado del body */
            line-height: 1;
        }
        .document-container {
            /* Mantenemos el padding lateral, pero nos aseguramos de no tener padding-top que genere espacio */
            padding-left: 0.7mm; /* Ajusta este valor para el margen izquierdo general del contenido */
            padding-right: 0.7mm; /* Ajusta este valor para el margen derecho general del contenido */
            /* Asegura que el contenedor no añada espacio abajo */
            background-color: white;
            /* border-radius: 5px; /* Comentado, si no necesitas bordes redondeados en el contenedor */
            position: relative;
            box-sizing: border-box; /* Para que el padding no aumente el ancho total */
            margin: 0; 
            padding: 0;
        }

        /* Estilos para el texto de las notas al pie */
        .notas {
            text-align: right;
            padding: 0px 0; /* Menos padding vertical para compactar */
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin-top: 1px; /* Pequeño espacio para separarlo de la tabla anterior */
            margin-bottom: 25px; /* Espacio antes de las firmas, ajusta este valor si necesitas más/menos */
        }

        /* Estilos generales para TODAS las tablas */
        table {
            width: 100%;
            border-collapse: collapse; /* Colapsa los bordes de las celdas */
            margin-bottom: 0px; /* Por defecto, las tablas no tendrán margen inferior, se controlará individualmente */
            font-family: Arial, sans-serif;
            font-size: 12px;
            border: none; /* Quitamos el borde solid que afectaba a todas las tablas por defecto */
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

        /* -------------------------------------------------------- */
        /* ESTILOS ESPECÍFICOS PARA LA TABLA DEL ENCABEZADO (LOGO Y RECETA) */
        .header-table {
            margin-bottom: 0px; /* Elimina el margen inferior */
            margin-top: 0px; /* Aseguramos que no haya margen superior en la tabla */
            padding-top: 0px; /* Aseguramos que no haya padding superior en la tabla */
            border: none; /* Quitamos el borde sólido de esta tabla */
        }

        .header-table td {
            border: none !important; /* Aseguramos que las celdas no tengan bordes */
            padding: 0; /* Quitamos padding de las celdas */
            vertical-align: middle;
        }

        .header-table img {
            display: block; /* Ayuda a controlar el espacio si hay algún margen invisible alrededor de la imagen */
            /* Si la imagen tiene un margen inferior por alguna razón, puedes añadir: */
            /* margin-bottom: -5px; */ /* Ajusta si es necesario */
        }

        .header-table .first-col {
            width: 8%; /* Ajuste de ancho si es necesario */
            padding-right: 0;
        }

        .header-table .second-col {
            width: 32%;
            padding-left: 5px; /* Mantenemos este padding para separar del logo */
            font-size:14px;
        }

        .header-table .third-col {
            width: 60%;
            text-align: right;
            padding-right: 0;
        }

        /* Ajustes para los párrafos dentro de la segunda columna del encabezado */
        .header-table .second-col p {
            margin: 0; /* Elimina los márgenes predeterminados de los <p> */
            line-height: 1.2; /* Ajusta el espacio entre las líneas de texto */
        }

        /* Ajustes para el span dentro de la tercera columna del encabezado */
        .header-table .third-col span {
            font-size: 12px;
            line-height: 1.3;
            display: block; /* Para que cada línea de texto ocupe su propio espacio */
        }

        /* -------------------------------------------------------- */
        /* ESTILOS ESPECÍFICOS PARA LA TABLA DE INFORMACIÓN (MATRICULA, ASEGURADO, ETC.) */
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

        /* -------------------------------------------------------- */
        /* ESTILOS PARA LA TERCERA TABLA (LISTADO DE PRODUCTOS) */
        .data-table-products {
            border: 1px solid #000; /* Borde exterior de la tabla */
            font-size: 12px;
            margin-bottom: 2px; /* Espacio entre esta tabla y la nota */
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
            font-size: 12px; /* Tamaño de fuente para las etiquetas de firma */
            position: relative; /* Necesario para posicionar la línea horizontal */
        }

        /* Línea horizontal para la firma */
        .signatures-table td::before {
            content: '';
            position: absolute;
            bottom: 1px; /* Aumenta este valor para subir la línea de firma. Prueba con 50px o más. */
            left: 15%; /* Inicia la línea más hacia la izquierda dentro de la celda */
            right: 15%; /* Termina la línea más hacia la derecha dentro de la celda */
            border-bottom: 1px solid #000; /* Estilo de la línea */
            width: 70%; /* Ancho de la línea (100 - left - right) */
            margin: 0 auto; /* Centra la línea dentro del espacio disponible */
        }

        /* Cabeceras estrechas con texto VERTICAL */
        .th-vertical {
            width: 12px;              /* ajusta a gusto */
            text-align: center;
            vertical-align: bottom;
        }

        .th-vertical span {
            display: inline-block;
            writing-mode: vertical-rl;   /* intenta usar escritura vertical */
            transform: rotate(180deg);   /* fallback cuando writing-mode no funcione */
            white-space: nowrap;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header" style="text-align:center;">
        <h2>INFORME DIARIO DE CONSULTA EXTERNA 2025</h2> 
    </div>
    <div class="subheader">Dra. Poner nombre</div>
    <div class="subheader">SERVICIO DE GINECOLOGÍA</div>
    <div class="subheader">HOSPITAL SEGURO SOCIAL UNIVERSITARIO LA PAZ - BOLIVIA</div>
    <div class="subheader">Fecha: {{ \Carbon\Carbon::parse($formulario->created_at)->format('d/m/Y') }}</div>

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Hora</th>
                <th>Matricula</th>
                <th>Nombre Completo</th>

                <th>Edad</th>
                <th>Género</th>
                <th>Tipo de Asegurado</th>
                <th>Institución</th>

                <!-- <th>ID Consulta</th>  -->

                @foreach($variables as $var)
                    <th>{{ $var->especialidadVariableVariable->nombre }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach($matriz as $i => $fila)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>hora</td>
                    <td>{{ $fila['matricula'] }}</td>

                    <td>{{ $fila['paciente'] }}</td>

                    <td style="text-align:center;">{{ $fila['edad'] }}</td>
                    <td style="text-align:center;">{{ $fila['sexo'] }}</td>
                    <td>tipo de asegurado</td>
                    <td>institucion</td>

                    <!-- <td>{{ $fila['consulta'] }}</td> -->

                    @foreach($variables as $var)
                        <td>{{ $fila['var_'.$var->id] ?? '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>


    <br><br>
    <div>
        <strong>Creado por usuario ID:</strong> {{ $formulario->id_user_created }}<br>
        <strong>Actualizado por usuario ID:</strong> {{ $formulario->id_user_updated }}
    </div>
</body>
</html>

