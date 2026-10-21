@extends('principal')

@section('contenido')
    @can('menu.liquidacion-agendas')
        @livewire('liquidaciones.agendas.agendas-main')
    @endcan
@stop
