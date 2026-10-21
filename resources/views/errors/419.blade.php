@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.'))

@section('content')
    <div style="text-align: center; padding: 20px;">
        <!-- Botón de redirección al login con estilo inline -->
        <a href="{{ route('login') }}" style="display: inline-block; padding: 12px 25px; background-color: #007bff; color: white; border-radius: 8px; text-decoration: none; font-size: 16px; transition: background-color 0.3s;">
            Ir al Login
        </a>
    </div>

    <!-- Añadir un efecto hover -->
    <style>
        a:hover {
            background-color: #0056b3;
        }
    </style>

@endsection
