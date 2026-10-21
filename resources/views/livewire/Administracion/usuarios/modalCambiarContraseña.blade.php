<x-big-component id="editarContraseñaModal" labelledby="editarContraseñaModalLabel" title=" CAMBIAR CONTRASEÑA " saveAction="">
    
</x-big-component>

<script>
    window.addEventListener('modalCambiarContraseña', event => {
        Swal.fire({
            title: '¿Resetear contraseña?',
            text: "Se generará una nueva contraseña para el usuarioSeleccionado!!!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, generar',
            cancelButtonText: 'No, cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('resetear');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
        // $('#editarContraseñaModal').modal('show');
    });
</script>