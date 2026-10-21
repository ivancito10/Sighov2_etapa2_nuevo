<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/styles/choices.min.css" rel="stylesheet">
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">

            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;"> EDICIÓN DE INGRESO DE MEDICAMENTOS -
                ALMACEN DE MEDICAMENTOS</h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>

    <fieldset class="field_estilos">
        <legend>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path
                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
            </svg>
            <em>Modifique los datos necesarios del proveedor:</em>
            <span>*</span>
        </legend>

        <div class="row">
            <div class='col-lg-3 col-md-6 col-sm-6 col-6' wire:ignore>
                <label for="id_institucion_externa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-shop" viewBox="0 0 16 16">
                        <path
                            d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z" />
                    </svg>
                    PROVEEDOR: <span style="color:#cb4335;"><b>*</b></span> </label>
                <select id="id_institucion_externa"
                    class="form-control input-comun  @error('id_institucion_externa') is-invalid @enderror"
                    style="width: 100%;" tabindex="-1" required>
                    <option selected="selected">Selecciona el proveedor </option>
                    @foreach ($institucionesExternas as $institucionExterna)
                        <option value="{{ $institucionExterna->id }}">{{ $institucionExterna->razon_social }}</option>
                    @endforeach

                </select>
                @error('id_institucion_externa')
                    <div class="text-danger">
                        <b>El campo proveedor es obligatorio.</b>
                    </div>
                @enderror
            </div>
            <div class='col-lg-3 col-md-6 col-sm-6 col-12'>
                <label for="id_tipo_proceso">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-caret-down-square-fill" viewBox="0 0 16 16">
                        <path
                            d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z" />
                    </svg>
                    TIPO PROCESO: <span style="color:#cb4335;"><b>*</b></span></label>
                <select wire:model.live="id_tipo_proceso" id="id_tipo_proceso"
                    class="form-control input-comun  @error('id_tipo_proceso') is-invalid @enderror"
                    style="width: 100%;" wire:change="tipoProcesoSeleccionado" tabindex="-1" required>
                    <option value={{ $id_tipo_proceso }}>{{ $nombreTipoProceso }}</option>
                    @foreach ($procesos as $proceso)
                        <option value="{{ $proceso->id }}">{{ $proceso->nombre }}</option>
                    @endforeach
                </select>
                @error('id_tipo_proceso')
                    <div class="text-danger">
                        <b>El campo tipo proceso es obligatorio.</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-12">
                <label for="fecha_ingreso">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                        <path
                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                    </svg>
                    FECHA INGRESO: <span style="color:#cb4335;"><b>*</b></span>
                </label>
                <input class="form-control input-comun  @error('fecha_ingreso') is-invalid @enderror" id="fecha_ingreso"
                    wire:model.live="fecha_ingreso" type="date" max="{{ date('Y-m-d') }}">
                @error('fecha_ingreso')
                    <div class="text-danger">
                        <b>El campo fecha ingreso es obligatorio.</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-12">
                <label for="cod_ingreso">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z" />
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                        <path
                            d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                    </svg>
                    CÓDIGO INGRESO
                    @if ($nombreTipoProceso != null)
                        ({{ $nombreTipoProceso }})
                    @endif
                    : <span style="color:#cb4335;"><b>*</b></span>
                </label>
                <input class="form-control input-comun  @error('cod_ingreso') is-invalid @enderror" id="cod_ingreso"
                    wire:model=cod_ingreso placeholder="Introduzca el código del ingreso">
                @error('cod_ingreso')
                    <div class="text-danger">
                        <b>El campo codigo ingreso es obligatorio.</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-12">
                <label for="asmi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z" />
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                        <path
                            d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                    </svg>
                    CÓDIGO ASMI: <span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun" id="asmi" wire:model="asmi" disabled>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-12">
                <label for="fecha_contrato">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                        <path
                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                    </svg>
                    FECHA DE ORDEN DE COMPRA: <span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun @error('fecha_contrato') is-invalid @enderror"
                    id="fecha_contrato" wire:model="fecha_contrato" type="date" max="{{ date('Y-m-d') }}">
                @error('fecha_contrato')
                    <div class="text-danger">
                        <b>El campo fecha de contrato es obligatorio.</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-12">
                <label for="fecha_recepcion">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                        <path
                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                    </svg>
                    FECHA RECEPCIÓN: <span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun @error('fecha_recepcion') is-invalid @enderror"
                    id="fecha_recepcion" type="date" wire:model="fecha_recepcion" max="{{ date('Y-m-d') }}">
                @error('fecha_recepcion')
                    <div class="text-danger">
                        <b>El campo fecha de recepción es obligatorio.</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <label for="hora_recepcion">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-clock-history" viewBox="0 0 16 16">
                        <path
                            d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                        <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                        <path
                            d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                    </svg>
                    HORA RECEPCIÓN: <span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun @error('hora_recepcion') is-invalid @enderror"
                    id="hora_recepcion" type="time" wire:model="hora_recepcion">
                @error('hora_recepcion')
                    <div class="text-danger">
                        <b>El campo hora de recepción es obligatorio.</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-6">
                <label for="cod_cuce">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z" />
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                        <path
                            d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                    </svg>
                    CÓDIGO CUCE:</label>
                <input class="form-control input-comun" id="cod_cuce" type="text"
                    oninput="this.value = this.value.toUpperCase()" wire:model="cod_cuce"
                    placeholder="Introduzca el código CUCE">
            </div>
            <div class="col-lg-10 col-md-12 col-sm-12 col-12" wire:ignore>
                <label for="responsables-texto" style="display: block; text-align: left;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path
                            d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                    </svg>
                    RESPONSABLES O COMISIÓN DE
                    RECEPCIÓN: <span style="color:#cb4335;"><b>*</b>
                </label>
                <!-- campo para llenar diagnostico -->
                <div id="responsables-texto" style="height: 100px;  ">
                    <!--introduzca aqui el texto a recuperar -->
                    {!! $responsables !!}
                </div>
            </div>
        </div>
    </fieldset>
    <br>
    <fieldset class="field_estilos">
        <legend>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path
                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
            </svg>
            <em>Modifique los datos necesarios de la(s) factura(s) o nota(s) de remisión:</em>
            <span>*</span>
        </legend>
        <div class="row">
            <div class="col-lg-2 col-md-12 col-sm-12 col-12 ">
                <strong>REGISTRAR FACTURA(S) O NOTA(S) DE REMISIÓN</strong>
                @include('livewire.farmacia.almacen-farmacia.ingreso-medicamentos.modalRegistrarFacturas')

            </div>
            <div class="col-lg-10 col-md-12 col-sm-12 col-12">
                <table class="table tabla-ploma">
                    <thead>
                        <tr align="center">
                            <th scope="col">#</th>
                            <th scope="col">NRO. </th>
                            <th scope="col">FECHA </th>
                            <th scope="col">MONTO TOTAL </th>
                            <th scope="col">TIPO</th>
                            <th scope="col">OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($facturaAgregadas == [])
                            <tr style="text-align: center;">
                                <td colspan="6">Ningún elemento seleccionado</td>
                            </tr>
                        @else
                            @foreach ($facturaAgregadas as $index => $facturaAgregada)
                                <tr style="text-align:center;">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $facturaAgregada['nro_factura'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($facturaAgregada['fecha_factura'])->format('d/m/Y') }}
                                    </td>
                                    <td>{{ 'Bs ' . number_format($facturaAgregada['monto_total_factura'], 2, '.', ',') }}
                                    </td>
                                    <td>
                                        @if ($facturaAgregada['nota_remision'] == true)
                                            NOTA DE REMISIÓN
                                        @else
                                            FACTURA
                                        @endif
                                    </td>
                                    <td>
                                        <x-eliminar-button
                                            wire:click="eliminarFactura('{{ $facturaAgregada['nro_factura'] }}')">
                                        </x-eliminar-button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row d-flex justify-content-end">
            <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                <label for="monto_total_facturas">
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
                    MONTO TOTAL:</label>
                <input class="form-control input-comun" id="monto_total_facturas"
                    value="{{ 'Bs ' . number_format($monto_total_facturas, 2, '.', ',') }}" disabled>
            </div>
        </div>
    </fieldset>

    <br>
    <fieldset class="field_estilos">
        <legend>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path
                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
            </svg>
            <em>Modifique los datos requeridos:</em>
            <span>*</span>
        </legend>
        <div class="row">
            <div class='col-lg-4 col-md-4 col-sm-12 col-12' wire:ignore>
                <label for="id_medicamento">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z" />
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                        <path
                            d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                    </svg>
                    CÓDIGO: <span style="color:#cb4335;"><b>*</b></span> </label>
                <select wire:model.live="id_medicamento" id="id_medicamento"
                    class="form-control input-comun select2  @error('id_medicamento') is-invalid @enderror"
                    style="width: 100%;" wire:change="medicamentoSeleccionado" tabindex="-1" required>
                    <option selected="selected">Selecciona código </option>
                    @foreach ($medicamentos as $medicamento)
                        <option value="{{ $medicamento->id }}">{{ $medicamento->codigo_medicamento }} -
                            {{ $medicamento->nombre }}</option>
                    @endforeach
                </select>
                @error('id_medicamento')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>

            <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                <strong>MEDICAMENTO O INSUMO</strong>
                <p class="p-2 p-comun-disabled">{{ $nombreMedicamento }} </p>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                <label for="formaFarmaceuticaMostrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-capsule" viewBox="0 0 16 16">
                        <path
                            d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                    </svg>
                    FORMA FARMACÉUTICA:<span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun" id="formaFarmaceuticaMostrar"
                    wire:model="formaFarmaceuticaMostrar" disabled>
                {{-- @error('formaFarmaceuticaMostrar')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror --}}
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                <label for="concentracionMostrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-capsule" viewBox="0 0 16 16">
                        <path
                            d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                    </svg>
                    CONCENTRACIÓN:<span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun @error('concentracionMostrar') is-invalid @enderror"
                    id="concentracionMostrar" wire:model="concentracionMostrar" disabled>
                @error('concentracionMostrar')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                <label for="pais_marca">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-globe-americas" viewBox="0 0 16 16">
                        <path
                            d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M2.04 4.326c.325 1.329 2.532 2.54 3.717 3.19.48.263.793.434.743.484q-.121.12-.242.234c-.416.396-.787.749-.758 1.266.035.634.618.824 1.214 1.017.577.188 1.168.38 1.286.983.082.417-.075.988-.22 1.52-.215.782-.406 1.48.22 1.48 1.5-.5 3.798-3.186 4-5 .138-1.243-2-2-3.5-2.5-.478-.16-.755.081-.99.284-.172.15-.322.279-.51.216-.445-.148-2.5-2-1.5-2.5.78-.39.952-.171 1.227.182.078.099.163.208.273.318.609.304.662-.132.723-.633.039-.322.081-.671.277-.867.434-.434 1.265-.791 2.028-1.12.712-.306 1.365-.587 1.579-.88A7 7 0 1 1 2.04 4.327Z" />
                    </svg>
                    PAIS DE ORIGEN O MARCA:<span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun @error('pais_marca') is-invalid @enderror" id="pais_marca"
                    wire:model="pais_marca" placeholder="Ingresa el pais de origen o marca.."
                    oninput="this.value = this.value.toUpperCase()">
                @error('pais_marca')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>

            <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                <label for="fecha_vencimiento">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                        <path
                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0" />
                    </svg>
                    FECHA VENCIMIENTO:<span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun @error('fecha_vencimiento') is-invalid @enderror"
                    id="fecha_vencimiento" wire:model="fecha_vencimiento" type="date"
                    min="{{ now()->toDateString() }}">
                @error('fecha_vencimiento')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-6">
                <label for="monto_total_individual">
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
                    PRECIO TOTAL:<span style="color:#cb4335;"><b>*</b></span></label>
                <input type="number"
                    class="form-control input-comun @error('monto_total_individual') is-invalid @enderror"
                    id="monto_total_individual" wire:model.live="monto_total_individual" maxlength="9"
                    pattern="^\d{1,6}(\.\d{1,2})?$" inputmode="decimal" wire:change="calcularMontoTotalIndividual">
                @error('monto_total_individual')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 col-6">
                <label for="cantidad">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-sort-numeric-up-alt" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M11.36 7.098c-1.137 0-1.708-.657-1.762-1.278h1.004c.058.223.343.45.773.45.824 0 1.164-.829 1.133-1.856h-.059c-.148.39-.57.742-1.261.742-.91 0-1.72-.613-1.72-1.758 0-1.148.848-1.836 1.973-1.836 1.09 0 2.063.637 2.063 2.688 0 1.867-.723 2.848-2.145 2.848zm.062-2.735c.504 0 .933-.336.933-.972 0-.633-.398-1.008-.94-1.008-.52 0-.927.375-.927 1 0 .64.418.98.934.98" />
                        <path
                            d="M12.438 8.668V14H11.39V9.684h-.051l-1.211.859v-.969l1.262-.906h1.046zM4.5 13.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.5.5 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707z" />
                    </svg>
                    CANTIDAD:<span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun  @error('cantidad') is-invalid @enderror" id="cantidad"
                    wire:model.live="cantidad" type="number" wire:change="calcularMontoTotalIndividual"
                    placeholder="Cantidad ingresada"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                @error('cantidad')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror

            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                <label for="precio_unitario">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-currency-dollar" viewBox="0 0 16 16">
                        <path
                            d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z" />
                    </svg>
                    PRECIO UNITARIO:<span style="color:#cb4335;"><b>*</b></span></label>
                <input class="form-control input-comun @error('precio_unitario') is-invalid @enderror"
                    id="precio_unitario" wire:model="precio_unitario" type="text" disabled>

                @error('precio_unitario')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6 col-6 d-flex justify-content-end align-items-end">
                <x-agregar-button wire:click="agregarMedicamento">ADICIONAR</x-agregar-button>
            </div>
        </div>
        <br>

        <div class="row">
            <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                <div class="cabecera text-center">
                    <b> LISTADO DE MEDICAMENTOS INGRESADOS</b>
                </div>
                <div style="height: 0.5rem;"></div>
                <div style="max-height: 250px; overflow-y: auto;">
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
                                <th>OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($medicamentosAgregados == [])
                                <tr style="text-align: center;">
                                    <td colspan="9">Ningún elemento seleccionado</td>
                                </tr>
                            @else
                                @foreach ($medicamentosAgregados as $index => $medicamentoIngresado)
                                    <tr>
                                        <td style="text-align: center;">{{ $index + 1 }}</td>
                                        <td style="text-align: center;">
                                            {{ $medicamentoIngresado['codigo_medicamento'] }}</td>
                                        <td style="text-align: left;">{{ $medicamentoIngresado['nombre'] }}</td>
                                        <td style="text-align: center;">
                                            {{ $medicamentoIngresado['forma_farmaceutica'] }}</td>
                                        <td style="text-align: center;">
                                            {{ $medicamentoIngresado['concentracion'] }}</td>
                                        <td style="text-align: center;">{{ $medicamentoIngresado['cantidad'] }}</td>
                                        <td style="text-align: center;">
                                            {{ number_format($medicamentoIngresado['precio_unitario'], 2, '.', ',') }}
                                        </td>
                                        <td style="text-align: center;">
                                            {{ number_format($medicamentoIngresado['total'], 2, '.', ',') }}</td>
                                        <td style="text-align: center;">
                                            {{ $medicamentoIngresado['pais_marca'] }}</td>
                                        <td style="text-align: center;">
                                            {{ \Carbon\Carbon::parse($medicamentoIngresado['fecha_vencimiento'])->format('d/m/Y') }}
                                        </td>
                                        <td style="text-align: center;">
                                            <x-eliminar-button
                                                wire:click="eliminarMedicamento('{{ $medicamentoIngresado['codigo_medicamento'] }}', '{{ $medicamentoIngresado['total'] }}')">
                                            </x-eliminar-button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="row d-flex justify-content-end">
            <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                <label for="monto_total">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                        class="bi bi-cash-coin" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0" />
                        <path
                            d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z" />
                        <path
                            d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 14.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z" />
                        <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567" />
                    </svg>
                    MONTO TOTAL:</label>
                <input class="form-control input-comun" id="monto_total"
                    value="{{ 'Bs ' . number_format($monto_total, 2, '.', ',') }}" disabled>
            </div>
        </div>

    </fieldset>

    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
                <x-cancel-button wire:click="cancelar">CANCELAR</x-cancel-button>
                <x-save-button onclick="editarIngreso()" wire:loading.attr="disabled"
                    id="save-button">GUARDAR</x-save-button>
            </div>
        </div>
    </div>

    <style>
        .select2-container .select2-selection--single {
            height: 45px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 45px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        let proveedoresSelect = null;

        document.addEventListener('DOMContentLoaded', function() {

            const el = document.getElementById('id_institucion_externa');

            // Inicializar Choices SOLO si el elemento existe
            if (el) {
                proveedoresSelect = new Choices(el, {
                    searchEnabled: true,
                    itemSelectText: 'Presiona para seleccionar',
                    shouldSort: false
                });

                el.addEventListener('addItem', function(event) {
                    @this.set('id_institucion_externa', event.detail.value);
                });
            }
        });

        document.addEventListener('cargarIdInstitucionExterna', function(event) {
            const id = String(event.detail.id);

            if (proveedoresSelect) {
                proveedoresSelect.setChoiceByValue(id);
            }
        });


        let quill;
        document.addEventListener('DOMContentLoaded', function() {
            if (quill) {
                quill.setText('');
            }
            quill = new Quill('#responsables-texto', {
                theme: 'snow',
                placeholder: 'Introduzca texto',
            });

        });

        function editarIngreso() {
            Swal.fire({
                title: '¿Editar Ingreso?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('editarIngresoMedicamento', {
                        texto: quill.getSemanticHTML()
                    });
                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }

        document.addEventListener('guardado', function() {
            Swal.fire({
                title: "Guardado!",
                text: "El ingreso fue registrado exitosamente.",
                icon: "success",
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false

            });
        });

        document.addEventListener('noGuardado', function() {
            Swal.fire({
                title: "Cancelado!",
                text: "No se registró el ingreso.",
                icon: "error",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('datosIncompletos', function() {
            Swal.fire({
                title: "Datos incompletos",
                text: "Complete los campos necesarios",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('medicamentosVacio', function() {
            Swal.fire({
                title: "Lista de medicamentos vacia",
                text: "Registre al menos un medicamento para registrar el ingreso",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false

            });
        });

        document.addEventListener('medicamentoAgregadoCorrectamente', function() {
            Swal.fire({
                title: "Medicamento agregado correctamente",
                icon: "success",
                timer: 1000,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false

            });
        });

        document.addEventListener('facturaEliminadaCorrectamente', function() {
            Swal.fire({
                title: "<b>Factura eliminada</b>",
                icon: "success",
                timer: 1000,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false

            });
        });

        document.addEventListener('facturasVacias', function() {
            Swal.fire({
                title: "Lista de facturas vacia",
                text: "Registre al menos una factura para guardar el ingreso",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('sinProveedorAgregado', function() {
            Swal.fire({
                title: "<b>Sin proveedor seleccionado</b>",
                text: "Seleccione un proveedor para registrar el ingreso",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });


        document.addEventListener('medicamentoEliminadoCorrectamente', function() {
            Swal.fire({
                title: "<b>Medicamento eliminado</b>",
                icon: "success",
                timer: 1000,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('medicamentoDuplicado', function() {
            Swal.fire({
                title: "Medicamento duplicado",
                text: "El medicamento ya fue agregado a la lista",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false

            });
        });

        document.addEventListener('montosDesiguales', function() {
            Swal.fire({
                title: "<b>Montos totales diferentes</b>",
                text: "El monto total descrito en las facturas es diferente al especificado al registrar los medicamentos. Verifique e intentelo nuevamente",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#id_medicamento');
            $(select).select2();

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

        document.addEventListener('refreshSelect2', function() {
            var select = document.querySelector('#id_medicamento');
            @this.call('actualizarListaMedicamentos').then(() => {
                var medicamentos = @this.get('medicamentos');
                $(select).select2('destroy').select2()
            });
        });

        document.addEventListener('valoresVacios', function() {
            Swal.fire({
                title: "Valor nulo",
                text: "El valor total no puede ser igual a 0",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
    </script>
</div>
