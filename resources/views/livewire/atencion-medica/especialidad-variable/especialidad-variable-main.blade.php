<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 style="color:black;">ADMINISTRACIÓN - <b> LISTA DE ESPECIALIDADES - VARIABLES</b></h4>
        </div>
    </div>
    <br>
    @livewire('atencion-medica.especialidad-variable.especialidad-variable-table')
    
    @include('livewire.atencion-medica.especialidad-variable.modalRegisterVariable')
    @include ('livewire.atencion-medica.especialidad-variable.modalVerEspVar')

     
</div>
<script>
    document.addEventListener('guardadoEspVar', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se guardó correctamente el registro.",
            icon: "success",
        }); 
        $("#registerVariableEsp").modal("hide");
    });


    document.addEventListener('agregadoVariable', function() {
        Swal.fire({
            title: "Agregado!",
            text: "Se agregó correctamente la variable a la especialidad.",
            icon: "success",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        }).then(() => { 
            $("#registerVariableEsp").modal("show");
        });
    });
</script>
