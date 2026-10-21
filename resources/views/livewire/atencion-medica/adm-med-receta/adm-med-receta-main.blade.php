<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h4 style="color:black;">ADMINISTRACIÓN POR RECETA  - <b> LISTA DE TIPO DE CONSULTA </b></h4>
        </div> 
         
    </div> 
    <br>
     
    @livewire('atencion-medica.adm-med-receta.adm-med-receta-table')
    
    @include('livewire.atencion-medica.adm-med-receta.modalEditarAdmMedReceta')
</div>

<script>
    document.addEventListener('datosModificados',function () {
        $('#modalAdmMedicamentos').modal('hide');
        Swal.fire({
            icon: 'success',
            title: 'Datos modificados exitosamente.',
            text: 'Los datos se modificaron exitosamente.',
            confirmButtonText: 'OK'
        });
    })

    document.addEventListener('error',function () {
        $('#modalAdmMedicamentos').modal('hide');
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Ocurrio un error, comuniquese con el administrador.',
            confirmButtonText: 'OK'
        });
    })
    
    document.addEventListener('DOMContentLoaded', function () {
        window.addEventListener('alerta_cambiar_estado', function () {
            Swal.fire({
                title: '¿Desea cambiar el estado?',
                text: "Se modificará el estado actual.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('funcion_cambia_estado');
                }
            });
        });
    });

    document.addEventListener('estado_cambiado',function () {
        
        Swal.fire({
            icon: 'success',
            title: 'Actualizado!',
            text: 'El estado ha sido actualizado correctamente.',
            confirmButtonText: 'OK'
        });
    })
</script>