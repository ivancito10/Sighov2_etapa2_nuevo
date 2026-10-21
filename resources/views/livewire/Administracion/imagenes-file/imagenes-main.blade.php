<div>
     
    
        <div class="input-group pr-4" style="justify-content: space-between; ">
            <h4 style="color:#212f3c ;">   ADMINISTRACIÓN - <b> LISTA DE IMÁGENES </b></h4>
            <!-- Aqui va el boto agregar-->
            <x-agregar-button style=" padding: 5px 10px;" wire:click="registrar_imagen"  title="REGISTRAR IMAGEN">
                <b>REGISTRAR</b></x-agregar-button>

        </div>
        <br>
    

        <div class="row">
             
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                 
                @livewire('administracion.imagenes-file.imagen-table')

                @include('livewire.Administracion.imagenes-file.modalRegistrarImagen')
                @include('livewire.Administracion.imagenes-file.modalVerImagen')
                 
             
            </div> 
        </div>
 
        
        <div wire:loading>
            <x-cargando-dos/>
        </div>

       
</div>
   
<script>
    document.addEventListener('livewire:init', () => {
        // Escucha el evento emitido desde PHP
        Livewire.on('deshabilitar_imagen', () => {
            Swal.fire({
                title: '¿Deshabilitar imagen?',
                text: "La imagen será deshabilitada.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, ahora disparamos evento a PHP
                    Livewire.dispatch('confirmar_estado');
                }
            });
        });
    });

    document.addEventListener('livewire:init', () => {
        // Escucha el evento emitido desde PHP
        Livewire.on('habilitar_imagen', () => {
            Swal.fire({
                title: '¿Habilitar imagen?',
                text: "La imagen será habilitada.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, habilitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, ahora disparamos evento a PHP
                    Livewire.dispatch('confirmar_estado');
                }
            });
        });
    });
    document.addEventListener('estado_deshabilitado', function() {
        Swal.fire({
            title: "Deshabilitado!",
            text: "Se modifico el estado del registro.",
            icon: "success"

        });

    });
    document.addEventListener('estado_habilitado', function() {
        Swal.fire({
            title: "Habilitado!",
            text: "Se modifico el estado del registro.",
            icon: "success"

        });

    });
</script>


