@extends('principal')

@section('contenido')
<!-- Contenido específico de la vista Index -->

@can('menu.usuarios')
@livewire('administracion.usuarios.usuarios-main')
@else

<p style="color: red;font-size: 25px;"><em><b>No tienes permisos para
            acceder a
            esta sección.</b></em></p>
@endcan
<script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'UsuarioCreado') {
                        Swal.fire({
                            title: "Guardado!",
                            text: "Los datos se han guardado exitosamente.",
                            icon: "success"
                        });
                } else if (evento === 'UsuarioCreadoCancelado') {
                    Swal.fire({
                        title: "Cancelo el registro!",
                        text: "Se cancelo el registro",
                        icon: "error"

                    });
                }
            @endif
        });
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('evento'))
                let evento = "{{ session('evento') }}";
                if (evento === 'UsuarioCreadoError') {
                        Swal.fire({
                            title: "Alerta!",
                            text: "Se produjo un error interno.",
                            icon: "warning"
                        });
                } 
            @endif
        });

        
    </script>
@stop
