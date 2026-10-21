<div>
    <x-ver-dos-modal id="modalVerCierreAlmacenGeneral" labelledby="modalVerCierreAlmacenGeneralLabel"
        title="CIERRE DE GESTIÓN - ALMACEN GENERAL">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card card-comun p-2" style="height:100%">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="cabecera text-center">
                                        <b>DATOS DEL INVENTARIADOR</b>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-9 col-sm-9 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                        </svg>
                                        NOMBRE COMPLETO:</strong>
                                    <p class="p-comun-disabled p-1">{{ $nombre }} </p>
                                </div>
                                <div class="col-lg-12 col-md-3 col-sm-3 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                                        </svg>
                                        CARNET:</strong>
                                    <p class="p-comun-disabled p-1">{{ $carnet }} </p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-12 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-bookmark-star-fill" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5M8.16 4.1a.178.178 0 0 0-.32 0l-.634 1.285a.18.18 0 0 1-.134.098l-1.42.206a.178.178 0 0 0-.098.303L6.58 6.993c.042.041.061.1.051.158L6.39 8.565a.178.178 0 0 0 .258.187l1.27-.668a.18.18 0 0 1 .165 0l1.27.668a.178.178 0 0 0 .257-.187L9.368 7.15a.18.18 0 0 1 .05-.158l1.028-1.001a.178.178 0 0 0-.098-.303l-1.42-.206a.18.18 0 0 1-.134-.098z" />
                                        </svg>
                                        UNIDAD:</strong>
                                    <p class="p-comun-disabled p-1">{{ $unidadInventariador }} </p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-12 col-12">
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-suitcase-lg-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M7 0a2 2 0 0 0-2 2H1.5A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14H2a.5.5 0 0 0 1 0h10a.5.5 0 0 0 1 0h.5a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2H11a2 2 0 0 0-2-2zM6 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1zM3 13V3h1v10zm9 0V3h1v10z" />
                                        </svg>
                                        CARGO:</strong>
                                    <p class="p-comun-disabled p-1">{{ $cargoInventariador }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="card card-comun p-2 h-100">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-12 ">
                            <div class="cabecera text-center">
                                <b>DATOS DEL MATERIAL O SUMINISTRO</b>
                            </div>
                        </div>
                    </div>
                    <div style="height: 0.5rem;"></div>
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
                                <td colspan="3">{{ $codigo }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-capsule" viewBox="0 0 16 16">
                                            <path
                                                d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                                        </svg>
                                        MATERIAL O SUMINISTRO:</strong>
                                </td>
                                <td colspan="3">{{ $nombre_material }}</td>

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
                                        UNIDAD DE MEDIDA:</strong>
                                </td>
                                <td>{{ $unidad }}</td>
                                <td>
                                    <strong>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-beaker-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M15.575.006a.5.5 0 0 1 .327.79l-.048.058-.122.12A2.5 2.5 0 0 0 15 2.743V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2.742a2.5 2.5 0 0 0-.566-1.584L.268.975.146.854A.5.5 0 0 1 .5 0h15zM11.5 13a.5.5 0 1 0 0 1H13v-1zm-2-2a.5.5 0 0 0 0 1H13v-1zm2-2a.5.5 0 1 0 0 1H13V9zm-2-2a.5.5 0 1 0 0 1H13V7zm2-2a.5.5 0 1 0 0 1H13V5zm-2-2a.5.5 0 1 0 0 1H13V3z" />
                                        </svg>
                                        DESCRIPCIÓN:</strong>
                                </td>
                                <td>{{ $descripcion }}</td>

                            </tr>
                            <tr>
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

                                    <b>{{ number_format($stock_actual, 0) }} UNIDADES</b>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 col-12" style="text-align:center;">
                            <div class="cabecera text-center">
                                <b>DATOS DE CIERRE DE GESTIÓN</b>
                            </div>
                        </div>
                    </div>
                    <div style="height: 0.5rem;"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="table-responsive">
                                <table class="table tabla-ploma">
                                    <tr>
                                        <td colspan="3" class="text-center p-0" style="background-color: #E2E2E2">
                                            <b>CANTIDAD </b>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="text-center p-0" style="background-color:#E2E2E2">
                                            <b>EN SISTEMA</b>
                                        </td>
                                        <td class="text-center p-0" style="background-color:#E2E2E2">
                                            <b>FÍSICA</b>
                                        </td>

                                        <td class="text-center p-0" style="background-color:#E2E2E2">
                                            <b>DE BAJAS</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center ">
                                            {{ $cantidad_sistema }}
                                        </td>
                                        <td class="text-center ">
                                            {{ $cantidad_fisica }}
                                        </td>
                                        <td class="text-center ">
                                            {{ $cantidad_bajas ?? 'SIN BAJAS' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <b> FECHA VENCIMIENTO:</b>
                                            {{ \Carbon\Carbon::parse($fecha_vencimiento)->Format('d /m/ Y') }}
                                        </td>
                                        <td>
                                            <b>FECHA VENCIMIENTO DE BAJA:</b>
                                            @if ($fecha_vencimiento_baja)
                                                {{ \Carbon\Carbon::parse($fecha_vencimiento_baja)->Format('d /m/ Y') }}
                                            @else
                                                SIN FECHA DE BAJA
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>

                                        <td colspan="3" align="right">
                                            <span class="h5 ">
                                                @if (is_null($diferencia) || $diferencia === '')
                                                    <em style="color: #7d7d7d;"><b>No calculado</b></em>
                                                @elseif($diferencia == 0)
                                                    <em style="color: #2471a3;"><b>La cantidad en sistema y en físico
                                                            es la misma</b></em>
                                                @elseif($diferencia < 0)
                                                    <em style="color: #cb4335;"><b>Hay {{ $diferencia_mostrar }} MÁS
                                                            en físico que en el
                                                            sistema</b></em>
                                                @elseif($diferencia > 0)
                                                    <em style="color: #1e8449;"><b>Hay {{ $diferencia_mostrar }} MÁS
                                                            en el sistema que en
                                                            físico</b></em>
                                                @endif
                                            </span> <br>
                                            <b>DIFERENCIA :</b> {{ $diferencia_mostrar }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </x-ver-dos-modal>
</div>

<script>
    window.addEventListener('verCierreAlmacenGeneralModalcito', event => {
        //console.log('asds');
        $('#modalVerCierreAlmacenGeneral').modal('show');
    });
</script>
