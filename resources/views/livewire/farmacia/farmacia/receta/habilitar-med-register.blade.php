<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">

            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;"> HABILITAR
                MEDICAMENTOS </h6>
        </div>
        <x-atras-button wire:click="atrasInicio" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="card p-3" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="cabecera text-center">
                            <b> ASEGURADO</b>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row d-flex flex-column align-items-center">
                            <div class="row d-flex flex-column align-items-center">
                                <img src="{{ !empty($foto_personal) ? $foto_personal : asset('images/default_afiliacion.jpg') }}"
                                    class="img-thumbnail"
                                    style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                    alt="Foto personal">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <span class="h3"
                            style="color: {{ $persona_institucion['estado_vigencia'] == 'VIGENTE'
                                ? '#196f3d'
                                : ($persona_institucion['estado_vigencia'] == 'NO VIGENTE'
                                    ? '#c0392b'
                                    : ($persona_institucion['estado_vigencia'] == 'CESANTE'
                                        ? '#e67e22'
                                        : '#000000')) }};">
                            <b>{{ $persona_institucion['estado_vigencia'] }}</b>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <span style="font-size:22px;"><b> {{ $edad }} Años </b></span>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRÍCULA :</strong>
                        <span class=" font-weight-bold h5 "
                            style="color:#1447E6;  ">{{ $persona->matricula_seguro ?? 'SIN DATOS' }} <span
                                style="font-weight:bold; color: #C0392B;"> {{ $codigo_institucional }} </span>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO ASEGURADO:</strong>
                        <span> {{ $persona->tipoAseguradoPersona->tipo_asegurado ?? 'SIN DATOS' }} </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO DE SANGRE:</strong>
                        <span> {{ $persona->grupo_sanguineo ?? 'SIN DATOS' }} </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">ALERGIAS:</strong>
                        <span> {{ $alergias ?? 'NINGUNA' }} </span>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> PACIENTE:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $persona->p_apellido ?? 'SIN DATOS' }}
                            {{ $persona->s_apellido ?? 'SIN DATOS' }}
                            {{ $persona->nombres ?? 'SIN DATOS' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        @if (count($instituciones) === 1)
                            @php
                                $ins = $instituciones[0];
                            @endphp

                            @if ($ins['nombre_institucion'] === 'APORTANTE VOLUNTARIO' || $ins['nombre_institucion'] === 'AFILIADO VOLUNTARIO')
                                <p><em><b>APORTANTE VOLUNTARIO</b></em></p>
                            @else
                                <p class="p-1">
                                    <b>INSTITUCIÓN: </b> {{ $ins['nombre_institucion'] }}
                                </p>
                            @endif
                        @else
                            <p><b>INSTITUCIÓN: </b> <em><b>{{ $institucion }} </b></em> </p>

                            @foreach ($instituciones as $ins)
                                @if ($ins['nombre_institucion'] != $institucion)
                                    <span class="p-0">- <em><b> {{ $ins['nombre_institucion'] }}</b></em> <span><br>
                                @endif
                            @endforeach

                        @endif


                    </div>
                </div>

            </div>

        </div>
        <div class="col-lg-9 col-md-9 col-sm-12">

            <div class="card-comun p-2">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
                        <div class="card cabecera">
                            <b> LISTA DE MEDICAMENTOS EN TRATAMIENTO</b>
                        </div>
                    </div>
                </div>
                <livewire:farmacia.farmacia.receta.receta-medicamento-table :id_persona="$id_persona" />

            </div>

        </div>

    </div>
</div>
<script>
    document.addEventListener('funcionAlertaMotivo', event => {
        Swal.fire({
            title: '¿Habilitar medicamento?',
            text: "Ingrese el motivo de la habilitación:",
            input: "text",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Sí, habilitar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Debe introducir el motivo.`);
                    return false;
                }
                return motivo_texto;
            },
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('medicamentoHabilitado', {
                    motivo: result.value
                });
            }
        });
    });

    document.addEventListener('medicamentoHabilitado2', function() {
        Swal.fire({
            title: "Medicamento Habilitado!",
            text: "Los medicamentos se actualizaron exitosamente!",
            icon: "success"
        });
    })
</script>
