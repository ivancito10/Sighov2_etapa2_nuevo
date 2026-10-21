<div>
    <div class="card-borde p-2">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="cabecera">
                    <b>DATOS DEL PACIENTE</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <label> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-person-fill mr-2" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                    </svg>NOMBRE COMPLETO:</label>
                <input type="text" class="form-control input-comun" value="{{ $nombreCompleto }}" readonly>
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                <label>MATRÍCULA:</label>
                <input type="text" class="form-control input-comun" value="{{ $matricula }}" readonly>
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                <label>C.I.:</label>
                <input type="text" class="form-control input-comun" value="{{ $ci }}" readonly>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-12 mt-2">
                <label>ESPECIALISTA:</label>
                <input type="text" class="form-control input-comun" value="{{ $especialista }}" readonly>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-12 mt-2">
                <label>ESPECIALIDAD:</label>
                <input type="text" class="form-control input-comun" value="{{ $especialidad }}" readonly>
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12 col-12 mt-2">
                <label>FECHA AGENDA:</label>
                <input type="text" class="form-control input-comun" value="{{ $fechaAgenda }}" readonly>
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12 col-12 mt-2">
                <label>HORA:</label>
                <input type="text" class="form-control input-comun" value="{{ $horaAgenda }}" readonly>
            </div>
        </div>
    </div>

    <br>

    <fieldset class="field_estilos">
        <legend>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-activity" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M6 2a.5.5 0 0 1 .485.379L7.89 8H10.5a.5.5 0 0 1 .485.379l.9 3.6 1.63-8.152A.5.5 0 0 1 14 3.5h1a.5.5 0 0 1 0 1h-.59l-1.92 9.598a.5.5 0 0 1-.98.023L10.11 9H7.5a.5.5 0 0 1-.485-.379L5.61 3H3.5a.5.5 0 0 1 0-1z" />
            </svg>
            <em>Registro de signos vitales:</em>
            <span>*</span>
        </legend>

        <div class="row">
            <div class="col-lg-3 mt-2">
                <label>PRESIÓN ARTERIAL (mmHg):</label>

                <input type="text" class="form-control @error('presionArterial') is-invalid @enderror input-comun"
                    placeholder="Ej: 120/80" wire:model="presionArterial">

                @error('presionArterial')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-3 mt-2">
                <label>FRECUENCIA RESPIRATORIA (rpm):</label>
                <input type="number"
                    class="form-control @error('frecuenciaRespiratoria') is-invalid @enderror input-comun"
                    placeholder="Ej:18" wire:model="frecuenciaRespiratoria">

                @error('frecuenciaRespiratoria')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-3 mt-2">
                <label><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-thermometer mr-2" viewBox="0 0 16 16">
                        <path d="M8 14a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                        <path
                            d="M8 0a2.5 2.5 0 0 0-2.5 2.5v7.55a3.5 3.5 0 1 0 5 0V2.5A2.5 2.5 0 0 0 8 0M6.5 2.5a1.5 1.5 0 1 1 3 0v7.987l.167.15a2.5 2.5 0 1 1-3.333 0l.166-.15z" />
                    </svg>TEMPERATURA (°C):</label>

                <input type="number" step="0.01" min="0"
                    class="form-control @error('temperatura') is-invalid @enderror input-comun" placeholder="Ej: 36.5"
                    wire:model="temperatura">

                @error('temperatura')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-3 mt-2">
                <label><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-heart-pulse mr-2" viewBox="0 0 16 16">
                        <path
                            d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857q.09.083.176.171a3 3 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01zM2.212 10h1.315C4.593 11.183 6.05 12.458 8 13.795c1.949-1.337 3.407-2.612 4.473-3.795h1.315c-1.265 1.566-3.14 3.25-5.788 5-2.648-1.75-4.523-3.434-5.788-5" />
                        <path
                            d="M10.464 3.314a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.5a.5.5 0 0 0 0 1H4a.5.5 0 0 0 .416-.223l1.473-2.209 1.647 4.118a.5.5 0 0 0 .945-.049l1.598-5.593 1.457 3.642A.5.5 0 0 0 12 9h3.5a.5.5 0 0 0 0-1h-3.162z" />
                    </svg>FRECUENCIA CARDIACA (lpm/ppm):</label>

                <input type="number" class="form-control @error('frecuenciaCardiaca') is-invalid @enderror input-comun"
                    placeholder="Ej: 72" wire:model="frecuenciaCardiaca">

                @error('frecuenciaCardiaca')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-3 mt-2">
                <label><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-lungs mr-2" viewBox="0 0 16 16">
                        <path
                            d="M8.5 1.5a.5.5 0 1 0-1 0v5.243L7 7.1V4.72C7 3.77 6.23 3 5.28 3c-.524 0-1.023.27-1.443.592-.431.332-.847.773-1.216 1.229-.736.908-1.347 1.946-1.58 2.48-.176.405-.393 1.16-.556 2.011-.165.857-.283 1.857-.241 2.759.04.867.233 1.79.838 2.33.67.6 1.622.556 2.741-.004l1.795-.897A2.5 2.5 0 0 0 7 11.264V10.5a.5.5 0 0 0-1 0v.764a1.5 1.5 0 0 1-.83 1.342l-1.794.897c-.978.489-1.415.343-1.628.152-.28-.25-.467-.801-.505-1.63-.037-.795.068-1.71.224-2.525.157-.82.357-1.491.491-1.8.19-.438.75-1.4 1.44-2.25.342-.422.703-.799 1.049-1.065.358-.276.639-.385.833-.385a.72.72 0 0 1 .72.72v3.094l-1.79 1.28a.5.5 0 0 0 .58.813L8 7.614l3.21 2.293a.5.5 0 1 0 .58-.814L10 7.814V4.72a.72.72 0 0 1 .72-.72c.194 0 .475.11.833.385.346.266.706.643 1.05 1.066.688.85 1.248 1.811 1.439 2.249.134.309.334.98.491 1.8.156.814.26 1.73.224 2.525-.038.829-.224 1.38-.505 1.63-.213.19-.65.337-1.628-.152l-1.795-.897A1.5 1.5 0 0 1 10 11.264V10.5a.5.5 0 0 0-1 0v.764a2.5 2.5 0 0 0 1.382 2.236l1.795.897c1.12.56 2.07.603 2.741.004.605-.54.798-1.463.838-2.33.042-.902-.076-1.902-.24-2.759-.164-.852-.38-1.606-.558-2.012-.232-.533-.843-1.571-1.579-2.479-.37-.456-.785-.897-1.216-1.229C11.743 3.27 11.244 3 10.72 3 9.77 3 9 3.77 9 4.72V7.1l-.5-.357z" />
                    </svg>SATURACIÓN OXÍGENO (%):</label>

                <input type="number" class="form-control @error('saturacionOxigeno') is-invalid @enderror input-comun"
                    placeholder="Ej: 98" wire:model="saturacionOxigeno">

                @error('saturacionOxigeno')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-3 mt-2">
                <label><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-speedometer mr-2" viewBox="0 0 16 16">
                        <path
                            d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2M3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.39.39 0 0 0-.029-.518z" />
                        <path fill-rule="evenodd"
                            d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.95 11.95 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0" />
                    </svg>PESO (KG):</label>
                <input type="number" step="0.01" min="0"
                    class="form-control @error('peso') is-invalid @enderror input-comun" placeholder="Ej: 72.50"
                    wire:model="peso">

                @error('peso')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-3 mt-2">
                <label><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-rulers mr-2" viewBox="0 0 16 16">
                        <path
                            d="M1 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h5v-1H2v-1h4v-1H4v-1h2v-1H2v-1h4V9H4V8h2V7H2V6h4V2h1v4h1V4h1v2h1V2h1v4h1V4h1v2h1V2h1v4h1V1a1 1 0 0 0-1-1z" />
                    </svg>TALLA (CM):</label>
                <input type="number" step="0.01" min="0"
                    class="form-control @error('talla') is-invalid @enderror input-comun" placeholder="Ej: 172"
                    wire:model="talla">

                @error('talla')
                    <span class="form-text text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </fieldset>
    <br>
    <div class="modal-footer pt-0" style="border: none; background-color: white;">
        <x-cancel-button data-dismiss="modal" wire:click="cancelarRegistro">CANCELAR</x-cancel-button>
        <x-save-button wire:loading.attr="disabled" wire:click="preguntarGuardarSignosVitales">GUARDAR</x-save-button>
    </div>

    <div wire:loading wire:target="preguntarGuardarSignosVitales, cancelarRegistro">
        <x-cargando-dos />
    </div>

    <div id="cargando-guardar-signos" style="display: none;">
        <x-cargando-dos />
    </div>
</div>


<script>
    document.addEventListener('preguntaGuardarSignos', function() {
        Swal.fire({
            title: '¿Guardar signos vitales?',
            text: "Los datos de signos vitales serán registrados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarSignosVitales');
                document.getElementById('cargando-guardar-signos').style.display =
                    'block';
                Swal.fire({
                    title: "¡Registro exitoso!",
                    text: "El registro fue realizado exitosamente.",
                    icon: "success",
                })
                $('#registroSignosVitalesModal').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Usted canceló el registro.",
                    icon: "warning"
                });
                Livewire.dispatch('cancelarRegistroMedicamento');
                $('#registroSignosVitalesModal').modal('hide');
            }
        });
    });

</script>
