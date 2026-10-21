<div>

    <div class="col-12 mb-3">
        <div class="alert  d-flex align-items-center shadow-sm rounded-3 p-3" role="alert"
            style="background-color: #e6f4f1;color:black">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#dfa01f"
                    class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                </svg>
                <strong>IMPORTANTE:</strong> La actualización de su <b>información personal y referencial</b> es
                fundamental. <br>
                Mantenga sus datos al día para contrastarlos con el <b>SEGIP</b>.
            </div>
        </div>
    </div>


    <div class="card-comun p-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class=" text-center">
                    <h3 style="color:#0077bd"><b>MIS DATOS</b></h3>
                    <hr style="color:#0077bd">
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-1 d-flex ">
                <span class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-person-vcard-fill " viewBox="0 0 16 16">
                        <path
                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                    </svg>
                </span>
                <p> {{ $primerDato->matricula_seguro }}</p>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-12  d-flex">
                <span class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                    </svg>
                </span>
                <p> {{ $primerDato->nombres . ' ' . $primerDato->p_apellido . ' ' . $primerDato->s_apellido }}</p>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-8 col-12 text-center  ">
                <p style="color:#0077bd ; font-size:20px"> <b>{{ $primerDato->tipo_asegurado }}</b></p>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between  ">
                <x-ver-button class="p-1 pr-2 pl-2" wire:click="verDatos({{ $primerDato->id }})">VER MIS
                    DATOS</x-ver-button>
                <x-siguiente-tres-button wire:click="formularioActualizacion({{ $primerDato->id }})">
                    SELECCIONAR</x-siguiente-tres-button>
            </div>
        </div>
    </div>
    <br>
    @if (array_slice($grupoFamiliar, 1))
        <div class="card-comun p-2">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class=" text-center">
                        <h3 style="color:#0077bd"><b>DATOS DE MIS BENEFICIARIOS</b></h3>

                    </div>
                </div>
            </div>
            @foreach (array_slice($grupoFamiliar, 1) as $item)
                <div class="card-borde p-2 mt-2">
                    <div class="col-lg-12 col-md-4 col-sm-12 col-12 mt-1 d-flex ">
                        <span class="mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                class="bi bi-person-vcard-fill " viewBox="0 0 16 16">
                                <path
                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                            </svg>
                        </span>
                        <p>{{ $item['matricula_seguro'] }}</p>
                    </div>
                    <div class="col-lg-12 col-md-8 col-sm-12 col-12 d-flex">
                        <span class="mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                        </span>
                        <p>{{ $item['nombres'] . ' ' . $item['p_apellido'] . ' ' . $item['s_apellido'] }}</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-8 col-12 text-center ">
                        <p style="color:#e2800a ;"><b>{{ $item['tipo_asegurado'] }}</b></p>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <x-ver-button class="p-1 pr-2 pl-2" wire:click="verDatos({{ $item['id'] }})">VER
                            DATOS</x-ver-button>
                        <x-siguiente-tres-button wire:click="formularioActualizacion({{ $item['id'] }})">
                            SELECCIONAR</x-siguiente-tres-button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div>@include('livewire.Administracion.contrastacion.modalVerDatosActualizacion')</div>
</div>
