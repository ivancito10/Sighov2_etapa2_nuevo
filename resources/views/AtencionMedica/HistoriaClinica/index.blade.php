@extends('principal')

@section('contenido')
    {{-- @livewire('atencion-medica.consulta-externa.historia-clinica.historia-clinica-main') --}}
    @livewire('atencion-medica.consulta-externa.historia-clinica.historia-clinica-main', [
        'datos_agenda' => $datos_agenda,
    ])

@stop
