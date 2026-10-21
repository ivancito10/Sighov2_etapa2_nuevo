<!DOCTYPE html>
<html>
<head>
    <title>Mi Correo</title>
</head>
<body>
    <h1>Hola, esto es un ejemplo de correo desde Laravel.</h1>
    <h2>
        {{$asunto}}
    </h2>
    <p>¡Espero que funcione correctamente!</p>
    <ul>
        @foreach ($datos as $key => $nombre)
            <li>{{ $key }}: {{ $nombre }}</li>
        @endforeach
    </ul>
</body>
</html>
