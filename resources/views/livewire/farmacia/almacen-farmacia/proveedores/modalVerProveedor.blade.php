<div>
    <x-ver-modal id="verModalProveedor" labelledby="verModalProveedorLabel" title="VER DATOS DEL PROVEEDOR">
        <div class="card-borde p-2">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card cabecera">
                        <b>DATOS DEL PROVEEDOR</b>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12 text-right">
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
                <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-bookmark-fill" viewBox="0 0 16 16">
                            <path
                                d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2" />
                        </svg>
                        RAZÓN SOCIAL:
                    </strong>
                    <p class="p-1 p-comun-disabled">
                        {{ $razonSocial }} </p>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-123" viewBox="0 0 16 16">
                            <path
                                d="M2.873 11.297V4.142H1.699L0 5.379v1.137l1.64-1.18h.06v5.961zm3.213-5.09v-.063c0-.618.44-1.169 1.196-1.169.676 0 1.174.44 1.174 1.106 0 .624-.42 1.101-.807 1.526L4.99 10.553v.744h4.78v-.99H6.643v-.069L8.41 8.252c.65-.724 1.237-1.332 1.237-2.27C9.646 4.849 8.723 4 7.308 4c-1.573 0-2.36 1.064-2.36 2.15v.057zm6.559 1.883h.786c.823 0 1.374.481 1.379 1.179.01.707-.55 1.216-1.421 1.21-.77-.005-1.326-.419-1.379-.953h-1.095c.042 1.053.938 1.918 2.464 1.918 1.478 0 2.642-.839 2.62-2.144-.02-1.143-.922-1.651-1.551-1.714v-.063c.535-.09 1.347-.66 1.326-1.678-.026-1.053-.933-1.855-2.359-1.845-1.5.005-2.317.88-2.348 1.898h1.116c.032-.498.498-.944 1.206-.944.703 0 1.206.435 1.206 1.07.005.64-.504 1.106-1.2 1.106h-.75z" />
                        </svg>
                        NIT:
                    </strong>
                    <p class="p-1 p-comun-disabled">
                        {{ $nit }} </p>
                </div>


                <div class="col-lg-4 col-md-5 col-sm-5 col-5">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-telephone-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                        </svg>
                        TELÉFONO:
                    </strong>
                    <p class="p-1 p-comun-disabled">
                        {{ $telefono }} </p>
                </div>
                <div class="col-lg-4 col-md-7 col-sm-7 col-7">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path
                                d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z" />
                        </svg>
                        CORREO:
                    </strong>
                    <p class="p-1 p-comun-disabled">
                        {{ $correo }} </p>
                </div>
                <div class="col-lg-4 col-md-8 col-sm-8 col-8">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-compass-fill" viewBox="0 0 16 16">
                            <path
                                d="M15.5 8.516a7.5 7.5 0 1 1-9.462-7.24A1 1 0 0 1 7 0h2a1 1 0 0 1 .962 1.276 7.5 7.5 0 0 1 5.538 7.24m-3.61-3.905L6.94 7.439 4.11 12.39l4.95-2.828 2.828-4.95z" />
                        </svg>
                        ZONA:
                    </strong>
                    <p class="p-1 p-comun-disabled">
                        {{ $zona }} </p>
                </div>

                <div class="col-lg-9 col-md-8 col-sm-4 col-8">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-signpost-split-fill" viewBox="0 0 16 16">
                            <path
                                d="M7 16h2V6h5a1 1 0 0 0 .8-.4l.975-1.3a.5.5 0 0 0 0-.6L14.8 2.4A1 1 0 0 0 14 2H9v-.586a1 1 0 0 0-2 0V7H2a1 1 0 0 0-.8.4L.225 8.7a.5.5 0 0 0 0 .6l.975 1.3a1 1 0 0 0 .8.4h5z" />
                        </svg>
                        DIRECCIÓN:
                    </strong>
                    <p class="p-1 p-comun-disabled">
                        {{ $direccion }} </p>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4 col-4">
                    <strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-hash" viewBox="0 0 16 16">
                            <path
                                d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                        </svg>
                        NRO:
                    </strong>
                    <p class="p-1 p-comun-disabled">
                        {{ $nro }} </p>
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
                            {{ $userCreado }}
                        </li>
                        <li>
                            <strong>Fecha de registro:</strong>
                            {{ Carbon\Carbon::parse($fechaCreado)->format('d/m/Y') }} <b> Hrs:</b>
                            {{ Carbon\Carbon::parse($fechaCreado)->format('H:i A') }}
                        </li>

                    </ul>
                </div>
            </div>
            @if ($fueEditado == true)
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
    window.addEventListener('modalVerProveedor', event => {
        $('#verModalProveedor').modal('show');
    });
</script>
