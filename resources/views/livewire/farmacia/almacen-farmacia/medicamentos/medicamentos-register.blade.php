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
                <em>Complete la información correspondiente al medicamento:</em>
                <span>*</span>
            </legend>

            <div class="row">
                <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                    <label for="cod_medicamento">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        CÓDIGO: <span style="color:#cb4335;"><b>*</b></span></label>
                    <input type="text"
                        class="form-control @error('cod_medicamento') is-invalid @enderror input-comun"
                        placeholder=" Código del medicamento" name="cod_medicamento" id="cod_medicamento" maxlength="8"
                        wire:model=cod_medicamento oninput="this.value = this.value.toUpperCase().slice(0,8)">
                    @error('cod_medicamento')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-lg-9 col-md-12 col-sm-12 col-12">
                    <label for="medicamento">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-prescription2" viewBox="0 0 16 16">
                            <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                            <path
                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                        </svg>
                        MEDICAMENTO: <span style="color:#cb4335;"><b>*</b></span></label>
                    <input type="text" class="form-control @error('medicamento') is-invalid @enderror input-comun"
                        placeholder=" Medicamento" name="medicamento" id="medicamento" wire:model="medicamento">
                    @error('medicamento')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <label for="forma_farmaceutica">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-capsule" viewBox="0 0 16 16">
                            <path
                                d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                        </svg>
                        FORMA FARMACÉUTICA: <span style="color:#cb4335;"><b>*</b></span></label>
                    <input type="text"
                        class="form-control @error('forma_farmaceutica') is-invalid @enderror input-comun"
                        placeholder=" Forma farmacéutica" name="forma_farmaceutica" id="forma_farmaceutica"
                        wire:model=forma_farmaceutica>
                    @error('forma_farmaceutica')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>

                <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                    <label for="concentracion">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-bandaid-fill" viewBox="0 0 16 16">
                            <path
                                d="m2.68 7.676 6.49-6.504a4 4 0 0 1 5.66 5.653l-1.477 1.529-5.006 5.006-1.523 1.472a4 4 0 0 1-5.653-5.66l.001-.002 1.505-1.492.001-.002Zm5.71-2.858a.5.5 0 1 0-.708.707.5.5 0 0 0 .707-.707ZM6.974 6.939a.5.5 0 1 0-.707-.707.5.5 0 0 0 .707.707M5.56 8.354a.5.5 0 1 0-.707-.708.5.5 0 0 0 .707.708m2.828 2.828a.5.5 0 1 0-.707-.707.5.5 0 0 0 .707.707m1.414-2.121a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707m1.414-.707a.5.5 0 1 0-.706-.708.5.5 0 0 0 .707.708Zm-4.242.707a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707m1.414-.707a.5.5 0 1 0-.707-.708.5.5 0 0 0 .707.708m1.414-2.122a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707M8.646 3.354l4 4 .708-.708-4-4zm-1.292 9.292-4-4-.708.708 4 4z" />
                        </svg>
                        CONCENTRACIÓN: <span style="color:#cb4335;"><b>*</b></span></label>
                    <input type="text" class="form-control @error('concentracion') is-invalid @enderror input-comun"
                        placeholder=" Concentración" name="concentracion" id="concentracion" wire:model=concentracion>
                    @error('concentracion')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-12" wire:ignore>
                    <label for="id_cuenta_cont">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-coin" viewBox="0 0 16 16">
                            <path
                                d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z" />
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                            <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12" />
                        </svg>
                        CUENTA CONTABLE: <span style="color:#cb4335;"><b>*</b></span></label>
                    <select id="id_cuenta_cont" class="form-control" title="Selecciona una opción...">
                        <option value="" disabled selected>Selecciona una cuenta contable...</option>

                        @forelse ($datos as $dato)
                            <option value="{{ $dato['id'] }}">{{ $dato['nro_cuenta'] }} - {{ $dato['nombre'] }}
                            </option>
                        @empty
                            <option disabled selected>No hay cuentas contables disponibles</option>
                        @endforelse
                    </select>
                    @error('id_cuenta_cont')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12 col-12">
                    <label for="customSwitch1reg" class="d-flex align-items-center gap-1">
                        <span>RESTRINGIDO:</span>
                        <span style="color:#cb4335;"><b>*</b></span>
                    </label>

                    <div class="col-sm-10 d-flex align-items-center">
                        <label class="mr-2 text-danger">NO</label>
                        <div
                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success @error('restringido') is-invalid @enderror">
                            <input wire:model="restringido" name="restringido_reg" type="checkbox"
                                class="custom-control-input" id="customSwitch1reg">
                            <label class="custom-control-label" for="customSwitch1reg"
                                style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                        </div>
                        <label class="ml-2 text-success">SI</label>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <label for="stock_minimo_almacen">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        STOCK MÍNIMO EN ALMACEN: <span style="color:#cb4335;"><b>*</b></span></label>
                    <input type="number"
                        class="form-control @error('stock_minimo_almacen') is-invalid @enderror input-comun"
                        placeholder=" Código del medicamento" name="stock_minimo_almacen" id="stock_minimo_almacen"
                        wire:model=stock_minimo_almacen step="1" min="0">
                    @error('stock_minimo_almacen')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </fieldset>
    </div>
    
    <div style="height: 0.5rem;"></div>
    <div class="modal-footer pt-0" style="border: none; background-color: white;">
        <x-cancel-button data-dismiss="modal" wire:click="cancelarRegistroMedicamento">CANCELAR</x-cancel-button>
        <x-save-button wire:loading.attr="disabled" wire:click="preguntarGuardarMedicamento">GUARDAR</x-save-button>
    </div>

    <div wire:loading wire:target="preguntarGuardarMedicamento, cancelarRegistroMedicamento">
        <x-cargando-dos />
    </div>

    <div id="cargando-guardar-medicamento" style="display: none;">
        <x-cargando-dos />
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('id_cuenta_cont');

        const cuentasSelect = new Choices(el, {
            searchEnabled: true,
            itemSelectText: 'Presiona para seleccionar',
            shouldSort: false
        });

        el.addEventListener('addItem', function(event) {
            const props = event.detail && event.detail.customProperties;
            @this.set('id_cuenta_cont', event.detail.value);
        });


        window.addEventListener('medicamento_registrado_g', () => {
            cuentasSelect.removeActiveItems();
            Swal.fire({
                title: "Guardado!",
                text: "El medicamento se registro exitosamente.",
                icon: "success"
            });
        });
    });

    document.addEventListener('preguntaGuardar', function() {
        Swal.fire({
            title: '¿Guardar medicamento?',
            text: "Los datos del medicamento serán registrados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarMedicamento');
                document.getElementById('cargando-guardar-medicamento').style.display =
                    'block';
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Usted canceló el registro.",
                    icon: "warning"
                });
                Livewire.dispatch('cancelarRegistroMedicamento');
                $('#registroMedicamentoModal').modal('hide');
            }
        });
    });

    document.addEventListener('medicamento_incompleto', function() {
        Swal.fire({
            title: "Datos incompletos!",
            text: "Debe completar todos los campos.",
            icon: "warning"
        });
    });

    document.addEventListener('sinCodMedicamento', function() {
        Swal.fire({
            title: "El campo CÓDIGO MEDICAMENTO es obligatorio",
            text: "Debe completar todos los campos",
            icon: "warning"
        });
    });

    document.addEventListener('sinMedicamento', function() {
        Swal.fire({
            title: "El campo MEDICAMENTO es obligatorio",
            text: "Debe completar todos los campos",
            icon: "warning"
        });
    });

    document.addEventListener('sinFormaFarmaceutica', function() {
        Swal.fire({
            title: "El campo FORMA FARMACÉUTICA es obligatorio",
            text: "Debe completar todos los campos",
            icon: "warning"
        });
    });

    document.addEventListener('sinConcentracion', function() {
        Swal.fire({
            title: "El campo CONCENTRACIÓN es obligatorio",
            text: "Debe completar todos los campos",
            icon: "warning"
        });
    });

    document.addEventListener('sinCuentaContable', function() {
        Swal.fire({
            title: "Seleccione una CUENTA CONTABLE",
            text: "Debe completar todos los campos",
            icon: "warning"
        });
    });

    document.addEventListener('medicamento_existe', function() {
        Swal.fire({
            title: "Duplicado!",
            text: "El código del medicamento ya existe, registre uno nuevo por favor",
            icon: "warning"
        });
    });

    document.addEventListener('medicamento_error', function() {
        Swal.fire({
            title: "Error!",
            text: "Ocurrió un error, verifique el problema.",
            icon: "error"
        });
    });
</script>
