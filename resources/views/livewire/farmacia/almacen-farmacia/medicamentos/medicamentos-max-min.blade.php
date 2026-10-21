<div>
    <div class="card-borde p-2">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-8 col-8">
                <div class="card cabecera">
                    <b>DATOS DEL MEDICAMENTO</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label for="codigo_medicamento">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z" />
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                        <path
                            d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                    </svg>
                    CÓDIGO MEDICAMENTO: <spam style="color:#cb4335;"><b>*</b></spam>
                </label>
                <input type="text" class="form-control input-comun" id="codigo_medicamento" disabled
                    wire:model=codigo_medicamento>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                <label for="nombre">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-prescription2" viewBox="0 0 16 16">
                        <path d="M7 6h2v2h2v2H9v2H7v-2H5V8h2z" />
                        <path
                            d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v10.5a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 14.5V4a1 1 0 0 1-1-1zm2 3v10.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V4zM3 3h10V1H3z" />
                    </svg>
                    MEDICAMENTO: <spam style="color:#cb4335;"><b>*</b></spam></label>
                <input type="text" class="form-control input-comun" id="nombre" wire:model="nombre" disabled>
            </div>
        </div>
        <div style="height: 1rem;"></div>
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-8 col-8">
                <div class="card cabecera">
                    <b>VALORES MÁXIMOS Y MÍNIMOS DE MEDICAMENTO POR SERVICIO</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="idTipoConsulta">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-caret-down-square-fill" viewBox="0 0 16 16">
                        <path
                            d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z" />
                    </svg>
                    SERVICIO: <spam style="color:#cb4335;"><b>*</b></spam></label>
                <select wire:model="idTipoConsulta" name="idTipoConsulta" id="idTipoConsulta"
                    class="form-control input-comun @error('idTipoConsulta') is-invalid @enderror"
                    wire:change="buscarValoresActuales" style="width: 100%;" tabindex="-1" required>
                    <option selected value="">Seleccione el tipo de consulta</option>
                    @foreach ($tiposConsulta as $tipoConsulta)
                        <option value="{{ $tipoConsulta->id }}">{{ $tipoConsulta->nombre }}</option>
                    @endforeach
                </select>
                @error('idTipoConsulta')
                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                @enderror
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <label for="valorMinimo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-down-circle" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z" />
                    </svg>
                    VAL. MIN.: <spam style="color:#cb4335;"><b>*</b></spam>
                </label>
                <input type="number" class="form-control @error('valorMinimo') is-invalid @enderror input-comun"
                    placeholder="Valor mínimo" name="valorMinimo" id="valorMinimo" wire:model=valorMinimo>
                @error('valorMinimo')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <label for="valorMaximo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-up-circle" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z" />
                    </svg>
                    VAL. MAX.: <spam style="color:#cb4335;"><b>*</b></spam></label>
                <input type="number" class="form-control @error('valorMaximo') is-invalid @enderror input-comun"
                    placeholder="Valor máximo" name="valorMaximo" id="valorMaximo" wire:model="valorMaximo">
                @error('valorMaximo')
                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                @enderror
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 text-right mt-3">
                <x-save-button wire:loading.attr="disabled" wire:click="actualizarGuardarValor">
                    @if ($nuevosValoresSwitch)
                        GUARDAR
                    @else
                        ACTUALIZAR
                    @endif
                </x-save-button>
            </div>
        </div>

        <div style="height: 1rem;"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="cabecera-tabla-azul-oscuro">
                    <b>VALORES ACTUALES</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div style="max-height: 300px; overflow-y: auto;">
                    <table class="table tabla-azul-oscuro">
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

        <br>

        <div class="modal-footer pt-0"
            style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
            <x-cancel-button data-dismiss="modal"
                wire:click="cancelarRegistroValoresMedicamento">CERRAR</x-cancel-button>
        </div>

        <div wire:loading
            wire:target="actualizarGuardarValor, cancelarRegistroValoresMedicamento, buscarValoresActuales">
            <x-cargando-dos />
        </div>

        <div id="cargando-guardar-nuevos-valores" style="display: none;">
            <x-cargando-dos />
        </div>
    </div>

    <script>
        document.addEventListener('preguntarGuardarValor', function() {
            Swal.fire({
                title: '¿Desea registrar los nuevos valores?',
                text: "Se guardarán las cantidades ingresadas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarValores');
                    document.getElementById('cargando-guardar-nuevos-valores').style.display = 'block';
                }
            });
        })

        document.addEventListener('valoresRegistradosCorrectamente', function() {
            Swal.fire({
                title: '<b>Valores registrados</b>',
                text: 'Se actualizaron las cantidades correctamente',
                icon: 'success',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('valoresNoRegistradosCorrectamente', function() {
            Swal.fire({
                title: 'Error al registrar los valores',
                text: 'Verifique e intente nuevamente',
                icon: 'error',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('minimoMayorQueMaximo', function() {
            Swal.fire({
                title: 'La cantidad mínima es mayor a la cantidad máxima',
                text: 'Verifique los datos ingresados e intente nuevamente',
                icon: 'warning',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
    </script>
