<div>
      
    <div class="row">
        <div class="col-lg-7 col-md-7 col-sm-7 col-12" style="text-align:left;">
                <p><b style="font-size:20px;"> ESPECIALIDAD:</b>  <b class="texto-azul">  {{$nombre_especialidad}} </b></p> 
        </div> 
    </div>
    <br>
    @livewire('atencion-medica.especialidad-variable.esp-var-table')

    <style>
        .texto-azul {
            background-color: #D6EAF8; /* azul */
            color: #2980B9; /* texto blanco para contraste */
            border-radius: 8px;
            display: inline-block; /* se adapta al tamaño del texto */
            padding: 4px 8px; /* espacio interno para que se vea mejor */
            font-size:20px;
        } 

    </style>
</div>

<script>
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

    document.addEventListener('eliminarVariable', function() {
        Swal.fire({
            title: "Eliminado!",
            text: "Se elimino correctamente la variable de la especialidad.",
            icon: "success",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        }).then(() => { 
            $("#registerVariableEsp").modal("show");
        });
    });

    document.addEventListener('estadoFalse', function() {
        Swal.fire({
            title: "¡Variable con estado inactivo!",
            text: "No se agrego la variable a la especialidad.",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        }).then(() => { 
            $("#registerVariableEsp").modal("show");
        });
    });

    document.addEventListener('variableDuplicada', function() {
        Swal.fire({
            title: "¡La variable ya fue registrada!",
            text: "No se agrego la variable a la especialidad.",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        }).then(() => { 
            $("#registerVariableEsp").modal("show");
        });
    });
</script>
