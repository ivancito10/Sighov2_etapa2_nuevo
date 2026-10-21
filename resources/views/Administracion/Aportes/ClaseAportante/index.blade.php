@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('super-admin-only')
@livewire('administracion.aportes.clase-aportante.clase-aportante-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan
@stop
