@extends('principal')

@section('contenido')
    @can('menu.liquidacion-laboratorios')
        @livewire('liquidaciones.laboratorio.laboratorio-main')
    @endcan
@stop
