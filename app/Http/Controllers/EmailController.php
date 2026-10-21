<?php

namespace App\Http\Controllers;

use App\Mail\CorreoMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class EmailController extends Controller
{
    //
    public function envioEmail(Request $request)
    {
        //
        $datos = [
            'nombre0' => "nombre falso0",
            'nombre1' => "nombre falso2",
            'nombre3' => "nombre falso3",
            'nombre4' => "nombre falso4",
            'nombre5' => "nombre falso5",
            'nombre6' => "nombre falso6",
            'nombre7' => "nombre falso7",
        ];
        $credentials = $request->only('email', 'asunto');
        $asunto = $credentials['asunto'];
        $email = $credentials['email'];
        $correo = new CorreoMailable($datos, $asunto);
        try {
           // Mail::to($email)->send($correo);
        } catch (\Exception $e) {
            // Log del error (opcional)
            Log::error('No se pudo enviar el correo: ' . $e->getMessage());
        }
        
        return response()->json(
            [
                'estado' => "correo enviado a  $email",
                'asunto' => $asunto,
                'data' => $datos
            ],
            200
        );
    }
}
