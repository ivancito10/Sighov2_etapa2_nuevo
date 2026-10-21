@extends('principal')

@section('contenido')
    @can('menu.agenda_ecografia_etapa2')
        <livewire:imagenologia.agenda-ecografia.agenda-ecografia-main />
    @else
        <x-acceso-denegado />
    @endcan
@stop
