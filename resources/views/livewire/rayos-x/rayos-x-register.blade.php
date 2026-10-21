<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="bi bi-file-x-fill"
                viewBox="0 0 16 16">
                <path
                    d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M6.854 6.146 8 7.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 8l1.147 1.146a.5.5 0 0 1-.708.708L8 8.707 6.854 9.854a.5.5 0 0 1-.708-.708L7.293 8 6.146 6.854a.5.5 0 1 1 .708-.708" />
            </svg>
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REGISTRO DE RAYOS X
            </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
        <div wire:loading wire:target="atras">
            <x-cargando-dos />
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card-comun p-2">
                <p style="background-color:#c4dff3 ;text-align:center;border-radius:6px">
                    <b>ASEGURADO</b>
                </p>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>NOMBRE COMPLETO:</strong>
                        <p class="p-1 p-comun-disabled">{{ $nombre_asegurado }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>TIPO ASEGURADO:</strong>
                        <p class="p-1 p-comun-disabled">{{ $tipo_asegurado }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <strong>C.I.:</strong>
                        <p class="p-1 p-comun-disabled">{{ $ci }} </p>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <strong>MATRICULA:</strong>
                        <p class="p-1 p-comun-disabled">{{ $matricula_asegurado }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <strong>EDAD:</strong>
                        <p class="p-1 p-comun-disabled">{{ $edad }} </p>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <strong>GÉNERO:</strong>
                        <p class="p-1 p-comun-disabled">{{ $genero }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>INSTITUCIÓN:</strong>
                        <p class="p-1 p-comun-disabled">{{ $institucion }} </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card-comun p-2">
                <div class="row ">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="d-flex align-items-center">
                            <label for="tipo" class="mb-0 mr-2">TIPO: <b style="color:#cb4335;">*</b></label>
                            <div class="d-flex align-items-center">
                                <label class="mb-0 mr-2" style="color:rgb(250, 192, 0)">ARCHIVO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-warning custom-switch-on-dark">
                                    <input wire:model="tipo" name="tipo" type="checkbox" class="custom-control-input"
                                        id="switchTipo" onchange="toggleFechaCamposRegistrar()">
                                    <label class="custom-control-label" for="switchTipo"
                                        style="margin-left: 0.5rem;"></label>
                                </div>
                                <label class="mb-0 ml-2" style="color:rgb(87, 94, 97)">AFUERA</label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <label for="nro_placas">NRO DE PLACAS: <b style="color:#cb4335;">*</b> </label>
                        <input class="form-control input-comun @error('nro_placas') is-invalid @enderror "
                            id="nro_placas" wire:model=nro_placas type="number">
                        @error('nro_placas')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <label for="fecha_estudio">FECHA DE ESTUDIO: <b style="color:#cb4335;">*</b> </label>
                        <input class="form-control input-comun @error('fecha_estudio') is-invalid @enderror "
                            id="fecha_estudio" type="date" wire:model="fecha_estudio"
                            max="{{ \Carbon\Carbon::now()->toDateString() }}">
                        @error('fecha_estudio')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-4 col-xs-4">
                        <label for="estudio_origen">ESTUDIO ORIGEN: <b style="color:#cb4335;">*</b> </label>
                        <select wire:model.live="estudio_origen" style="border-radius:10px" id="estudio_origen"
                            class="form-control @error('estudio_origen') is-invalid @enderror">
                            <option value = "" selected>Seleccione un estudio de origen .. </option>
                            <option value="INTERNO">INTERNO</option>
                            <option value="EXTERNO">EXTERNO</option>
                            <option value="OTROS">OTROS</option>
                        </select>
                        @error('estudio_origen')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    @if ($ingresoManual == true)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>INGRESO MANUAL MÉDICO SOLICITANTE: <b style="color:#cb4335;">*</b> </label>
                            <input class="form-control input-comun @error('nombre_solicitante') is-invalid @enderror "
                                id="nombre_solicitante" wire:model.live=nombre_solicitante
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nombre_solicitante')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    @else
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre_solicitante">MÉDICO SOLICITANTE: <b style="color:#cb4335;">*</b></label>
                            <select wire:model.live="nombre_solicitante" id="nombre_solicitante"
                                class="form-control input-comun @error('nombre_solicitante') is-invalid @enderror select2"
                                tabindex="-1">
                                <option selected="selected">Selecciona el médico</option>
                                @foreach ($solicitantes as $solicitante)
                                    <option value="{{ $solicitante->id }}">
                                        {{ $solicitante->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nombre_solicitante')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="estudios">ESTUDIOS: <b style="color:#cb4335;">*</b></label>
                        <textarea class="form-control input-comun @error('estudios') is-invalid @enderror" id="estudios"
                            wire:model="estudios" rows="3"></textarea>
                        @error('estudios')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="observacion">OBSERVACIÓN: <b style="color:#cb4335;">*</b></label>
                        <textarea class="form-control input-comun @error('observacion') is-invalid @enderror" id="observacion"
                            wire:model="observacion" rows="3"></textarea>
                        @error('observacion')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="cacelarRegistroRayosX" id="'cancelarAccion">
            CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarRayosX()">GUARDAR</x-save-button>
    </div>
</div>
<script>
    document.addEventListener('rayosXCancelado', function() {
        document.getElementById('cargando').style.display = 'block';
        Swal.fire({
            title: "Cancelado!",
            text: "Se canceló el registro",
            icon: "error"
        });
    });

    function guardarRayosX() {
        Swal.fire({
            title: '¿Registrar Rayos X?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // document.getElementById('cargando').style.display = 'block';
                Livewire.dispatch('crearRayosX');

            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                document.getElementById('cargando').style.display = 'none';

            }
        });
    }
    document.addEventListener('rayosXExitoso', function() {
        Swal.fire({
            title: "¡Guardado!",
            text: "Se guardó el registro exitosamente",
            icon: "success",
            showConfirmButton: false,
            timer: 1500,
        });
    });

    document.addEventListener('errorNuevo', function() {
        Swal.fire({
            title: "Ocurrio un error!",
            text: "Vuelve a intentarlo, si el error ocurre de nuevo comunicate con la unidad de sistemas",
            icon: "error",
            iconColor: "red"
        });
    });
</script>
