<x-small-modal id="cieRegister" labelledby="cieRegisterLabel" title="NUEVO CIE"
    saveAction="guardarCie()">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes datos.</b>
                </em>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label for="codigo">Codigo del nuevo CIE:<span
                    style="color:#cb4335;"><b>*</b></span></label>
            <input class="form-control @error('codigo') is-invalid @enderror" name="codigo" id="codigo"
                wire:model="codigo" placeholder="Ingrese el codigo del nuevo CIE..." rows="5"
                oninput="this.value = this.value.toUpperCase()">
            @error('codigo')
                <div class="text-danger">
                    <b>Este campo es obligatorio</b>
                </div>
            @enderror

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label for="descripcion">Descripción del nuevo CIE:<span
                    style="color:#cb4335;"><b>*</b></span></label>
            <textarea class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion"
                wire:model="descripcion" placeholder="Ingrese la descripción del nuevo CIE..." rows="5"
                oninput="this.value = this.value.toUpperCase()"></textarea>
            @error('descripcion')
                <div class="text-danger">
                    <b>Este campo es obligatorio</b>
                </div>
            @enderror

        </div>
    </div>
</x-small-modal>
<script>
    document.addEventListener('cieRegister', function() {
        $('#cieRegister').modal('show');
    });

    function guardarCie() {
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
                // $('#cieRegister').modal('hide');
                Livewire.dispatch('crearNuevaCie');
            }
        });
    }
    document.addEventListener('CieCreada', function() {
        $('#cieRegister').modal('hide');
        Swal.fire({
            title: "CIE registada!",
            text: "CIE fue registrado exitosamente",
            icon: "success",
            timer: 1000,
            showConfirmButton: false,
        });

    });
    document.addEventListener('CieVacia', function() {
        $('#cieRegister').modal('hide');
        Swal.fire({
            title: "¡Faltan Datos!",
            text: "Ingrese la descripcion de la nueva Funcion",
            icon: "warning",
            timer: 1000,
            showConfirmButton: true,
        });
    });
    document.addEventListener('CieCodigoRepetida', function() {
        Swal.fire({
            title: "CIE DUPLICADA!",
            text: "Ya existe una Funcion con la misma descripcion ingresada.",
            icon: "warning",
            timer: 1200,
            showConfirmButton: false,
        });
        $('#cieRegister').modal('hide');
    });
    document.addEventListener('CieDescripcionRepetida', function() {
        Swal.fire({
            title: "CIE DUPLICADA!",
            text: "Ya existe una Funcion con la misma descripcion ingresada.",
            icon: "warning",
            timer: 1200,
            showConfirmButton: false,
        });
        $('#cieRegister').modal('hide');
    });
</script>
