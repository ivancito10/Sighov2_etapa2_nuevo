@extends('principal')

@section('contenido')

@livewire('administracion.unidades.unidades-main')

<div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'unidadCreada') {
                    Swal.fire({
                        title: "Guardado!",
                        text: "Los datos se han guardado exitosamente.",
                        icon: "success"
                    });
                } else if (evento === 'unidadCancelada') {
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
    
