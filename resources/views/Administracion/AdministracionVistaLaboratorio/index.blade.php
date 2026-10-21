@extends('principal')

@section('contenido')
    @can('super-admin-only')
        @livewire('Administracion.administracion-vistas-laboratorio.administracion-vistas-laboratorio-main')
    @else
        <acceso-denegado></acceso-denegado>
    @endcan
@stop
