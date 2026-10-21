<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Administracion\UserSession;
use App\Models\Administracion\Comunicado;
use App\Models\Administracion\Imagen;
use App\Models\Administracion\LinkServicio;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    { 
        $hoy = Carbon::today();
        $comunicados = Comunicado::where('estado', true)
            ->where(function ($query) use ($hoy) {
                $query->where('permanente', true)
                    ->orWhere(function ($subquery) use ($hoy) {
                        $subquery->where('permanente', false)
                            ->whereDate('fecha_inicio', '<=', $hoy)
                            ->whereDate('fecha_fin', '>=', $hoy);
                    });
            })
            ->orderBy('id', 'desc')
            ->get();

        $imagenes = Imagen::where('estado', true)
                ->pluck('nombre_imagen'); 
        $fotos = [];
              
        foreach ($imagenes as $img) {
            $rutaFTP = 'Administracion/Login/Imagenes/' . $img;

            if (Storage::disk('ftp')->exists($rutaFTP)) {
                $contenido = Storage::disk('ftp')->get($rutaFTP);
                $mime = 'image/' . pathinfo($img, PATHINFO_EXTENSION);
                $fotos[] = 'data:' . $mime . ';base64,' . base64_encode($contenido);
            }
        } 

        return view('auth.login', [
            'comunicados' => $comunicados,
            'fotos' => $fotos
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Respaldar credenciales antes de la autenticación
        $identificador = $request->input('usuario') ?? $request->input('email') ?? $request->input('username');
        $passwordPlano = $request->input('password');

        // 2. Autenticación nativa de Laravel
        $request->authenticate();

        // 3. Generar token de sesión y auditoría interna
        $sessionToken = Str::random(60);
        $request->user()->update([
            'session_token' => $sessionToken, 
            'last_login_at' => Carbon::now()
        ]);

        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');
        $deviceName = gethostbyaddr($ipAddress);

        UserSession::create([
            'user_id'       => $request->user()->id,
            'session_token' => $sessionToken,
            'ip_address'    => $ipAddress,
            'device_name'   => $deviceName,
            'user_agent'    => $userAgent,
            'login_at'      => now(),
        ]);

        $request->session()->put('session_token', $sessionToken);
        $request->session()->regenerate();

        // 4. Obtener el JWT de NestJS bajo su propia clave aislada
        $request->session()->forget('nest_access_token');

        try {
            $respNest = Http::timeout(5)
                ->acceptJson()
                ->post($this->nestBaseUrl() . '/auth/login', [
                    'username' => $identificador,
                    'password' => $passwordPlano,
                ]);

            $jwtToken = $respNest->successful() ? $respNest->json('accessToken') : null;

            if ($jwtToken) {
                $request->session()->put('nest_access_token', $jwtToken);
            } else {
                Log::warning('NestJS Auth sin token', [
                    'status'  => $respNest->status(),
                    'usuario' => $identificador,
                ]);
                $request->session()->flash(
                    'warning',
                    'No se pudo conectar con el servicio de pacientes. Algunos datos no estarán disponibles.'
                );
            }
        } catch (\Throwable $e) {
            Log::error('NestJS Auth: error de conexión: ' . $e->getMessage());
            $request->session()->flash('warning', 'Servicio de pacientes no disponible.');
        }

        // 5. Emitir socket y redirigir
        $this->emitir_socket($request->user()->id);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Helper para normalizar la URL de NestJS
     */
    private function nestBaseUrl(): string
    {
        $url = rtrim(config('services.nestjs.url', 'http://localhost:3000/api'), '/');
        return str_ends_with($url, '/api') ? $url : $url . '/api';
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            UserSession::where('user_id', $user->id)
                ->where('session_token', session('session_token'))
                ->update(['logout_at' => now()]);
        }

        // Limpiar el JWT específico de NestJS
        session()->forget('nest_access_token');

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function emitir_socket($id_user)
    {
        $servicio = LinkServicio::where('estado', true)
            ->where('nombre_servicio', 'SERVER_SOCKET_IO')
            ->first();

        if ($servicio) {
            $ruta = $servicio->link_servicio;
            $user = User::where('id', $id_user)->first();

            $id_socket = $user?->socket_id;
            if ($id_socket) {
                Http::post($ruta . '/control_session', [
                    'id_socket' => $id_socket,
                ]);
            }
        }
    }

    /**
     * Destroy an authenticated session.
     */
    
}