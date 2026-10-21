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
                <em> Modificar los siguientes campos del medicamento:</em>
                <span>*</span>
            </legend>
            <div class="row">
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                    <label for="codigo_medicamento_edit"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>CÓDIGO: <spam style="color:#cb4335;"><b>*</b></spam>
                    </label>
                    <input type="text"
                        class="form-control @error('codigo_medicamento') is-invalid @enderror input-comun"
                        placeholder=" Código del medicamento" name="codigo_medicamento_edit"
                        id="codigo_medicamento_edit" wire:model=codigo_medicamento
                        oninput="this.value = this.value.toUpperCase().slice(0,8)" maxlength="8">
                    @error('codigo_medicamento')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                    <label for="nombre_edit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-prescription2" viewBox="0 0 16 16">
                            <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                            <path
                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                        </svg>MEDICAMENTO: </b></spam></label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror input-comun"
                        placeholder=" nombre" name="nombre_edit" id="nombre_edit" wire:model="nombre">
                    @error('nombre')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label for="forma_farmaceutica_edit">FORMA FARMACÉUTICA: <spam style="color:#cb4335;"><b>*</b>
                        </spam>
                    </label>
                    <input type="text"
                        class="form-control @error('forma_farmaceutica') is-invalid @enderror input-comun"
                        placeholder=" Forma farmacéutica" name="forma_farmaceutica_edit" id="forma_farmaceutica_edit"
                        wire:model=forma_farmaceutica>
                    @error('forma_farmaceutica')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <label for="concentracion_edit">CONCENTRACIÓN: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <input type="text" class="form-control @error('concentracion') is-invalid @enderror input-comun "
                        placeholder=" Concentración" name="concentracion_edit" id="concentracion_edit"
                        wire:model=concentracion>
                    @error('concentracion')
                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12" wire:ignore>
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

                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <label for="customSwitch2">RESTRINGIDO: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <label class="mr-2">NO</label>
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input wire:model="restringido" name="restringido_edit" type="checkbox"
                                class="custom-control-input" id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2"
                                style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                        </div>
                        <label class="ml-2">SI</label>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <label for="stock_minimo_almacen_edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        STOCK MÍNIMO EN ALMACEN: <span style="color:#cb4335;"><b>*</b></span></label>
                    <input type="number"
                        class="form-control @error('stock_minimo_almacen') is-invalid @enderror input-comun"
                        placeholder=" Código del medicamento" name="stock_minimo_almacen_edit"
                        id="stock_minimo_almacen_edit" wire:model=stock_minimo_almacen step="1" min="0">
                    @error('stock_minimo_almacen')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </fieldset>



    </div>


    <div class="modal-footer pt-0"
        style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
        <x-cancel-button data-dismiss="modal" wire:click="cancelarEdicionMedicamento">CANCELAR</x-cancel-button>
        <x-save-button wire:loading.attr="disabled" wire:click="preguntarEditarMedicamento">GUARDAR</x-save-button>
    </div>

    <div wire:loading wire:target="preguntarEditarMedicamento, cancelarEdicionMedicamento">
        <x-cargando-dos />
    </div>

    <div id="cargando-guardar-medicamento-editar" style="display: none;">
        <x-cargando-dos />
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
<script>
    let cuentasSelect = null;

    document.addEventListener('DOMContentLoaded', function() {

        const el = document.getElementById('id_cuenta_contable_edit');

        // Inicializar Choices SOLO si el elemento existe
        if (el) {
            cuentasSelect = new Choices(el, {
                searchEnabled: true,

                itemSelectText: 'Presiona para seleccionar',
                shouldSort: false
            });

            el.addEventListener('addItem', function(event) {
                @this.set('id_cuenta_contable', event.detail.value);
            });
        }
        
    });

    document.addEventListener('cargarIdCuentaContable', function(event) {
        const id = String(event.detail.id);

        if (cuentasSelect) {
            cuentasSelect.setChoiceByValue(id);
        }
    });

    document.addEventListener('preguntaGuardarEditar', function() {
        Swal.fire({
            title: '¿Editar medicamento?',
            text: "Los nuevos datos del medicamento serán guardados.",
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
                Livewire.dispatch('guardarEdicionMedicamento');
                document.getElementById('cargando-guardar-medicamento-editar').style.display = 'block';
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Se canceló la edición.",
                    icon: "warning"
                });
                Livewire.dispatch('cancelarEdicionMedicamento');
                $('#modalEditarMedicamentoModal').modal('hide');
            }
        });
    });

    document.addEventListener('medicamento_error', function() {
        Swal.fire({
            title: "Error!",
            text: "Ocurrió un error, verifique el problema.",
            icon: "error",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

</script>
