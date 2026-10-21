<x-small-modal id="cieEditar" labelledby="cieEditarLabel" title="EDITAR CIE"
    saveAction="guardarCieEdit()">
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
    document.addEventListener('cieEditar', function() {
        $('#cieEditar').modal('show');
    });

    function guardarCieEdit() {
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
                Livewire.dispatch('editarCie');
                $('#cieEditar').modal('hide');
            }
        });
    }
    document.addEventListener('cieEditada', function() {
        Swal.fire({
            title: "¡CIE Modificada!",
            text: "CIE fue modificada exitosamente",
            icon: "success",
            timer: 1200,
            showConfirmButton: false,
        });

    });
    document.addEventListener('cieVacia', function() {
        Swal.fire({
            title: "¡Sin Datos!",
            text: "Ingrese la descripcion del CIE",
            icon: "warning",
            timer: 1200,
            showConfirmButton: true,
        });
    });
</script>
