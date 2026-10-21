<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EnfermeriaNestService
{
    protected string $baseUrl;

    public function __construct()
    {
        $url = config('services.nestjs.url', 'http://localhost:3000/api');
        $this->baseUrl = rtrim($url, '/');
        if (!str_ends_with($this->baseUrl, '/api')) {
            $this->baseUrl .= '/api';
        }
    }

    /**
     * Obtiene el token emitido por NestJS almacenado en la sesión
     */
    protected function getToken(): string
    {
        return (string) session('nest_access_token', '');
    }

    public function obtenerPacientePorId(int $idPersona): ?array
    {
        $token = $this->getToken();

        if ($token === '') {
            Log::warning("EnfermeriaNestService: sin token en sesión para paciente #{$idPersona}");
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(5)
                ->get("{$this->baseUrl}/pacientes/{$idPersona}");

            if ($response->status() === 401) {
                // Token inválido o vencido: se invalida para no reintentar en cada fila
                session()->forget('nest_access_token');
                Log::warning('EnfermeriaNestService: 401 de NestJS, token descartado');
                return null;
            }

            if ($response->successful()) {
                $body = $response->json();
                return isset($body['data']) && is_array($body['data']) ? $body['data'] : $body;
            }

            Log::warning("EnfermeriaNestService: HTTP {$response->status()} en paciente #{$idPersona}");
            return null;
        } catch (\Throwable $th) {
            Log::error('EnfermeriaNestService: ' . $th->getMessage());
            return null;
        }
    }

    public function buscarPacientes(?string $termino = '', int $page = 1, int $limit = 20): array
    {
        $token = $this->getToken();

        if ($token === '') {
            return ['data' => [], 'meta' => []];
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(5)
                ->get("{$this->baseUrl}/pacientes", [
                    'q'     => trim($termino ?? ''),
                    'page'  => $page,
                    'limit' => $limit,
                ]);

            if ($response->status() === 401) {
                session()->forget('nest_access_token');
                return ['data' => [], 'meta' => []];
            }

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['data']) ? $data : ['data' => $data, 'meta' => []];
            }

            return ['data' => [], 'meta' => []];
        } catch (\Throwable $th) {
            return ['data' => [], 'meta' => []];
        }
    }

    public function listarEspecialistasHabilitados(): array
    {
        $token = $this->getToken();

        if ($token === '') {
            return [];
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(5)
                ->get("{$this->baseUrl}/s1/plataforma/listar_especialistas_habilitados");

            if ($response->status() === 401) {
                session()->forget('nest_access_token');
                return [];
            }

            if ($response->successful()) {
                $body = $response->json();
                return $body['data'] ?? $body ?? [];
            }

            return [];
        } catch (\Throwable $th) {
            return [];
        }
    }
    /**
     * Obtiene especialistas activos con opción de búsqueda por nombre o especialidad
     */
    public function obtenerEspecialistas(string $termino = '', int $limite = 20): array
    {
        $token = session('nest_access_token');
        if (!$token) {
            $token = $this->obtenerTokenNest();
        }

        try {
            $query = [
                'limite' => $limite,
                'pagina' => 1,
            ];

            if (!empty(trim($termino))) {
                $query['buscar'] = trim($termino);
            }

            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->withoutVerifying()
                ->timeout(10)
                ->get("{$this->baseUrl}/especialistas", $query);

            if ($response->successful()) {
                $body = $response->json();
                return $body['datos'] ?? $body['data'] ?? $body ?? [];
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Error al consultar especialistas en Nest: " . $e->getMessage());
        }

        return [];
    }
}