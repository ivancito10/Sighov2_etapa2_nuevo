<div>
    <x-ver-modal id="verReingresoMedicamentoModal" labelledby="verReingresoMedicamentoModalLabel"
        title="REINGRESO DE MEDICAMENTOS">
        <div class="row">
            <div class="col-lg-7 col-md-8 col-sm-12 col-12">
                <div class="cabecera">
                    <b>DETALLE DEL REINGRESO</b>
                </div>
            </div>
            @if ($origenReingreso)
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div style="height: 0.5rem;"></div>
                    <table class="table tabla-ploma">
                        <tr>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                    </svg>
                                    NOMBRE ASEGURADO:
                                </strong>
                            </td>
                            <td colspan="3">{{ $nombreCompleto }} </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                                    </svg>
                                    CARNET IDENTIDAD:
                                </strong>
                            </td>
                            <td>{{ $ci }} </td>
                            <td>
                                <strong>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-postcard-fill" viewBox="0 0 16 16">
                                        <path d="M11 8h2V6h-2z" />
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5.5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0zM2 5.5a.5.5 0 0 0 .5.5H6a.5.5 0 0 0 0-1H2.5a.5.5 0 0 0-.5.5M2.5 7a.5.5 0 0 0 0 1H6a.5.5 0 0 0 0-1zM2 9.5a.5.5 0 0 0 .5.5H6a.5.5 0 0 0 0-1H2.5a.5.5 0 0 0-.5.5m8-4v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5" />
                                    </svg>
                                    MATRICULA:
                                </strong>
                            </td>
                            <td>{{ $matricula }} </td>
                        </tr>

                    </table>
                </div>
            @endif

            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card card-comun p-1">
                    <div class="row">
                        <div class="col-lg-12 col-md-4 col-sm-12 col-12 text-right">
                            <strong>CÓDIGO REINGRESO:</strong>
                            <span class="p-1 p-comun-disabled">{{ $codigo_reingreso }} </span>
                        </div>

                        @if ($origenReingreso)
                            <div class="col-lg-5 col-md-4 col-sm-4 col-4">
                            @else
                                <div class="col-lg-9 col-md-4 col-sm-4 col-4">
                        @endif
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                            </svg>
                            UNIDAD ORIGEN:
                        </strong>
                        <p class=" p-1 p-comun-disabled">{{ $unidad }} </p>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-4">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-calendar2-plus-fill" viewBox="0 0 16 16">
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 3.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5H2.545c-.3 0-.545.224-.545.5m6.5 5a.5.5 0 0 0-1 0V10H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V11H10a.5.5 0 0 0 0-1H8.5z" />
                            </svg>
                            FECHA REINGRESO:</strong>
                        <p class=" p-1 p-comun-disabled">{{ $fecha_reingreso }} </p>
                    </div>
                    @if ($origenReingreso)
                        <div class="col-lg-2 col-md-4 col-sm-4 col-4">
                            <strong>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16">
                                    <path
                                        d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                                </svg>
                                Nº DE HABITACIÓN:
                            </strong>
                            <p class=" p-1 p-comun-disabled">{{ $nro_habitacion }} </p>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-4 col-4">
                            <strong>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16">
                                    <path
                                        d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                                </svg>
                                Nº DE PISO:
                            </strong>
                            <p class=" p-1 p-comun-disabled">{{ $piso }} </p>
                        </div>
                    @endif

                    <div class="col-lg-6 col-md-4 col-sm-12 col-12">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                            </svg>
                            MOTIVO DE REINGRESO:
                        </strong>
                        <p class="p-1 p-comun-disabled">{{ $motivo_reingreso }} </p>
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-12 col-12">
                        <strong>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-blockquote-left" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm5 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-5 3a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1zm.79-5.373q.168-.117.444-.275L3.524 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562Q2 7.587 2 7.969q0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282q.036-.305.123-.498a1.4 1.4 0 0 1 .252-.37 2 2 0 0 1 .346-.298zm2.167 0q.17-.117.445-.275L5.692 6q-.183.111-.452.287-.27.176-.51.428a2.4 2.4 0 0 0-.398.562q-.165.31-.164.692 0 .54.217.873.217.328.72.328.322 0 .504-.211a.7.7 0 0 0 .188-.463q0-.345-.211-.521-.205-.182-.568-.182h-.282a1.8 1.8 0 0 1 .118-.492q.087-.194.257-.375a2 2 0 0 1 .346-.3z" />
                            </svg>
                            OBSERVACIONES:
                        </strong>
                        <p class="p-1 p-comun-disabled">{{ $observacion }} </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card card-comun p-1">
                <div class="row">
                    <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                        <div class="cabecera-tabla-azul-oscuro justify-content-center">
                            <b> MEDICAMENTOS INGRESADOS</b>
                        </div>
                    </div>
                    <div style="height: 0.5rem;"></div>
                    <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                        <div class="table-responsive">
                            <table class="table tabla-azul-oscuro">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>CÓDIGO</th>
                                        <th>MEDICAMENTO</th>
                                        <th>FORMA FARMACÉUTICA</th>
                                        <th>CONCENTRACIÓN</th>
                                        <th>FECHA VENCIMIENTO</th>
                                        <th>CANTIDAD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($medicamentosAgregados == [])
                                        <tr style="text-align: center;">
                                            <td colspan="8">Ningún elemento seleccionado</td>
                                        </tr>
                                    @else
                                        @foreach ($medicamentosAgregados as $index => $medicamentoIngresado)
                                            <tr style="text-align:center;">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $medicamentoIngresado['codigo_medicamento'] }}</td>
                                                <td>{{ $medicamentoIngresado['nombre'] }}</td>
                                                <td>{{ $medicamentoIngresado['forma_farmaceutica'] }}</td>
                                                <td>{{ $medicamentoIngresado['concentracion'] }}</td>
                                                <!-- <td>{{ $medicamentoIngresado['fecha_vencimiento'] }}</td> -->
                                                <td>{{ \Carbon\Carbon::parse($medicamentoIngresado['fecha_vencimiento'])->format('d/m/Y') }}
                                                </td>

                                                <td>{{ $medicamentoIngresado['cantidad'] }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


</x-ver-modal>
</div>

<script>
    window.addEventListener('modalVerReingresoMedicamento', event => {
        $('#verReingresoMedicamentoModal').modal('show');
    });
</script>
