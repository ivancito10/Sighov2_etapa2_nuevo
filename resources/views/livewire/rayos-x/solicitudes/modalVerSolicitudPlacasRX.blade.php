<div>
    <x-ver-modal id="modalVerEstudioRX" labelledby="modalVerEstudioRXLabel" title="DETALLE DE ORDEN DE RAYOS X">
        <div class="card-borde p-2">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12 col-12">
                    <div class="card cabecera">
                        <b>DATOS DE CONSULTA</b>
                    </div>
                </div>
                <div class="col-lg-5 col-md-3 col-sm-12 col-12 text-right">
                    <strong>ESTADO: </strong>
                    @if ($resultado == true)
                        <span style="background-color:#1B950E;color:white; padding:5px;border-radius:10px"><b>RESULTADOS
                                REGISTRADOS</b></span>
                    @else
                        <span style="background-color:#EB4123;color:white; padding:5px;border-radius:10px">RESULTADOS NO
                            REGISTRADOS</span>
                    @endif
                    </span>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-heart-pulse-fill" viewBox="0 0 16 16">
                            <path
                                d="M1.475 9C2.702 10.84 4.779 12.871 8 15c3.221-2.129 5.298-4.16 6.525-6H12a.5.5 0 0 1-.464-.314l-1.457-3.642-1.598 5.593a.5.5 0 0 1-.945.049L5.889 6.568l-1.473 2.21A.5.5 0 0 1 4 9z" />
                            <path
                                d="M.88 8C-2.427 1.68 4.41-2 7.823 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C11.59-2 18.426 1.68 15.12 8h-2.783l-1.874-4.686a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8z" />
                        </svg>
                        ESPECIALIDAD: </strong>
                    <p class="p-comun-disabled p-1">{{ $especialidad }} </p>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-prescription2" viewBox="0 0 16 16">
                            <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                            <path
                                d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                        </svg>
                        ESPECIALISTA: </strong>
                    <p class="p-comun-disabled p-1">{{ $espacialista }} </p>
                </div>

                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-calendar-date" viewBox="0 0 16 16">
                            <path
                                d="M6.445 11.688V6.354h-.633A13 13 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23" />
                            <path
                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                        </svg>
                        FECHA DE ATENCIÓN: </strong>
                    <p class="p-comun-disabled p-1">{{ $fechaAtencion }} </p>
                </div>
                <div class="col-lg-8 col-md-4 col-sm-4 col-4">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-journal" viewBox="0 0 16 16">
                            <path
                                d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                            <path
                                d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                        </svg>
                        DIAGNOSTICO DESCRIPTIVO: </strong>
                    <p class="p-comun-disabled p-1">{{ $diagnosticoDescriptivo }} </p>
                </div>
            </div>
            <div style="height: 0.5rem;"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card cabecera">
                        <b>DATOS DE SOLICITUD</b>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        NRO. DE SOLICITUD: </strong>
                    <p class="p-comun-disabled p-1">{{ $nroSolicitud }} </p>

                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-card-checklist" viewBox="0 0 16 16">
                            <path
                                d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                            <path
                                d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
                        </svg>
                        TIPO DE ESTUDIO: </strong>
                    <p class="p-comun-disabled p-1">{{ $tipoEstudio }} </p>

                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-book-fill" viewBox="0 0 16 16">
                            <path
                                d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
                        </svg>
                        INDICACIONES DEL ESTUDIO: </strong>
                    <p class="p-comun-disabled p-1">{{ $indicaciones }} </p>

                </div>
            </div>
            @if ($resultado == true)
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card cabecera">
                            <b>RESULTADOS</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($imagenesResultado as $imageneResultado)
                    <img src="{{ !empty($imageneResultado['fotoRX']) ? 'data:image/jpg;base64,' . $imageneResultado['fotoRX'] : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="200" height="200" alt="FTP.{{ $imageneResultado['fotoRX'] }}">
                    @endforeach

                </div>
            @endif

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

            @if ($userEdit != '')
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
    document.addEventListener('abrirModalVerSolicitudRX', function() {
        // alert("sd");
        $('#modalVerEstudioRX').modal('show');
    });
</script>
