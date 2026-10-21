<?php

namespace App\Services;

use App\Models\Administracion\LinkServicio;
use Illuminate\Support\Facades\Http;

class ServicioPersona
{
    /**
     * Realiza una petición HTTP genérica a un servicio externo.
     *
     * @param  string  $nombreServicio  Nombre en la BD (ej: 'OBTENER PERSONA FARMACIA')
     * @param  string  $metodo  HTTP method: 'get', 'post', 'put', 'delete'
     * @param  string|int|null  $urlParam  Parámetro para concatenar a la URL (como el ID)
     * @param  array  $data  Datos para el body (en POST/PUT)
     * @return mixed
     */
    protected function ejecutarPeticionServicio(string $nombreServicio, string $metodo = 'get', $urlParam = null, array $data = [])
    {
        // 1. Manejo de Token
        $authTokenService = new ObtenerToken();
        $authTokenService->refresh_token();

        // 2. Buscar el servicio en la BD
        $link_servicio = LinkServicio::where('nombre_servicio', $nombreServicio)->first();

        if (! $link_servicio) {
            return [
                'status' => 'error',
                'message' => "El servicio '{$nombreServicio}' no está configurado.",
            ];
        }

        // Construir URL final
        $urlCompleta = $link_servicio->link_servicio.$urlParam;
        $metodo = strtolower($metodo);

        // 3. Ejecutar la petición según el método HTTP de forma dinámica
        // Http::withToken(...)->get($url, $data) o post($url, $data), etc.
        $response = Http::withToken(session('access_token'))->$metodo($urlCompleta, $data);

        // 4. Procesar la respuesta
        if ($response->successful()) {
            return $response->json()['data'] ?? null;
        } else {
            $jsonResponse = $response->json();

            return [
                'status' => $jsonResponse['status'] ?? $response->status(),
                'message' => $jsonResponse['message'] ?? 'Error al consumir el servicio.',
                'params' => ['urlParam' => $urlParam, 'data' => $data],
                'error' => $jsonResponse['error'] ?? null,
            ];
        }
    }
}
