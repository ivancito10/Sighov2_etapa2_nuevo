<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOCUMENTO CONFIDENCIAL - CAMBIO DE CONTRASEÑA</title>
    <style>
        body {

            margin: 10px 5px;
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
        <img src="logo/logo2.png" class="logo-right" width="120" height="60" alt="Logo">
        <img src="logo/ssu.png" class="logo-left" width="65" height="65" alt="Logo">
        <div class="document-title">DOCUMENTO CONFIDENCIAL <br>CAMBIO DE CONTRASEÑA</div>
        <div class="document-content">
            <p style="text-align: justify;">A solicitud del interesado, se ha procedido a resetear la contraseña del
                usuario <span class="highlight">{{$datos_usuario_registro->personaUser->nombres}}
                    {{$datos_usuario_registro->personaUser->p_apellido}}
                    {{$datos_usuario_registro->personaUser->s_apellido}}</span>, con número de C.I. <span
                    class="highlight">{{$datos_usuario_registro->personaUser->ci}}
                    {{$datos_usuario_registro->personaUser->complemento}}</span>. La nueva contraseña ha sido generada
                automáticamente por nuestro sistema. su nueva contraseña es la siguiente:</p>

            <table align="center">
                <tr>
                    <td align="center">
                        <span style="font-size: 25px;text-align: center;" align="center"><em><b>{{$password}} </b></em><br>
                            <span style="font-size: 11px;">Usuario: {{$datos_usuario_registro->email}}</span>
                        </span>
                    </td>
                    <td align="right"> <img  src="data:image/png;base64,{{ $qrCode }}" alt="Código QR"></td>
                </tr>
            </table>



            <p style="text-align: justify;">Es fundamental que esta información <b>sea manejada con el más alto nivel de
                    confidencialidad.</b> Le recomendamos que cambie esta contraseña temporal por una de su elección lo
                antes
                posible y que cumpla con las políticas de seguridad establecidas.</p>
            <p class="recommendation" style="text-align: justify;">Por motivos de seguridad, se sugiere que este
                documento sea resguardado en un lugar seguro o eliminado después de su uso. Utilice una combinación de
                números, letras mayúsculas y minúsculas para su nueva contraseña para garantizar su seguridad.</p>
        </div>

    </div>
    <!-- Instrucciones adicionales del segundo ejemplo -->
    <div class="interlineado">
        <i class="subrayado"><b>{{$datos_usuario_registro->personaUser->nombres}}
                {{$datos_usuario_registro->personaUser->p_apellido}}
                {{$datos_usuario_registro->personaUser->s_apellido}}</b></i>
        <br><br>
        <b>CI:{{$datos_usuario_registro->personaUser->ci}} {{$datos_usuario_registro->personaUser->complemento}}</b>
    </div>

    <div style="font-size: 10px; text-align:right;">
        <label>GENERADO EL: <b>{{$fecha}}</b></label>
        <label>- POR: <b>{{$datos_usuario_modificador->name}}</b></label>
    </div>
</body>

</html>