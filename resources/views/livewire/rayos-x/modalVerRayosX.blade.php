<div>
    <x-ver-dos-modal id="verModalRayosX" labelledby="verModalRayosXLabel" title=" VISTA DE ASEGURADO TITULAR ">

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
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="d-flex align-items-center">
                                <label for="tipo" class="mb-0 mr-2">TIPO: </label>
                                @if ($tipo === true)
                                    <p class="p-2"
                                        style="display: inline-block; background-color:rgb(235, 201, 13);  font-weight: bold;  border-radius: 20px; font-size: 1rem;">
                                        ARCHIVO </p>
                                @else
                                    <p class="p-2"
                                        style="display: inline-block; background-color:rgb(87, 94, 97);color: white;  font-weight: bold;  border-radius: 20px; font-size: 1rem;">
                                        AFUERA </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <label for="nro_placas">NRO DE PLACAS: </label>
                            <input class="form-control input-comun-disabled " id="nro_placas" disabled
                                wire:model=nro_placas>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <label for="fecha_estudio">FECHA DE ESTUDIO: </label>
                            <input class="form-control input-comun-disabled" disabled id="fecha_estudio" type="date"
                                wire:model="fecha_estudio">
                        </div>
                        <div class="col-lg-5 col-md-4 col-sm-4 col-xs-4">
                            <label for="estudio_origen">ESTUDIO ORIGEN: </label>
                            <input class="form-control input-comun-disabled" disabled id="estudio_origen"
                                wire:model=estudio_origen>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>MÉDICO SOLICITANTE: </label>
                            <input class="form-control input-comun-disabled" disabled id="nombre_solicitante"
                                wire:model=nombre_solicitante>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="estudios">ESTUDIOS: </label>
                            <textarea class="form-control input-comun-disabled " disabled id="estudios" wire:model="estudios" rows="3"></textarea>
                            @error('estudios')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="observacion">OBSERVACIÓN: </label>
                            <textarea class="form-control input-comun-disabled " disabled id="observacion" wire:model="observacion" rows="3"></textarea>
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
    </x-ver-dos-modal>
</div>
<script>
    window.addEventListener('modalVerRayosX', event => {
        $('#verModalRayosX').modal('show');
    });
</script>
