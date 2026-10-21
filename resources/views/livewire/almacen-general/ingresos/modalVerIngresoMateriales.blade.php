<div>
    <x-ver-modal id="verIngresoMaterialModal" labelledby="verIngresoMaterialModalLabel"
        title="INGRESO DE MATERIALES Y SUMINISTROS">

        <div class="card-borde p-2">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="cabecera">
                        <b>DETALLE DEL INGRESO</b>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-shop" viewBox="0 0 16 16">
                            <path
                                d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z" />
                        </svg>
                        PROVEEDOR:</strong>
                    <p class="p-comun-disabled p-1">{{ $proveedor }} </p>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-caret-down-square-fill" viewBox="0 0 16 16">
                            <path
                                d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z" />
                        </svg>
                        TIPO DE PROCESO:</strong>
                    <p class="p-comun-disabled p-1">{{ $tipo_proceso }} </p>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                            <path
                                d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                        </svg>
                        FECHA DE INGRESO:</strong>
                    <p class="p-comun-disabled p-1">{{ \Carbon\Carbon::parse($fecha_ingreso)->Format('d /m/ Y') }} </p>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-qr-code" viewBox="0 0 16 16">
                            <path d="M2 2h2v2H2z" />
                            <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                            <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                            <path
                                d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                            <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                        </svg>
                        NRO. ADQUISICIÓN:</strong>
                    <p class="p-comun-disabled p-1">{{ $nro_ingreso_literal }} </p>
                </div>
                {{--<div class="col-lg-3 col-md-4 col-sm-4 col-4">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-qr-code" viewBox="0 0 16 16">
                            <path d="M2 2h2v2H2z" />
                            <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                            <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                            <path
                                d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                            <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                        </svg>
                        CÓDIGO ASMI:</strong>
                    <p class="p-comun-disabled p-1">{{ $cod_asmi_literal }} </p>
                </div>--}}
                {{--<div class="col-lg-3 col-md-4 col-sm-4 col-4">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        NRO. LOTE:</strong>
                    <p class="p-comun-disabled p-1">{{ $nro_lote }} </p>
                </div>--}}


                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                            <path
                                d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                        </svg>
                        FECHA DE ORDEN DE COMPRA:</strong>
                    <p class="p-comun-disabled p-1"> {{ \Carbon\Carbon::parse($fecha_contrato)->Format('d/m/Y') }}
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                            <path
                                d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                        </svg>FECHA RECEPCIÓN:</strong>
                    <p class="p-comun-disabled p-1"> {{ \Carbon\Carbon::parse($fecha_recepcion)->Format('d/m/Y') }}
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-clock-history" viewBox="0 0 16 16">
                            <path
                                d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                            <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                            <path
                                d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                        </svg>
                        HORA RECEPCIÓN:</strong>
                    <p class="p-comun-disabled p-1">{{ \Carbon\Carbon::parse($hora_recepcion)->format('H:i') }} </p>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-qr-code" viewBox="0 0 16 16">
                            <path d="M2 2h2v2H2z" />
                            <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                            <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                            <path
                                d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                            <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                        </svg>
                        CÓDIGO CUCE:</strong>
                    <p class="p-comun-disabled p-1">{{ $cod_cuce }} </p>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-caret-down-square-fill" viewBox="0 0 16 16">
                            <path
                                d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z" />
                        </svg>
                        NRO. RESOLUCIÓN:</strong>
                    <p class="p-comun-disabled p-1">{{ $nro_resolucion_memorandum }} </p>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                            class="bi bi-caret-down-square-fill" viewBox="0 0 16 16">
                            <path
                                d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z" />
                        </svg>
                        OBJ. COMPRA:</strong>
                    <p class="p-comun-disabled p-1">{{ $objeto_recepcion }} </p>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <label for="responsables">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path
                                d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                        </svg>
                        RESPONSABLES O COMISIÓN DE RECEPCIÓN:</label>
                    <div id="responsables-content"
                        style="border: 1px solid white; padding: 10px; height: auto; overflow-y: auto; background-color: #e5e8e8; border-radius: 8px;">
                        {!! $responsables !!}
                    </div>
                </div>
            </div>
        </div>
        <div style="height: 0.5rem;"></div>
        <div class="card-borde p-2">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="cabecera">
                        <b>FACTURA(S) / NOTA(S) DE REMISIÓN </b>
                    </div>
                </div>
            </div>
            <div style="height: 0.5rem;"></div>
            @if (!empty($facturas))
                <div style="max-height: 150px; overflow-y: auto; scrollbar-width: thin;">

                    <table class="table tabla-ploma">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16">
                                        <path
                                            d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                                    </svg>
                                    NRO. </th>
                                <th> <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                                    </svg>
                                    FECHA</th>
                                <th>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-caret-down-square-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z" />
                                    </svg>
                                    TIPO
                                </th>
                                <th> <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0" />
                                        <path
                                            d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z" />
                                        <path
                                            d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z" />
                                        <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567" />
                                    </svg>
                                    TOTAL </th>
                            </tr>
                        </thead>
                        @forelse ($facturas as $index=> $factura)
                            <tbody>
                                <tr>
                                    <td scope="row">{{ $index + 1 }}</td>
                                    <td>
                                        {{ $factura['nro_factura'] }}
                                    </td>
                                    <td>
                                        @if ($factura['fecha_factura'])
                                            {{ \Carbon\Carbon::parse($factura['fecha_factura'])->Format('d /m/ Y') }}
                                        @else
                                            SIN DATO
                                        @endif
                                    </td>
                                    <td>
                                        {{ $factura['tipo'] }}
                                    </td>
                                    <td>{{ 'Bs ' . $factura['total_factura'] }}</td>
                                </tr>
                            </tbody>
                        @empty
                            <p>NO HAY REGISTROS </p>
                        @endforelse
                    </table>
                </div>
            @else
                <p>NO HAY REGISTROS </p>
            @endif


        </div>

        <div style="height: 0.5rem;"></div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="cabecera-tabla-celeste">
                    <b>MEDICAMENTOS INGRESADOS</b>
                </div>
            </div>
        </div>
        <div style="height: 0.3rem;"></div>
        <div class="row">
            <div class ="col-lg-12 col-md-12 col-sm-12 col-12">
                <div style="max-height: 300px; overflow-y: auto; scrollbar-width: thin;">
                    <table class="table tabla-azul-oscuro">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CÓDIGO</th>
                                <th>MEDICAMENTO</th>
                                <th>FORMA FARMACEUTICA</th>
                                <th>CONCENTRACIÓN</th>
                                <th>CANTIDAD</th>
                                <th>PRECIO UNITARIO</th>
                                <th>TOTAL</th>
                                <th>MARCA O PAIS ORIGEN</th>
                                <th>FECHA VENCIMIENTO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($materialesIngreso)
                                @foreach ($materialesIngreso as $index => $materialIngreso)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $materialIngreso->materialIngresoMaterial->codigo }}
                                        </td>
                                        <td>{{ $materialIngreso->materialIngresoMaterial->nombre_material }}</td>
                                        <td>{{ $materialIngreso->materialIngresoMaterial->unidad }}
                                        </td>
                                        <td>{{ $materialIngreso->materialIngresoMaterial->descripcion }}
                                        </td>
                                        <td>{{ $materialIngreso->cantidad }}</td>
                                        <td>{{ 'Bs ' . number_format($materialIngreso->precio_unitario, 2, ',', '.') }}
                                        </td>
                                        <td>{{ 'Bs ' . number_format($materialIngreso->precio_total, 2, ',', '.') }}
                                        </td>
                                        <td>{{ $materialIngreso->pais_marca }}
                                        </td>
                                        @if ($materialIngreso->fecha_vencimiento)
                                            <td>{{ \Carbon\Carbon::parse($materialIngreso->fecha_vencimiento)->format('d/m/Y') }}
                                            </td>
                                        @else
                                            <td>SIN FECHA
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" style="text-align: center;">No hay medicamentos disponibles.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-12 col-md-4 col-sm-4 col-4 text-right">
                <strong>
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
                    MONTO TOTAL:</strong>
                <span class="p-comun-disabled p-2">{{ 'Bs ' . $monto_total }}
                </span>
            </div>
        </div>

        <br>

        <div class="card-borde p-2">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="cabecera">
                        <b>EL PROVEEDOR CUMPLE CON</b>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <p class="ml-3"><strong>Tiempo de entrega:</strong></p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4" style="text-align:center">
                    <input class="form-check-input" type="checkbox" value="" id="tiempoEntregaBoolean"
                        wire:model="tiempoEntregaBoolean" disabled>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <input class="form-control input-comun" id="tiempoEntregaObservaciones"
                        wire:model="tiempoEntregaObservaciones" type="text"
                        placeholder="Introduzca las observaciones" disabled>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <p class="ml-3"><strong>Cantidad según pedido:</strong></p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4" style="text-align:center">
                    <input class="form-check-input" type="checkbox" value="" id="cantidadPedidoBoolean"
                        wire:model="cantidadPedidoBoolean" disabled>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <input class="form-control input-comun" id="cantidadPedidoObservaciones" type="text"
                        wire:model="cantidadPedidoObservaciones" placeholder="Introduzca las observaciones" disabled>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <p class="ml-3"><strong>Calidad según lo solicitado:</strong></p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4" style="text-align:center">
                    <input class="form-check-input" type="checkbox" value="" id="calidadSolicitadaBoolean"
                        wire:model='calidadSolicitadaBoolean' disabled>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <input class="form-control input-comun" id="calidadSolicitadaObservaciones" type="text"
                        wire:model="calidadSolicitadaObservaciones" placeholder="Introduzca las observaciones" disabled>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <p class="ml-3"><strong>Esp. Técnicas / Términos de referencia:</strong></p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4" style="text-align:center">
                    <input class="form-check-input" type="checkbox" value="" id="terminosReferenciaBoolean"
                        wire:model='terminosReferenciaBoolean' disabled>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <input class="form-control input-comun" id="terminosReferenciaObservaciones" type="text"
                        wire:model="terminosReferenciaObservaciones" placeholder="Introduzca las observaciones" disabled>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <p class="ml-3"><strong>Capacitación:</strong></p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4" style="text-align:center">
                    <input class="form-check-input" type="checkbox" value="" id="capacitacionBoolean"
                        wire:model='capacitacionBoolean' disabled>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <input class="form-control input-comun" id="capacitacionObservaciones" type="text"
                        wire:model="capacitacionObservaciones" placeholder="Introduzca las observaciones" disabled>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <p class="ml-3"><strong>Garantías:</strong></p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-4" style="text-align:center">
                    <input class="form-check-input" type="checkbox" value="" id="garantiasBoolean"
                        wire:model='garantiasBoolean' disabled>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                    <input class="form-control input-comun" id="garantiasObservaciones" type="text"
                        wire:model="garantiasObservaciones" placeholder="Introduzca las observaciones" disabled>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <p class="ml-3"><strong>Otros:</strong></p>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                    <input class="form-control input-comun" id="otrasObservaciones" type="text"
                        wire:model="otrasObservaciones" placeholder="Introduzca las observaciones" disabled>
                </div>
            </div>
        </div>
    </x-ver-modal>
</div>

<script>
    window.addEventListener('modalVerIngresoMateriales', event => {
        $('#verIngresoMaterialModal').modal('show');
    });
</script>
