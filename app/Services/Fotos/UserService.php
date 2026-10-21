<?php

namespace App\Services\Fotos;
// app/Services/Fotos/ImagenService.php
use App\Models\User;
use App\Services\Fotos\ImagenService;
use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class UserService
{
    protected $imagenService;

    public function __construct(ImagenService $imagenService)
    {
        $this->imagenService = $imagenService;
    }
    public function obtener_imagen_usuario($id_user)
    {
        $user = User::find($id_user);
        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }
        $nombre_imagen = $user->imagen;
        $direccion_foto = 'Administracion/Usuarios/Perfil/';
        return $this->imagenService->obtener_imagen($nombre_imagen, $direccion_foto);
    }
    public function url_imagen_usuario($id_user)
    {
        $imagenUrl = URL::temporarySignedRoute(
            'obtener_imagen_usuario',        // Nombre de la ruta que devuelve la imagen
            now()->addMinutes(90),     // Duración de la validez (10 minutos aquí)
            ['id_user' => $id_user]   // Parámetros que necesita la ruta
        );
        return $imagenUrl;
        // return url('/api/administracion/obtener_imagen_usuario/' . $id_user);
    } 
}