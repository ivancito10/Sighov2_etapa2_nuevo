<div>
    <x-modal-sin-footer id="modalRegistrarCierreAlmacenGeneral" labelledby="modalRegistrarCierreAlmacenGeneralLabel" title="ALMACEN GENERAL - CIERRE DE GESTIÓN">

        @livewire('almacen-general.cierre-gestion.cierre-gestion-nuevo-almacen')
    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('registrar_cierre', function() {
        $('#modalRegistrarCierreAlmacenGeneral').modal('show');
    });

    /*document.addEventListener('registrar_cierre_cerrar', function() {
        $('#modalRegistrarCierreAlmacenGeneral').modal('hide');
    });*/
    /*  document.addEventListener('registrar_cierre', function () {
         $('#modalRegistrarCierre').modal('show');
          $('#registrarCertificado').css('overflow-y', 'auto');
     }); */

    /*  function guardar_cierre_gestion() {
         Swal.fire({
             title: '¿Guardar registro?',
             text: "Los datos ingresados seran registrados.",
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             confirmButtonText: 'Sí, guardar',
             cancelButtonText: 'No, cancelar'
         }).then((result) => {
             if (result.isConfirmed) {
                 Livewire.dispatch('guardar_cierre_almacen');

             } else {
                 Swal.fire({
                     title: "Cancelado!",
                     text: "Usted canceló el registro.",
                     icon: "warning"
                 });
                 $('#modalRegistrarCierre').modal('hide');
             }
         });
     }
     document.addEventListener('cierre_gestion_almacen_registrado', function () {
         Swal.fire({
             title: "Guardado!",
             text: "El medicamento se registro exitosamente.",
             icon: "success"
         });
         $('#modalRegistrarCierre').modal('hide');
     });

     document.addEventListener('complete_datos', function () {
         Swal.fire({
             title: "Datos incompletos!",
             text: "Debe completar todos los campos.",
             icon: "warning"
         });
     });
     document.addEventListener('cierre_error', function () {
         Swal.fire({
             title: "Error!",
             text: "Ocurrió un error, verifique el problema.",
             icon: "error"
         });
     });
     document.addEventListener('hay_registro_almacen', function () {
         Swal.fire({
             title: "Ya existe un registro!",
             text: "No se registro, existe un registro anterior.",
             icon: "warning"
         });
         $('#modalRegistrarCierre').modal('show');
     }); */
</script>
