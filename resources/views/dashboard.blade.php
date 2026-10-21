@extends('adminlte::page')

@section('title', 'SIGHO V2')

@section('content_header')
    <div
        style="
        background: linear-gradient(to right, #f0f4f8,rgb(214, 214, 221));
        padding: 20px 20px;
        border-radius: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 10000px;
        margin: 0 auto;
    ">
        <h1 style="font-size: 2.2em; margin-bottom: 10px;">
            <span style="color: #1f2937;">Bienvenido al</span><br>
            <strong style="color: #2471a3;">Seguro Social Universitario</strong>
            <span style="color: #cb4335;">La Paz</span>
        </h1>
        <p style="color: #6b7280; font-size: 1em; margin-top: 10px;">
            Sistema de Gestión Hospitalaria <strong>SIGHOv2</strong>
        </p>
    </div>

@stop


@section('content')
    <!-- <p>Welcome to this beautiful admin panel.</p>-->

    @livewire('administracion.notificaciones.notificaciones-detalles')
    @livewire('administracion.dashboard.dashboard-estadisticas')

    @livewire('administracion.cambio-contrasenia.cambio-contrasenia-inicio')
     


    @persist('player')
        <audio src="{{ asset('sound/BienvenidaSistema.mp3') }}" autoplay></audio>
    @endpersist

    @include('numeros_emergencia')

@stop
@section('content_top_nav_left') 
    <!-- Botón Atención Médica --> 
    @can('menu.consulta_externa')
        <li class="nav-item me-3">
            <a href="{{ route('atencion_medica.consulta_externa.index') }}" class="nav-link" title="CONSULTA EXTERNA">
                <!-- <i class="fas fa-stethoscope text-primary fa-2x"></i> -->
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#0044A3" class="bi bi-calendar2-heart" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm2 .5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V4a.5.5 0 0 0-.5-.5zm5 4.493c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                    </svg>
            </a>
            
        </li>
    @endcan
    
@endsection

@section('content_top_nav_right') 
    {{-- @livewire('administracion.notificaciones.notificaciones-main') --}}

@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/botonesStyles.css') }}">
    {{-- Add here extra stylesheets --}} 
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(async function(OneSignal) {
            await OneSignal.init({
                appId: "ed0394bd-c7ad-488d-a461-4125ccbb5411",
                //   appId: "dd52755f-15c1-4bac-9863-984174a3a0fc",

            });
        });
    </script>
    {{-- <script src="{{ asset('js/bloqueos.js') }}"></script> --}}
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('botonAgendamientoWeb');
            if (btn) {
                btn.addEventListener('click', function(e) {
                    btn.classList.add('disabled');
                    btn.style.pointerEvents = 'none';
                    btn.style.opacity = '0.7';
                });
            }
        });
    </script>
@stop
