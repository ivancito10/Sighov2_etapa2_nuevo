<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; 
        background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3); margin-bottom: 8px;">
        <div style="display: flex; align-items: center;">

            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                HISTORIA CLÍNICA
            </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>


    <div class ="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-3" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cabecera text-center">
                            <b> DATOS DEL PACIENTE</b>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row d-flex flex-column align-items-center">
                            <img src="{{ !empty($foto_personal) ? $foto_personal : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail"
                                style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                alt="Foto personal">
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        @if ($estado_vigencia == 'VIGENTE')
                            <span class="h3" style="color: #196f3d;"><b> VIGENTE </b></span>
                        @elseif($estado_vigencia == 'NO VIGENTE')
                            <span class="h3 parpadeo" style="color: #c0392b;"><b> NO VIGENTE </b></span>
                        @elseif($estado_vigencia == 'CESANTE')
                            <span class="h3 parpadeo" style="color: #ecb41d;"><b> CESANTE </b></span>
                        @else
                            <span class="h3"><b> {{ $estado_vigencia }} </b></span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <span style="font-size:22px;"><b>{{ $edad }} Años </b></span>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong style="font-size:90%">MATRÍCULA :</strong>
                        <span class=" font-weight-bold h5 "
                            style="color:#1447E6;  ">{{ $datos_paciente->matricula_seguro ?? 'SIN DATOS' }} <span
                                style="font-weight:bold; color: #C0392B;">{{ $codigo_institucional }}</span>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO ASEGURADO:</strong>
                        <span> {{ $datos_paciente->tipoAseguradoPersona->tipo_asegurado ?? 'SIN DATOS' }} </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO DE SANGRE:</strong>
                        <span> {{ $datos_paciente->grupo_sanguineo ?? 'SIN DATOS' }} </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <strong style="font-size:90%">ALERGIAS:</strong>
                        <span> {{ $alergias ?? 'NINGUNA' }} </span>

                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12" wire:click="mostrarInput">
                        <button class="btn btn-warning custom-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg> Agregar Alergia</button>

                        <div wire:loading wire:target="mostrarInput">
                            <x-cargando-dos />
                        </div>
                    </div>
                </div>

                @if ($mostrar_v1 == 1)
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-9 col-sm-9 text-center">
                            <label for="nueva_alergia" style="color: #C0392B;">ALERGIA NUEVA:</label>
                            <textarea wire:model.live="nueva_alergia" type="text" name="nueva_alergia" id="nueva_alergia" class="form-control"
                                placeholder="Ingrese la alergia" oninput="this.value = this.value.toUpperCase()" required
                                style="border-radius: 10px;" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center;" wire:click="cerrarInput">
                            <button class="btn btn-danger custom-btn" type="button"
                                style="border-radius: 10px 0px 0px 10px;">
                                Cancelar</button>
                            <button class="btn btn-success" type="button" style="border-radius: 0 10px 10px 0;"
                                wire:click="registrarAlergia">
                                Guardar
                            </button>

                            <div wire:loading wire:target="registrarAlergia">
                                <x-cargando-dos />
                            </div>
                            <div wire:loading wire:target="cerrarInput">
                                <x-cargando-dos />
                            </div>

                        </div>
                    </div>
                @endif
                <br>


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>NOMBRE DEL PACIENTE:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $datos_paciente->p_apellido ?? 'SIN DATOS' }}
                            {{ $datos_paciente->s_apellido ?? 'SIN DATOS' }}
                            {{ $datos_paciente->nombres ?? 'SIN DATOS' }}</p>
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


                <hr>
                <div class="row">
                    <div class="table-responsive m-0 p-0">
                        <table class="table" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="width: 25%; padding: 8px; text-align:center; border: none;">
                                        <button class="btn btn-danger" type="button" title="VER RECETAS"
                                            wire:click="verHistorialReceta">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                fill="currentColor" class="bi bi-capsule" viewBox="0 0 16 16">
                                                <path
                                                    d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                                            </svg>
                                        </button>
                                        <div wire:loading wire:target="verHistorialReceta">
                                            <x-cargando-dos />
                                        </div>
                                    </td>

                                    <td style="width: 25%; padding: 8px; text-align:center; border: none;">
                                        <button class="btn btn-info" type="button" title="VER LABORATORIO"
                                            wire:click="verHistorialLaboratorio">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                fill="currentColor" class="bi bi-flask-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M11.5 0a.5.5 0 0 1 0 1H11v5.358l4.497 7.36c.099.162.16.332.192.503l.013.063.008.083q.006.053.007.107l-.003.09q-.001.047-.005.095-.006.053-.017.106l-.016.079q-.012.049-.028.096l-.028.086a1.5 1.5 0 0 1-.17.322 1.5 1.5 0 0 1-.395.394q-.04.028-.082.054-.045.026-.095.049l-.073.035-.09.033q-.05.02-.103.034-.04.01-.08.017-.053.012-.108.021l-.006.002-.202.013H1.783l-.214-.015a1.503 1.503 0 0 1-1.066-2.268L5 6.359V1h-.5a.499.499 0 0 1-.354-.854A.5.5 0 0 1 4.5 0zm.5 12a.5.5 0 0 0 0 1h1.885l-.61-1zm-1-2a.5.5 0 0 0 0 1h1.664l-.612-1zm-1-2a.5.5 0 0 0 0 1h1.441l-.61-1zM9 6a.5.5 0 0 0 0 1h1.22l-.147-.24A.5.5 0 0 1 10 6.5V6zm0-2a.5.5 0 0 0 0 1h1V4zm0-2a.5.5 0 0 0 0 1h1V2z" />
                                            </svg></button>
                                        <div wire:loading wire:target="verHistorialLaboratorio">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                    <td style="width: 25%; padding: 8px; text-align:center; border: none;">
                                        <button class="btn btn-primary" type="button" title="VER IMAGENOLOGÍA"
                                            wire:click="verHistorialImagenologia">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                fill="currentColor" class="bi bi-lungs-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 1a.5.5 0 0 1 .5.5v5.243L9 7.1V4.72C9 3.77 9.77 3 10.72 3c.524 0 1.023.27 1.443.592.431.332.847.773 1.216 1.229.736.908 1.347 1.946 1.58 2.48.176.405.393 1.16.556 2.011.165.857.283 1.857.24 2.759-.04.867-.232 1.79-.837 2.33-.67.6-1.622.556-2.741-.004l-1.795-.897A2.5 2.5 0 0 1 9 11.264V8.329l-1-.715-1 .715V7.214c-.1 0-.202.03-.29.093l-2.5 1.786a.5.5 0 1 0 .58.814L7 8.329v2.935A2.5 2.5 0 0 1 5.618 13.5l-1.795.897c-1.12.56-2.07.603-2.741.004-.605-.54-.798-1.463-.838-2.33-.042-.902.076-1.902.24-2.759.164-.852.38-1.606.558-2.012.232-.533.843-1.571 1.579-2.479.37-.456.785-.897 1.216-1.229C4.257 3.27 4.756 3 5.28 3 6.23 3 7 3.77 7 4.72V7.1l.5-.357V1.5A.5.5 0 0 1 8 1m3.21 8.907a.5.5 0 1 0 .58-.814l-2.5-1.786A.5.5 0 0 0 9 7.214V8.33z" />
                                            </svg>
                                        </button>
                                        <div wire:loading wire:target="verHistorialImagenologia">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                    <td style="width: 25%; padding: 8px; border: none; text-align:center;">
                                        <button class="btn btn-success" type="button" title="BAJAS MÉDICAS"
                                            wire:click="verBajasMedicas" wire:loading.attr="disabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="23"
                                                fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                                <path
                                                    d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                                                <path
                                                    d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                                            </svg>

                                        </button>
                                        <div wire:loading wire:target="verBajasMedicas">
                                            <x-cargando-dos />
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-3" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                <div class="input-group pr-4" style="justify-content: space-between; ">
                    <div class="card cabecera">
                        <b style="font-size:18px;"> LISTA DE REGISTROS DE HISTORIA CLÍNICA</b>
                    </div>
                    <div class="d-flex">

                        <div style="margin-left: 7px;">
                            <x-agregar-button wire:click="ConsultaExternaRegister"> REGISTRAR NUEVA CONSULTA
                            </x-agregar-button>
                        </div>
                        <div wire:loading wire:target="ConsultaExternaRegister">
                            <x-cargando-dos />
                        </div>

                    </div>
                </div>
                <br>

                <div class="scroll-container-epi">
                    @if ($idPersona)
                        <livewire:atencion-medica.consulta-externa.historia-clinica.historia-clinica-table
                            :id_persona="$idPersona" />
                    @endif

                </div>
                @include('livewire.atencion-medica.consulta-externa.historia-clinica.modalVerHistoriaClinica')

                <!-- modales que muestran los detalles de los planes-->
                @include('livewire.atencion-medica.consulta-externa.baja-medica.modalVerBajaMedica')
                @include('livewire.atencion-medica.consulta-externa.fisioterapia.modalVerFisioterapia')
                @include('livewire.atencion-medica.consulta-externa.imagenologia.modalVerImagenologia')
                @include('livewire.atencion-medica.consulta-externa.internacion.modalVerInternacion')
                @include('livewire.atencion-medica.consulta-externa.laboratorio.modalVerLaboratorio')
                @include('livewire.atencion-medica.consulta-externa.receta.modalVerReceta')
                @include('livewire.atencion-medica.consulta-externa.referencia.modalVerReferencia')
                @include('livewire.atencion-medica.consulta-externa.interconsulta.modalVerInterconsulta')

                <!-- Modales para ver el historico de los planes-->
                @include('livewire.atencion-medica.consulta-externa.modalPlanImagenologia')
                @include('livewire.atencion-medica.consulta-externa.modalPlanRecetas')
                @include('livewire.atencion-medica.consulta-externa.modalPlanLaboratorio')
                @include('livewire.atencion-medica.consulta-externa.modalVerBajaMedica')
            </div>


        </div>
    </div>


    <style>
        .scroll-container-epi {
            max-height: 550px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        @keyframes parpadeo {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .parpadeo {
            animation: parpadeo 1s infinite;
        }

        .div-tabla-responsive {
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>
</div>

<script>
    document.addEventListener('sinApi', function() {
        Swal.fire({
            title: "Ocurrio un error",
            text: "El servicio de datos API no responde, comuniquese con la unidad de sistemas",
            icon: "error"
        });
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "Ocurrio un error",
            text: "Comuniquese con el administrador.",
            icon: "error"
        });
    });

    document.addEventListener('alergiaActualizada', function() {
        Swal.fire({
            title: "¡Actualizado!",
            text: "Los datos de alergia fueron actualizados correctamente.",
            icon: "success",
            showConfirmButton: false,
            timer: 1500,
        });
    });
</script>
