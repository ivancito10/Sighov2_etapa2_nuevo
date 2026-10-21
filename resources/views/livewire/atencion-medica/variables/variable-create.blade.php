<div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align:left;">
                <p style="color:#2471a3" class="h5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em class="ml-2"><b>Ingrese y seleccione las siguientes campos:</b><span style="color:#cb4335;"><b>*</b></span> </em>

                </p>
            </div>
        </div>
        <br>
       
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <label for="nombre">  NOMBRE: <span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control @error('nombre') is-invalid @enderror" 
                id="nombre"
                wire:model="nombre"
                style="border-radius:8px; "
                placeholder="Ingrese el nombre de la variable"
                type="text" oninput="this.value = this.value.toUpperCase()"> 
                @error('nombre')
                    <div class="text-danger">
                        <b>Este campo es obligatorio</b>
                    </div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-5 col-sm-6 col-12">
                <label for="tipo">  TIPO: <span style="color:#cb4335;"><b>*</b></span></label>
                <select wire:model="tipo" name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" style="border-radius:8px;">
                    <option value=" ">Seleccione una opción</option>
                    <option value="integer">NÚMERO ENTERO</option>
                    <option value="decimal">NÚMERO DECIMAL</option> 
                    <option value="varchar">TEXTO</option> 
                    <option value="boolean">VERDADERO / FALSO</option> 
                     
                </select>  
                @error('tipo')
                    <div class="text-danger">
                        <b>Este campo es obligatorio</b>
                    </div>
                @enderror
            </div> 
        </div>
         
     
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:right;">
                <x-cancel-button wire:click="cancelar_registro" title="CANCELAR REGISTRO"> CANCELAR</x-cancel-button>
                <x-save-button onclick="guardarVariable()" title="GUARDAR REGISTRO"> GUARDAR</x-save-button>
            </div>
        </div>
</div>

<script>
    function guardarVariable() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) { 
                Livewire.dispatch('guardarVaribleAsignada');
            }
        });
    }

    document.addEventListener('guardar_variable', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se guardó el registro.",
            icon: "success",
        }); 
        $("#registrarVariable").modal("hide");
    });

    document.addEventListener('variable_duplicada', function() {
        Swal.fire({
            title: "Duplicado!",
            text: "Ya existe un registro con esas características. Verifique el la tabla de registros",
            icon: "warning",
        }); 
        $("#registrarVariable").modal("hide");
    });

    document.addEventListener('livewire:init', () => {
        Livewire.on('cerrarModal', () => {
            $('#registrarVariable').modal('hide');
        });
    });
</script>
