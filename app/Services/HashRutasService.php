<?php

namespace App\Services;

// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
class HashRutasService
{
    public function url_hash($id, string $ruta, string $nombreParametro = 'id')
    {
        // dd($id, $ruta);
        $imagenUrl = URL::temporarySignedRoute(
            $ruta,        // Nombre de la ruta que devuelve la imagen
            now()->addMinutes(90),     // Duración de la validez (10 minutos aquí)
            [$nombreParametro => $id]   // Parámetros que necesita la ruta
        );
        return $imagenUrl;
        // return url('/api/administracion/obtener_imagen_usuario/' . $id_user);
    }
}