<div>
    <x-ver-modal id="modalVerMedicamento" labelledby="modalVerMedicamentolLabel" title="DETALLE DE MEDICAMENTO">
        <div class="card-borde p-2">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12 col-12">
                    <div class="card cabecera">
                        <b>DATOS DEL MEDICAMENTO</b>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 col-sm-12 col-12 text-right">
                    <strong>RESTRINGIDO: </strong>
                    @if ($restringido == true)
                        <span
                            style="background-color:#1B950E;color:white; padding:5px;border-radius:10px"><b>SI</b></span>
                    @else
                        <span style="background-color:#EB4123;color:white; padding:5px;border-radius:10px">NO</span>
                    @endif

                    </span>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-12 text-left">
                    <strong>
                        ESTADO:
                    </strong>
                    @if ($estado == true)
                        <span
                            style="background-color:#1B950E;color:white; padding:5px;border-radius:10px"><b>ACTIVO</b></span>
                    @else
                        <span
                            style="background-color:#EB4123;color:white; padding:5px;border-radius:10px">INACTIVO</span>
                    @endif
                </div>

                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        CÓDIGO: </strong>
                    <p class="p-comun-disabled p-1">{{ $cod_medicamento }} </p>
                </div>
                <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-prescription2" viewBox="0 0 16 16">
                            <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                            <path
                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                        </svg>
                        MEDICAMENTO: </strong>
                    <p class="p-comun-disabled p-1">{{ $medicamento }} </p>
                </div>

                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-capsule" viewBox="0 0 16 16">
                            <path
                                d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                        </svg>
                        FORMA FARMACÉUTICA: </strong>
                    <p class="p-comun-disabled p-1">{{ $forma_farmaceutica }} </p>
                </div>
                <div class="col-lg-8 col-md-4 col-sm-4 col-4">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-bandaid-fill" viewBox="0 0 16 16">
                            <path
                                d="m2.68 7.676 6.49-6.504a4 4 0 0 1 5.66 5.653l-1.477 1.529-5.006 5.006-1.523 1.472a4 4 0 0 1-5.653-5.66l.001-.002 1.505-1.492.001-.002Zm5.71-2.858a.5.5 0 1 0-.708.707.5.5 0 0 0 .707-.707ZM6.974 6.939a.5.5 0 1 0-.707-.707.5.5 0 0 0 .707.707M5.56 8.354a.5.5 0 1 0-.707-.708.5.5 0 0 0 .707.708m2.828 2.828a.5.5 0 1 0-.707-.707.5.5 0 0 0 .707.707m1.414-2.121a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707m1.414-.707a.5.5 0 1 0-.706-.708.5.5 0 0 0 .707.708Zm-4.242.707a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707m1.414-.707a.5.5 0 1 0-.707-.708.5.5 0 0 0 .707.708m1.414-2.122a.5.5 0 1 0-.707.707.5.5 0 0 0 .707-.707M8.646 3.354l4 4 .708-.708-4-4zm-1.292 9.292-4-4-.708.708 4 4z" />
                        </svg>
                        CONCENTRACIÓN: </strong>
                    <p class="p-comun-disabled p-1">{{ $concentracion }} </p>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        NRO. CUENTA CONTABLE: </strong>
                    <p class="p-comun-disabled p-1">{{ $cuenta_contable }} - {{ $nombre_cuenta }} </p>

                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        STOCK MÍNIMO EN ALMACEN: </strong>
                    <p class="p-comun-disabled p-1">{{ $stock_minimo_almacen }} </p>

                </div>

            </div>
            <div style="height: 0.5rem;"></div>
            <div class="row">
                <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                    <div class="card cabecera">
                        <b>CANTIDADES MÁXIMAS Y MÍNIMAS POR SERVICIO</b>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="max-height: 300px; overflow-y: auto;">
                        <table class="table tabla-ploma">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>VALOR MÍNIMO</th>
                                    <th>VALOR MÁXIMO</th>
                                    <th>SERVICIO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($listaValoresActuales !== [])
                                    @foreach ($listaValoresActuales as $index => $valorActual)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $valorActual->cantidad_minima }}</td>
                                            <td>{{ $valorActual->cantidad_maxima }}</td>
                                            <td>{{ $valorActual->maxMinServicioTipoConsulta->nombre }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" style="text-align: center;">No se registraron valores</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <div style="height: 0.5rem;"></div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="p-2 card card-borde mb-2 h-100">
                    <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                        <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <strong>Nombre de usuario:</strong>
                            {{ $userCrea }}
                        </li>
                        <li>
                            <strong>Fecha de registro:</strong>
                            {{ Carbon\Carbon::parse($fechaCreado)->format('d/m/Y') }} <b> Hrs:</b>
                            {{ Carbon\Carbon::parse($fechaCreado)->format('H:i A') }}
                        </li>
                    </ul>
                </div>
            </div>

            @if ($fechaEditado != null)
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="p-2 card card-borde mb-2 h-100">
                        <h6 class="mb-3 text-orange font-weight-bold border-bottom pb-1">
                            <i class="fas fa-check-circle mr-1"></i> MODIFICADO POR
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Nombre de usuario:</strong>
                                {{ $userEdit }}
                            </li>
                            <li>
                                <strong>Fecha de registro:</strong>
                                {{ Carbon\Carbon::parse($fechaEditado)->format('d/m/Y') }} <b> Hrs:</b>
                                {{ Carbon\Carbon::parse($fechaEditado)->format('H:i A') }}
                            </li>

                        </ul>
                    </div>
                </div>
            @endif

        </div>
    </x-ver-modal>
</div>
<script>
    document.addEventListener('verMedicamento', function() {
        $('#modalVerMedicamento').modal('show');
    });
</script>
