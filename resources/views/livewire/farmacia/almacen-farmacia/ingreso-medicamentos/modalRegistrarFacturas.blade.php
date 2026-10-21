<div>
    <x-agregar-button data-toggle="modal" data-target="#modalAgregarFactura"> AGREGAR </x-agregar-button>

    <x-ver-modal id="modalAgregarFactura" labelledby="modalAgregarFacturaLabel"
        title="REGISTRO DE FACTURA O NOTA DE REMISIÓN">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <p style="color:#2471a3" class="h5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em class="ml-2"><b>Registre los datos de la(s) factura(s) o nota(s) de remisión.</b> </em>
                    <spam style="color:#cb4335;"><b>*</b></spam>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <label for="nota_remision" class="d-flex align-items-center gap-1">
                    <span>TIPO:</span>
                    <span style="color:#cb4335;"><b>*</b></span>
                </label>

                <div class="col-sm-10 d-flex align-items-center">
                    <label class="mr-2 text-blue">FACTURA</label>
                    <div
                        class="custom-control custom-switch custom-switch-off-blue custom-switch-on-success @error('nota_remision') is-invalid @enderror">
                        <input wire:model="nota_remision" name="nota_remision" type="checkbox"
                            class="custom-control-input" id="customSwitch1">
                        <label class="custom-control-label" for="customSwitch1"
                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                    </div>
                    <label class="ml-2 text-success">NOTA DE REMISIÓN</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <label for="nro_factura">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-hash" viewBox="0 0 16 16">
                        <path
                            d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                    </svg>
                    Nº :<spam style="color:#cb4335;"><b>*</b></spam></label>
                <input class="form-control input-comun  @error('nro_factura') is-invalid @enderror" type="number"
                    id="nro_factura" wire:model="nro_factura" placeholder="Introduzca el número de la factura">
                @error('nro_factura')
                    <div class="text-danger">
                        <b>El campo numero factura es obligatorio.</b>
                    </div>
                @enderror
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                <label for="fecha_factura">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                        <path
                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                    </svg>
                    FECHA : <spam style="color:#cb4335;"><b>*</b></spam>
                </label>
                <input class="form-control input-comun  @error('fecha_factura') is-invalid @enderror" id="fecha_factura"
                    wire:model="fecha_factura" type="date" max="{{ date('Y-m-d') }}">
                @error('fecha_factura')
                    <div class="text-danger">
                        <b>El campo fecha factura es obligatorio.</b>
                    </div>
                @enderror
            </div>

            <div class="col-lg-2 col-md-12 col-sm-12 col-12">
                <label for="monto_total_factura">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-cash-coin" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0" />
                        <path
                            d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z" />
                        <path
                            d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z" />
                        <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567" />
                    </svg>
                    TOTAL : <spam style="color:#cb4335;"><b>*</b></spam></label>
                <input class="form-control input-comun" id="monto_total_factura" wire:model="monto_total_factura"
                    type="number" placeholder="Introduzca el total">
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12 col-12 text-center">
                <br>
                <x-agregar-button onclick="agregarFactura()">REGISTRAR </x-agregar-button>
            </div>


        </div>


        <script>
            function agregarFactura() {
                Swal.fire({
                    title: `¿Está seguro de registrar la factura?`,
                    text: "Se registrarán los datos ingresados",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, agregar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('agregarFactura');
                    }
                });
            }

            document.addEventListener('facturaAgregadaExitosamente', function() {
                $('#modalAgregarFactura').modal('hide');
                Swal.fire({
                    title: "Registro correcto",
                    text: "El registro fue realizado exitosamente.",
                    icon: "success",
                })
            });

            document.addEventListener('nroFacturaYaExiste', function() {
                Swal.fire({
                    title: "<b>El número de factura ya existe</b>",
                    text: "El número de factura introducido ya se registró anteriormente",
                    icon: "warning",
                })
            });

            document.addEventListener('sinNumeroFactura', function() {
                Swal.fire({
                    title: "<b>El campo NÚMERO FACTURA es obligatorio</b>",
                    text: "Verifique e intente nuevamente",
                    icon: "warning",
                })
            });

            document.addEventListener('sinFechaFactura', function() {
                Swal.fire({
                    title: "<b>El campo FECHA FACTURA es obligatorio</b>",
                    text: "Verifique e intente nuevamente",
                    icon: "warning",
                })
            });

            document.addEventListener('sinTotalFactura', function() {
                Swal.fire({
                    title: "<b>El campo TOTAL FACTURA es obligatorio</b>",
                    text: "Verifique e intente nuevamente",
                    icon: "warning",
                })
            });

            document.addEventListener('valorNroFacturaNoValido', function() {
                Swal.fire({
                    title: "<b>El valor de NRO FACTURA no es válido</b>",
                    text: "Verifique e intente nuevamente",
                    icon: "warning",
                })
            });

            document.addEventListener('valorMontoTotalFacturaNoValido', function() {
                Swal.fire({
                    title: "<b>El valor del MONTO TOTAL DE FACTURA no es válido</b>",
                    text: "Verifique e intente nuevamente",
                    icon: "warning",
                })
            });
        </script>
    </x-ver-modal>

</div>
