<div>
    <link href="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/styles/choices.min.css" rel="stylesheet">
    <div class="card-comun p-2">
        <fieldset class="field_estilos">
            <legend>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <em>Complete la información correspondiente al material o suministro:</em>
                <span>*</span>
            </legend>

            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                    <label for="codigo_registrar">CÓDIGO: <b style="color:#cb4335;">*</b></label>
                    <input id="codigo_registrar" class="form-control @error('codigo') is-invalid @enderror"
                        type="text" wire:model='codigo_material' maxlength="6" placeholder="Introduzca el código"
                        style=" border-radius:8px">
                    @error('codigo_material')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <label for="unidad_registrar">UNIDAD DE MEDIDA: <b style="color:#cb4335;">*</b></label>
                    <select wire:model="unidad" style="border-radius:10px" id="unidad_registrar"
                        class="select2 form-control @error('unidad') is-invalid @enderror">
                        <option value selected=" ">Seleccione ...</option>
                        <option value="BLOCK">BLOCK</option>
                        <option value="BOLSA">BOLSA</option>
                        <option value="CAJA">CAJA</option>
                        <option value="ELIXIR">ELIXIR</option>
                        <option value="FÓRMULA">FÓRMULA</option>
                        <option value="FRASCO">FRASCO</option>
                        <option value="GRAMOS">GRAMOS</option>
                        <option value="HOJA">HOJA</option>
                        <option value="JUEGO">JUEGO</option>
                        <option value="LITRO">LITRO</option>
                        <option value="METROS">METROS</option>
                        <option value="PARES">PARES</option>
                        <option value="PIEZA">PIEZA</option>
                        <option value="ROLLO">ROLLO</option>
                        <option value="TUBOS">TUBOS</option>
                        <option value="NINGUNO">NINGUNO</option>
                    </select>
                    @error('unidad')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="nombre_registrar">NOMBRE GENÉRICO: <b style="color:#cb4335;">*</b></label>
                    <input id="nombre_registrar" class="form-control @error('nombre') is-invalid @enderror"
                        type="text" wire:model='nombre' placeholder="Introduzca el nombre"
                        oninput="this.value = this.value.toUpperCase()" style=" border-radius:8px">
                    @error('nombre')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="descripcion_registrar">DESCRIPCIÓN: <b style="color:#cb4335;">*</b></label>
                    <textarea id="descripcion_registrar" class="form-control @error('descripcion') is-invalid @enderror" type="text"
                        wire:model='descripcion' row="3" placeholder="Introduzca una descripción"
                        oninput="this.value = this.value.toUpperCase()" style=" border-radius:8px"> </textarea>
                    @error('descripcion')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12" wire:ignore>
                    <label for="id_cuenta_contable_edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-coin" viewBox="0 0 16 16">
                            <path
                                d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z" />
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                            <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12" />
                        </svg>
                        CUENTA CONTABLE: <span style="color:#cb4335;"><b>*</b></span></label>
                    <select id="id_cuenta_contable_edit" class="form-control" title="Selecciona una opción...">
                        <option value="" disabled selected>Selecciona una cuenta contable...</option>

                        @forelse ($datos as $dato)
                            <option value="{{ $dato['id'] }}">{{ $dato['nro_cuenta'] }} - {{ $dato['nombre'] }}
                            </option>
                        @empty
                            <option disabled selected>No hay cuentas contables disponibles</option>
                        @endforelse
                    </select>
                    @error('id_cuenta_contable')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>
            </div>
        </fieldset>
    </div>
    <div class="modal-footer pt-0"
        style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
        <x-cancel-button data-dismiss="modal" wire:click="cancelarEdicionMaterial">CANCELAR</x-cancel-button>
        <x-save-button wire:loading.attr="disabled" wire:click="preguntarEditarMaterial">GUARDAR</x-save-button>
    </div>

    <div wire:loading wire:target="preguntarEditarMaterial, cancelarEdicionMaterial">
        <x-cargando-dos />
    </div>

    <div id="cargando-guardar-material-editar" style="display: none;">
        <x-cargando-dos />
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('id_cuenta_contable_edit');

        const cuentasSelect = new Choices(el, {
            searchEnabled: true,
            itemSelectText: 'Presiona para seleccionar',
            shouldSort: false
        });

        el.addEventListener('addItem', function(event) {
            const props = event.detail && event.detail.customProperties;
            @this.set('id_cuenta_contable', event.detail.value);
        });


        window.addEventListener('material_registrado_g', () => {
            cuentasSelect.removeActiveItems();
            Swal.fire({
                title: "Guardado!",
                text: "El material se registro exitosamente.",
                icon: "success"
            });
        });

        window.addEventListener('material_array_reset', () => {
            cuentasSelect.removeActiveItems();
        });

        window.addEventListener('cargarIdCuentaContable', function(event) {
            // Nos aseguramos de limpiar cualquier selección previa primero
            cuentasSelect.removeActiveItems();

            // Convertimos a String por si el ID viene como número
            const id = String(event.detail.id);

            // Forzamos a Choices.js a seleccionar el valor de tu registro previo
            cuentasSelect.setChoiceByValue(id);
        });
    });


    document.addEventListener('preguntaGuardarEditar', function() {
        Swal.fire({
            title: '¿Editar material?',
            text: "Los nuevos datos del material serán guardados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, editar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarEdicionMaterial');
                document.getElementById('cargando-guardar-material-editar').style.display = 'block';
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Se canceló la edición.",
                    icon: "warning"
                });
                Livewire.dispatch('cancelarEdicionMaterial');
                $('#modalEditarMaterialModal').modal('hide');
            }
        });
    });

    document.addEventListener('material_error', function() {
        Swal.fire({
            title: "Error!",
            text: "Ocurrió un error, verifique el problema.",
            icon: "error",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('materialEditadoCorrectamente', function() {
        Swal.fire({
            title: "<b>Material editado correctamente</b>",
            text: "Se guardaron los cambios",
            icon: "success"
        });
    });
</script>
