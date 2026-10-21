<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h4 style="color:black;">ADMINISTRACIÓN POR ESPECIALIDAD  - <b> VISTA LABORATORIO </b></h4>
        </div> 
         
    </div> 
    <br>
     
    @livewire('Administracion.administracion-vistas-laboratorio.administracion-vistas-laboratorio-table')
    
</div>

<script>
    document.addEventListener('preguntarCambiodeVista', function() {
        Swal.fire({
            title: '<b>Cambiar de vista?</b>',
            text: "La vista de laboratorios del especialista cambiará",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#008f39',
            cancelButtonColor: '#ff0000',
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarAdmVista');
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Se canceló la entrega",
                    icon: "warning",
                    confirmButtonColor: '#008f39',
                });
            }
        });
    });

    document.addEventListener('cambioExitoso', function() {
        Swal.fire({
            title: '<b>Cambio realizado exitosamente</b>',
            text: "El cambio se realizó correctamente",
            icon: 'success',
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
    });

    document.addEventListener('sinRegistroDeEspecialista', function() {
        Swal.fire({
            title: '<b>Cambiar de vista?</b>',
            text: "La vista de laboratorios del especialista cambiará",
            icon: 'warning',
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
    });
</script>