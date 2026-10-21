<div>   

    <div class="input-group pr-4" style="justify-content: space-between; ">
            <h4 style="color:black;">ATENCIÓN MÉDICA - <b> LISTA DE VARIABLES</b></h4> 
                <div class="d-flex"> 
                    <div style="margin-left: 7px;">
                        <x-agregar-button style=" padding: 5px 10px;"  wire:click="boton_agregar"> AGREGAR </x-agregar-button>
                    </div> 
                </div> 
        </div>
    <br>

    @livewire('atencion-medica.variables.variable-table')
    @include('livewire.atencion-medica.variables.modalAgregarVariable')

    @include('livewire.atencion-medica.variables.modalVerVariable')
    @include('livewire.atencion-medica.variables.modalEditarVariable')
</div>

<script>
    document.addEventListener('alertaInactivo', function() {
        Swal.fire({
            title: "¡Deshabilitado!",
            text: "El estado esta deshabilitado.",
            icon: "success", 
        });
    });

    document.addEventListener('alertaActivo', function() {
        Swal.fire({
            title: "¡Habilitado!",
            text: "El estado esta  habilitado.",
            icon: "success", 
        });
    });

    document.addEventListener('modificadoVariable', function() {
        Swal.fire({
            title: "¡Actualizado!",
            text: "Se modifico el registro.",
            icon: "success",
        }); 
        $("#editarVariable").modal("hide");
    });

    document.addEventListener('variable_duplicada', function() {
        Swal.fire({
            title: "¡Duplicado!",
            text: "Ya existe un registro con esas características. Verifique el la tabla de registros",
            icon: "warning",
        }); 
        $("#editarVariable").modal("hide");
    });

    document.addEventListener('faltaDatos', function() {
        Swal.fire({
            title: "¡Datos faltantes!",
            text: "Faltan datos para el registro.",
            icon: "warning",
        }); 
        $("#editarVariable").modal("hide");
    });
</script>