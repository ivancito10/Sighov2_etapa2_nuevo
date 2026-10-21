<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use App\Services\ObtenerToken;

class EspecialidadService
{
    public function obtenerEspecialidades(): Collection
    {   
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link = LinkServicio::where('nombre_servicio','LISTAR ESPECIALIDADES')->pluck('link_servicio')->first();
        $token = session('access_token', '');

        $response = Http::withToken($token)->get($link);

        $espList = $response->json('data') ?? [];
        //dd($espList);
        return collect($espList)->pluck('especialidad', 'id');
    }

    public function obtenerEspecialistas(): Collection
    {   
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();
        $link = LinkServicio::where('nombre_servicio','LISTAR ESPECIALISTAS')->pluck('link_servicio')->first();
        $token = session('access_token', '');

        $response = Http::withToken($token)->get($link);
        //dd($response);
        $espList = $response->json('data') ?? [];
        //dd($espList[0]);
        return collect($espList);
    }
}
