<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImagenProxyController extends Controller
{
    public function mostrar(Request $request)
    {   //dd('llega');
        //Log::info('imagen.proxy hit', ['u' => $request->query('u')]);
        $u = $request->query('u');
        if (!$u) abort(400, 'Missing url');

        $parts = parse_url($u);
        if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
            abort(400, 'Invalid url');
        }

        // servicios interno
        $allowedHosts = [
            '172.16.0.246', //produccion etapa2
            '192.168.2.56', //servicios prueba
            '192.168.2.102', //servicios desarrollo
        ];

        if (!in_array($parts['host'], $allowedHosts, true)) {
            abort(403, 'Host not allowed');
        }

        $path  = $parts['path']  ?? '/';
        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
        $url   = $parts['scheme'].'://'.$parts['host']
               . (isset($parts['port']) ? ':' . $parts['port'] : '')
               . $path . $query;

        $resp = Http::timeout(15)->get($url);

        if (!$resp->ok()) abort(404);

        $contentType = $resp->header('Content-Type', 'image/jpeg');

        return response($resp->body(), 200)
            ->header('Content-Type', $contentType)
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
