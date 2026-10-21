@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('menu.zonas')
@livewire('administracion.afiliacion.zonas.zonas-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para acceder a
            esta sección.</b></em></p>
@endcan

<div>

</div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'zonaCreada') {
                    Swal.fire({
                        title: "Guardado!",
                        text: "Los datos se han guardado exitosamente.",
                        icon: "success"
                    });
                } else if (evento === 'zonaCancelada') {
                    Swal.fire({
                        title: "Se canceló la operación",
                        text: "Se cancelo el registro",
                        icon: "error"
                    });
                }
            @endif
        });
    </script>
@stop
