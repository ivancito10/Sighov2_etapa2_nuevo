<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DOCUMENTO CONFIDENCIAL - USUARIO Y CONTRASEÑA</title>
        <style>
        body {

            margin: 0px 0px;
            background-color: #f4f4f4;
            color: #333;
            background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;
        }

        .document-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            /* Habilita posicionamiento relativo para el contenedor */
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Times New Roman', Times, serif
        }

        .document-content {
            font-size: 13px;
            line-height: 1, 1;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
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
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            right: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            left: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }
    </style>
    </head>

    <body>
        <div class="document-container">
            <img src="logo/logo2.png" class="logo-right" width="120" height="60"
                alt="Logo">
            <img src="logo/ssu.png" class="logo-left" width="65" height="65"
                alt="Logo">
            <div class="document-title">DOCUMENTO CONFIDENCIAL <br>RECORDATORIO
                DE USUARIO Y CONTRASEÑA</div>
            <div class="document-content">
                <p style="text-align: justify;">Este documento es un
                    <b>recordatorio</b> de los datos de usuario y contraseña que
                    le han sido asignados anteriormente para acceder al sistema
                    <b>SIGHO V2 del Seguro Social Universitario</b>. El usuario
                    <span
                        class="highlight">{{$datos_usuario_registro->personaUser->nombres}}
                        {{$datos_usuario_registro->personaUser->p_apellido}}
                        {{$datos_usuario_registro->personaUser->s_apellido}}</span>,
                    con número de C.I. <span
                        class="highlight">{{$datos_usuario_registro->personaUser->ci}}
                        {{$datos_usuario_registro->personaUser->complemento}}</span>,
                    ya cuenta con estas credenciales. A continuación, se
                    detallan nuevamente sus datos de acceso:</p>

                <table align="center">
                    <tr>
                        <td align="center">
                            <p style="font-size: 25px; text-align: center;"
                                align="center">
                                <em><span style="font-size: 15px;">Usuario:
                                    </span><b>{{$datos_usuario_registro->email}}</b></em><br>
                                <span style="font-size: 25px;"><span
                                        style="font-size: 15px;">Contraseña:
                                    </span>********</span>
                            </p>
                        </td>
                        <td align="right"> <img
                                src="data:image/png;base64,{{ $qrCode }}"
                                alt="Código QR"></td>
                    </tr>
                </table>

                <p style="text-align: justify;">Recuerde que esta información es
                    <b>estrictamente confidencial y de uso exclusivo</b>. Le
                    pedimos que siga manejando estos datos con la máxima
                    cautela, ya que han sido proporcionados directamente por el
                    Seguro Social Universitario La Paz para su acceso
                    personal.</p>

                <p class="recommendation" style="text-align: justify;">Por
                    motivos de seguridad, le reiteramos la recomendación de
                    cambiar su contraseña si aún no lo ha hecho, asegurándose de
                    que cumpla con nuestras políticas de seguridad. Use una
                    combinación de números, letras mayúsculas y minúsculas.
                    Guarde este documento de manera segura o elimínelo después
                    de su consulta para proteger su información.</p>

                <p style="text-align: justify;">Agradecemos su colaboración en
                    el tratamiento confidencial de esta información para
                    garantizar la seguridad de sus datos y la integridad del
                    sistema.</p>
            </div>
        </div>

        <!-- Instrucciones adicionales del segundo ejemplo -->
        <div class="interlineado">
            <i
                class="subrayado"><b>{{$datos_usuario_registro->personaUser->nombres}}
                    {{$datos_usuario_registro->personaUser->p_apellido}}
                    {{$datos_usuario_registro->personaUser->s_apellido}}</b></i>
            <br><br>
            <b>CI:{{$datos_usuario_registro->personaUser->ci}}
                {{$datos_usuario_registro->personaUser->complemento}}</b>
        </div>

        <div style="font-size: 10px; text-align:right;">
            <label>GENERADO EL: <b>{{$fecha}}</b></label>
            <label>- POR: <b>{{$datos_usuario_modificador->name}}</b></label>
        </div>
    </body>

</html>