<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Prueba Formal de Posicionamiento PDF</title>
    <style>
        /* 1. Reseteamos los márgenes físicos para que obedezca el absoluto */
        @page {
            margin: 0mm;
        }

        body {
            margin: 0mm;
            padding: 0mm;
            font-family: 'Times New Roman', serif;
        }

        .dinamic-wrapper {
            position: absolute;
            top: {{ $marginTop ?? 15 }}mm;
            left: {{ $marginLeft ?? 15 }}mm;

            /* Volvemos al ancho completo para que ocupe toda la hoja */
            width: 185mm;
            box-sizing: border-box;
        }

        @page {
            margin: 0mm;
        }

        body {
            margin: 0mm;
            padding: 0mm;
            font-family: 'Times New Roman', serif;
            background-color: #ffffff;
        }

        /* 2. Contenedor dinámico controlado por el modal */
        .dinamic-wrapper {
            position: absolute;
            /* Si no se envían las variables, por defecto usará 15mm */
            top: {{ $marginTop ?? 15 }}mm;
            left: {{ $marginLeft ?? 15 }}mm;
            width: 185mm;
            box-sizing: border-box;
        }

        .document-container {
            margin: 0;
            padding: 5mm;
            background-color: white;
            box-sizing: border-box;
        }

        /* Estilos de tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 11px;
        }

        th {
            background-color: #f2f2f2;
            border: 1px solid #383a3b;
            padding: 5px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #383a3b;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        /* Encabezado simulado libre de bordes */
        .header-table {
            border: none;
            margin-bottom: 15px;
        }

        .header-table td {
            border: none !important;
            padding: 0;
        }

        .titulo-reporte {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 10px;
        }

        /* Sección de Firmas */
        .signatures-table {
            margin-top: 30px;
            border: none;
        }

        .signatures-table td {
            border: none !important;
            text-align: center;
            vertical-align: bottom;
            font-size: 11px;
            position: relative;
            padding-top: 40px;
        }

        /* Línea de firma simulada */
        .signatures-table td::before {
            content: '';
            position: absolute;
            top: 35px;
            left: 20%;
            right: 20%;
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body>

    <div class="dinamic-wrapper">
        <div class="document-container">

            <table class="header-table">
                <tr>
                    <td style="width: 50%;">
                        <strong>INSTITUCIÓN DE SALUD S.U.</strong><br>
                        <small>Unidad de Consulta Externa</small>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <strong>Código Seg:</strong> CE-2026-9981<br>
                        <small>Fecha Emisión: {{ now()->format('d/m/Y H:i') }}</small>
                    </td>
                </tr>
            </table>

            <div class="titulo-reporte">FORMULARIO DE ATENCIÓN MÉDICA (PRUEBA)</div>

            <table>
                <thead>
                    <tr>
                        <th colspan="4">I. DATOS DE FILIACIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 25%;"><strong>Asegurado:</strong></td>
                        <td style="width: 25%;">PÉREZ GÓMEZ JUAN</td>
                        <td style="width: 25%;"><strong>Matrícula:</strong></td>
                        <td style="width: 25%;">84-0512-PGJ</td>
                    </tr>
                    <tr>
                        <td><strong>Edad / Sexo:</strong></td>
                        <td>42 Años / Masculino</td>
                        <td><strong>Especialista:</strong></td>
                        <td>{{ $nombre_especialista ?? 'Dr. Eleazar Jhonny Cruz R.' }}</td>
                    </tr>
                </tbody>
            </table>

            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Código CIE-10</th>
                        <th style="width: 80%;">Descripción Diagnóstico</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>I10</strong></td>
                        <td>Hipertensión esencial (primaria)</td>
                    </tr>
                    <tr>
                        <td><strong>E11.9</strong></td>
                        <td>Diabetes mellitus no insulinodependiente sin mención de complicación</td>
                    </tr>
                </tbody>
            </table>

            <table>
                <thead>
                    <tr>
                        <th>II. PLANES Y TRATAMIENTO SUGERIDO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="height: 50px;">
                            Se indica continuar con el tratamiento antihipertensivo regular. Restricción estricta de
                            sodio en la dieta, control de glucemias capilares en ayunas y reevaluación clínica en el
                            lapso de 15 días calendario.
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="signatures-table">
                <tr>
                    <td style="width: 50%;">
                        Firma del Especialista Médico<br>
                        <small>S.S.U. La Paz</small>
                    </td>
                    <td style="width: 50%;">
                        Firma / Huella Digital del Asegurado
                    </td>
                </tr>
            </table>

        </div>
    </div>

</body>

</html>
