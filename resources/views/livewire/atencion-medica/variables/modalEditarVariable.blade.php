<div>
    <x-big-component id="editarVariable" labelledby="editarVariableLabel" title="MODIFICAR VARIABLE"
    saveAction="actualizarDato()">

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
                    <em class="ml-2"><b>Ingrese y actualice los siguientes campos:</b><span style="color:#cb4335;"><b>*</b></span> </em>

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
                 
            </div>

            <div class="col-lg-4 col-md-5 col-sm-6 col-12">
                <label for="tipo">  TIPO: <span style="color:#cb4335;"><b>*</b></span></label>
                <select wire:model="tipo" name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" style="border-radius:8px;">
                    <option value=" " disabled>Seleccione una opción</option>
                    <option value="integer">NÚMERO ENTERO</option>
                    <option value="decimal">NÚMERO DECIMAL</option> 
                    <option value="varchar">TEXTO</option> 
                    <option value="boolean">VERDADERO / FALSO</option> 
                     
                </select>  
                 
            </div> 
        </div>
    </x-big-component>
</div>


<script>
    window.addEventListener('modalEditarVariable', event => {
        $('#editarVariable').modal('show');
    });

    function actualizarDato() {
        Swal.fire({
            title: '¿Modificar registro?',
            text: "Se modificarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, modificar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) { 
                Livewire.dispatch('modificarVaribleAsignada');
            }
        });
    }
 
</script>