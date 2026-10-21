@extends('principal')

@section('contenido')
    @can('menu.liquidacion-rayosx')
        @livewire('liquidaciones.rayos-x.rayos-x-main')
    @endcan
@stop
