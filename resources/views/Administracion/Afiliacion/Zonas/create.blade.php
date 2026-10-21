@extends('principal')

@section('contenido')

@can('registro.zonas')
@livewire('Administracion.afiliacion.zonas.zonas-register')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
      esta sección.</b></em></p>
@endcan
@stop