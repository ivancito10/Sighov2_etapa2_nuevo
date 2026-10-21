<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
class TokensServices extends Controller
{
    //
    public static function obtener_token($email,$password)
    {
        $token = null;
        $url_service = LinkServicio::where('nombre_servicio', 'like', 'OBTENER TOKEN MICROSERVICIO')->first();
        if($url_service!=null){
            $login = $url_service->link_servicio;
            dd(auth()->user()->name);
            // $credenciales= [
            //     'email' => auth()->user()->name,
            //     'password' => auth()->user()->password
            // ];
            // dd($credenciales);
            // $response = Http::post($login, $credenciales);
            // return $response->json();

        }

        return response()->json(['token'=>$token]);
    }
}
