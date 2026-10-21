@extends('principal')

@section('contenido')
@can('super-admin-only')

@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para
            acceder a
            esta sección.</b></em></p>
@endcan

@stop
    
