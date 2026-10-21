@extends('principal')

@section('contenido')

@can('super-admin-only')
@livewire('administracion.aportes.validar-persona.validar-persona-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

@stop
