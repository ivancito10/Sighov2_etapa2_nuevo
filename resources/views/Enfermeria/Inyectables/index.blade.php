@extends('principal')

@section('contenido')

@can('menu.enfermeria')
    @livewire('enfermeria.inyectables.inyectables-main')
@else
    <p style="color: red; font-size: 25px;">
        <em><b>No tienes permisos para acceder a esta sección.</b></em>
    </p>
@endcan

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('evento'))
            let evento = "{{ session('evento') }}";
            if (evento === 'InyeccionRegistrada') {
                Swal.fire({
                    title: "¡Guardado!",
                    text: "La atención de enfermería se registró exitosamente.",
                    icon: "success"
                });
            } else if (evento === 'InyeccionCancelada') {
                Swal.fire({
                    title: "Cancelado",
                    text: "Se canceló el registro.",
                    icon: "error"
                });
            }
        @endif
    });
</script>
@stop