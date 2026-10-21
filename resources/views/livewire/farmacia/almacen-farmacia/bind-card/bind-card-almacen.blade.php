<div class='pb-5'>

    <div class="card-borde card-comun p-2 ">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-8">
                <div class="cabecera">
                    <b>DETALLE DEL MEDICAMENTO:</b>
                </div>
            </div>
        </div>
        <div style="height: 0.5rem;"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="table-responsive">
                    <table class="table tabla-ploma">
                        <tr>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                                        <path d="M2 2h2v2H2z" />
                                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                                        <path
                                            d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                                    </svg>

                                    CÓDIGO :</strong>
                            </td>
                            <td>{{ $codigo_medicamento }}</td>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-capsule" viewBox="0 0 16 16">
                                        <path
                                            d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                                    </svg>
                                    MEDICAMENTO:</strong>
                            </td>
                            <td>{{ $nombre_medicamento }}</td>

                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-prescription2" viewBox="0 0 16 16">
                                        <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                                        <path
                                            d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                                    </svg>
                                    FORMA FARMACÉUTICA:</strong>
                            </td>
                            <td>{{ $forma_farmaceutica }}</td>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-beaker-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M15.575.006a.5.5 0 0 1 .327.79l-.048.058-.122.12A2.5 2.5 0 0 0 15 2.743V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2.742a2.5 2.5 0 0 0-.566-1.584L.268.975.146.854A.5.5 0 0 1 .5 0h15zM11.5 13a.5.5 0 1 0 0 1H13v-1zm-2-2a.5.5 0 0 0 0 1H13v-1zm2-2a.5.5 0 1 0 0 1H13V9zm-2-2a.5.5 0 1 0 0 1H13V7zm2-2a.5.5 0 1 0 0 1H13V5zm-2-2a.5.5 0 1 0 0 1H13V3z" />
                                    </svg>
                                    CONCENTRACIÓN:</strong>
                            </td>
                            <td>{{ $concentracion }}</td>

                        </tr>
                        <!--   <tr>
                                <td colspan="4" style="text-align: right; white-space: nowrap; font-size:20px">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-sort-numeric-up-alt" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.36 7.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.836 1.973-1.836 1.09 0 2.063.637 2.063 2.688 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98" />
                                            <path
                                                d="M12.438 8.668V14H11.39V9.684h-.051l-1.211.859v-.969l1.262-.906h1.046zM4.5 13.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                                        </svg>
                                        STOCK TOTAL:
                                    </strong>

                                    <b>{{ number_format($stockAlmacen, 0) }} UNIDADES</b>
                                </td>
                            </tr> -->

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 0.5rem;"></div>
    <div class="card-borde card-comun p-2 ">
        <fieldset class="field_estilos">
            <legend>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <em>Ingrese y complete los campos para generar el BindCard:</em>
                <span>*</span>
            </legend>
            <div class="row">


                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <label for="fecha_inicio"> FECHA INICIO: <span style="color:#cb4335;"><b>*</b></span>
                    </label>
                    <input class="form-control input-comun  @error('fecha_inicio') is-invalid @enderror"
                        id="fecha_inicio" wire:model.live="fecha_inicio" type="date">
                    @error('fecha_inicio')
                        <div class="text-danger">
                            <b> {{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <label for="fecha_fin"> FECHA FIN: <span style="color:#cb4335;"><b>*</b></span>
                    </label>
                    <input class="form-control input-comun  @error('fecha_fin') is-invalid @enderror" id="fecha_fin"
                        wire:model.live="fecha_fin" type="date">
                    @error('fecha_fin')
                        <div class="text-danger">
                            <b> {{ $message }}</b>
                        </div>
                    @enderror
                </div>
            </div>
            <br>
            <div class="d-flex justify-content-end">
                <x-pdf2-button class="mr-2" wire:click="generarPDF_BincardFarmacia">Generar
                    pdf</x-pdf2-button>
                <x-ver-excel-button wire:click="generarExcel_BincardFarmaciaAlmacen">Generar
                    Excel</x-ver-pdf-button>
            </div>

        </fieldset>
    </div>

</div>


</div>
<script>
    document.addEventListener('fechasNoValidas', function() {
        Swal.fire({
            title: "Fechas No Validas!",
            text: "Verifique el rango de fechas introducidas.",
            icon: "warning"
        });
    });

    document.addEventListener('sinCodigoMed', function() {
        Swal.fire({
            title: "Medicamento No Encontrado!",
            text: "El código del medicamento no se encuentra registrado.",
            icon: "warning"
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#id_medicamento');
        $(select).select2();
        $('.select2-selection').css('height', '40px');
        $('.select2-selection__rendered').css('line-height', '30px');

        $(select).on('select2:open', function() {
            setTimeout(function() {
                document.querySelector('.select2-search__field').focus();
            }, 100);
        });

        $('#id_medicamento').on('change', function(e) {
            var data = $(this).val();
            @this.set('id_medicamento', data);
            Livewire.dispatch('medicamentoSeleccionado');
        });
    });
</script>
