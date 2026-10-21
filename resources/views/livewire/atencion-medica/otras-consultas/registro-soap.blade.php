<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; 
        background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3); margin-bottom:8px;">
        <div style="display: flex; align-items: center;">

            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                CONSULTA A REGISTRAR</h6>
        </div>
        <x-atras-button onclick="boton_atras()" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <div>

        <div class="row">
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                            <strong style="font-size:90%">MATRICULA :</strong>
                            <span class=" font-weight-bold h5 "
                                style="color:#1447E6;">{{ $datos_paciente->matricula_seguro ?? 'SIN DATOS' }} <span
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <strong style="font-size:90%">ALERGIAS:</strong>
                            <span> {{ $alergias ?? 'NINGUNA' }} </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12" wire:click="mostrarInput">
                            <button class="btn btn-warning custom-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
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
                            <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center;"
                                wire:click="cerrarInput">
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
                            <strong> NOMBRE DEL PACIENTE:</strong>
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
                                        <span class="p-0">- <em><b> {{ $ins['nombre_institucion'] }}</b></em>
                                            <span><br>
                                    @endif
                                @endforeach

                            @endif

                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="width: 25%; padding: 8px; border: none; text-align:center;">
                                            <button class="btn btn-danger" type="button" title="VER RECETAS"
                                                wire:click="verRecetas" wire:loading.attr="disabled">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                    fill="currentColor" class="bi bi-capsule" viewBox="0 0 16 16">
                                                    <path
                                                        d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                                                </svg>
                                            </button>
                                            <div wire:loading wire:target="verRecetas">
                                                <x-cargando-dos />
                                            </div>
                                        </td>

                                        <td style="width: 25%; padding: 8px; border: none; text-align:center;">
                                            <button class="btn btn-info" type="button" title="VER LABORATORIO"
                                                wire:click="verLaboratorio" wire:loading.attr="disabled">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                    fill="currentColor" class="bi bi-flask-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.5 0a.5.5 0 0 1 0 1H11v5.358l4.497 7.36c.099.162.16.332.192.503l.013.063.008.083q.006.053.007.107l-.003.09q-.001.047-.005.095-.006.053-.017.106l-.016.079q-.012.049-.028.096l-.028.086a1.5 1.5 0 0 1-.17.322 1.5 1.5 0 0 1-.395.394q-.04.028-.082.054-.045.026-.095.049l-.073.035-.09.033q-.05.02-.103.034-.04.01-.08.017-.053.012-.108.021l-.006.002-.202.013H1.783l-.214-.015a1.503 1.503 0 0 1-1.066-2.268L5 6.359V1h-.5a.499.499 0 0 1-.354-.854A.5.5 0 0 1 4.5 0zm.5 12a.5.5 0 0 0 0 1h1.885l-.61-1zm-1-2a.5.5 0 0 0 0 1h1.664l-.612-1zm-1-2a.5.5 0 0 0 0 1h1.441l-.61-1zM9 6a.5.5 0 0 0 0 1h1.22l-.147-.24A.5.5 0 0 1 10 6.5V6zm0-2a.5.5 0 0 0 0 1h1V4zm0-2a.5.5 0 0 0 0 1h1V2z" />
                                                </svg></button>
                                            <div wire:loading wire:target="verLaboratorio">
                                                <x-cargando-dos />
                                            </div>
                                        </td>
                                        <td style="width: 25%; padding: 8px; border: none; text-align:center;">
                                            <button class="btn btn-primary" type="button" title="VER IMAGENOLOGÍA"
                                                wire:click="verImagenologia" wire:loading.attr="disabled">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                    fill="currentColor" class="bi bi-lungs-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 1a.5.5 0 0 1 .5.5v5.243L9 7.1V4.72C9 3.77 9.77 3 10.72 3c.524 0 1.023.27 1.443.592.431.332.847.773 1.216 1.229.736.908 1.347 1.946 1.58 2.48.176.405.393 1.16.556 2.011.165.857.283 1.857.24 2.759-.04.867-.232 1.79-.837 2.33-.67.6-1.622.556-2.741-.004l-1.795-.897A2.5 2.5 0 0 1 9 11.264V8.329l-1-.715-1 .715V7.214c-.1 0-.202.03-.29.093l-2.5 1.786a.5.5 0 1 0 .58.814L7 8.329v2.935A2.5 2.5 0 0 1 5.618 13.5l-1.795.897c-1.12.56-2.07.603-2.741.004-.605-.54-.798-1.463-.838-2.33-.042-.902.076-1.902.24-2.759.164-.852.38-1.606.558-2.012.232-.533.843-1.571 1.579-2.479.37-.456.785-.897 1.216-1.229C4.257 3.27 4.756 3 5.28 3 6.23 3 7 3.77 7 4.72V7.1l.5-.357V1.5A.5.5 0 0 1 8 1m3.21 8.907a.5.5 0 1 0 .58-.814l-2.5-1.786A.5.5 0 0 0 9 7.214V8.33z" />
                                                </svg>
                                            </button>
                                            <div wire:loading wire:target="verImagenologia">
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

                <div class="row p-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="font-size: 18px;">


                        <span style="color:#1976D2;  
                                    font-weight: bold;">
                            @if ($tipo_consulta == 2)
                                <span>
                                    EMERGENCIAS - </span>
                            @elseif ($tipo_consulta == 3)
                                <span>
                                    CONSULTORIO DE CONTINGENCIAS -
                                </span>
                            @elseif ($tipo_consulta == 4)
                                <span>
                                    HOSPITALIZACIÓN -
                                </span>
                            @elseif ($tipo_consulta == 5)
                                <span>
                                    TERAPIA INTENSIVA -
                                </span>
                            @elseif ($tipo_consulta == 6)
                                <span>
                                    QUIRÓFANO -
                                </span>
                            @elseif ($tipo_consulta == 7)
                                <span>
                                    TRANSCRIPCIÓN -
                                </span>
                            @elseif ($tipo_consulta == 8)
                                <span>
                                    CONSULTA EXTERNA EXTRA INSTITUCIONAL -
                                </span>
                            @elseif ($tipo_consulta == 9)
                                <span>
                                    HEMODIÁLISIS -
                                </span>
                            @else
                                <span> <b>CONSULTA EXTERNA - </b></span>
                            @endif

                        </span>
                        @if ($reconsulta == false)
                            <span
                                style="  background-color: #D4EFDF;
                                        border-radius: 8px;
                                        padding: 0.25rem;
                                        color: #1E8449;
                                        text-align:center;">
                                <b>
                                    CONSULTA NUEVA </b>
                            </span>
                        @elseif($reconsulta == true)
                            <span
                                style="  background-color: #FDEBD0;
                                    border-radius: 8px;
                                    padding: 0.25rem;
                                    color: #D68910;
                                    text-align:center;">
                                <b>
                                    RECONSULTA </b>
                            </span>
                        @endif

                    </div>

                </div>



                <div class="card-comun p-3">
                    <strong style="color: #1F618D; font-size:18px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>

                        Subjetivo </strong>


                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="motivo_consulta">MOTIVO CONSULTA:<b style="color:#cb4335;"> *
                                </b></span></label>
                            <textarea id="motivo_consulta" class="form-control @error('motivo_consulta') is-invalid @enderror"
                                wire:model="motivo_consulta" style="border-radius:8px;" placeholder="Ingrese el motivo de consulta..."></textarea>
                            @error('motivo_consulta')
                                <div class="text-danger">
                                    <b>El campo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="enfermedad_actual">ENFERMEDAD ACTUAL:
                                </span></label>
                            <textarea id="enfermedad_actual" class="form-control @error('enfermedad_actual') is-invalid @enderror"
                                wire:model="enfermedad_actual" style="border-radius:8px;" placeholder="Ingrese la enefermedada actual..."></textarea>
                            @error('enfermedad_actual')
                                <div class="text-danger">
                                    <b>El campo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>

                    <strong style="color: #1F618D; font-size:18px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>

                        Objetivo <b style="color: #cb4335;"> *</b>
                    </strong>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <textarea type="text" class="form-control @error('objetivo') is-invalid @enderror" wire:model="objetivo"
                                style="border-radius:8px" placeholder="Ingrese el objetivo..." rows="2"></textarea>
                            @error('objetivo')
                                <div class="text-danger">
                                    <b>El campo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <br>
                    <strong style="color: #1F618D; font-size:18px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> Analítico
                    </strong>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <span><b> DIAGNÓSTICO CIE 10: </b> <b style="color:#cb4335;"> *</b> </span>

                            <x-agregar-button wire:click="abrirModalCie10" title="REGISTRAR DIAGNOSTICO CIE 10"
                                wire:loading.attr="disabled">
                                Registrar Diagnóstico CIE-10
                            </x-agregar-button>

                        </div>
                    </div>

                    <br>
                    @if (count($diagnosticosSeleccionados) > 0)

                        <table class="table tabla-azul-oscuro">
                            <thead style="background-color:#2471a3 ; color:white; text-align:center;">
                                <tr>
                                    <th colspan="3" style="padding-top: 0; padding-bottom: 0;">
                                        DIAGNÓSTICO CIE-10 REGISTRADOS<br></th>
                                </tr>
                                <tr>
                                    <th style="padding-top: 0; padding-bottom: 0;">CÓDIGO</th>
                                    <th style="padding-top: 0; padding-bottom: 0;">DESCRIPCIÓN</th>
                                    <th style="padding-top: 0; padding-bottom: 0;">OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diagnosticosSeleccionados as $diagnostico)
                                    <tr>
                                        <td style="text-align:center;">{{ $diagnostico->codigo }}</td>
                                        <td>{{ $diagnostico->descripcion }}</td>
                                        <td style="text-align:center;">
                                            <button type="button" class="btn btn-danger" title="ELIMINAR"
                                                wire:click="eliminarDiagnostico({{ $diagnostico->id }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p style="text-align:center; color: #797D7F;">
                            <b> <em> No hay registros de diagnosticos CIE-10 aún.</em>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="orange"
                                    class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                </svg>
                            </b>
                        </p>
                    @endif


                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="descriptivo" class="form-label"> DIAGNÓSTICO DESCRIPTIVO:</label>
                            <textarea id="descriptivo" class="form-control @error('descriptivo') is-invalid @enderror" wire:model="descriptivo"
                                placeholder="Ingrese el diagnóstico descriptivo..." style="border-radius: 10px; padding: 10px 15px;"
                                rows="2"></textarea>
                            @error('descriptivo')
                                <div class="text-danger">
                                    <b>El campo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                    </div>



                    <br>
                    @if (!$historialGuardado)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                <x-siguiente-dos-button onclick="registrarHistorialClinico()"
                                    title="REGISTRAR HISTORIAL CLÍNICO"> CONTINUAR CONSULTA </x-siguiente-dos-button>
                            </div>
                        </div>
                    @endif




                </div>

                @if ($historialGuardado)

                    <div class="card-comun p-3">
                        <strong style="color: #1F618D; font-size:18px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>

                            Plan </strong>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="observacion_planes" class="form-label"> OBSERVACIONES: </label>
                                <textarea id="observacion_planes" class="form-control" wire:model="observacion_planes"
                                    placeholder="Ingrese la observación..." style="border-radius: 10px; padding: 10px 15px;" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center flex-wrap p-2" style="gap: 10px;" wire:ignore>

                        <button class="btn btn-danger mb-2" type="button" title="RECETA"
                            wire:click="registrar_receta" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                fill="currentColor" class="bi bi-capsule" viewBox="0 0 16 16">
                                <path
                                    d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                            </svg> <br> RECETA
                        </button>

                        <div class="dropup mb-2">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"
                                title="LABORATORIO" aria-expanded="false" wire:loading.attr="disabled"
                                wire:target="registrar_laboratorio">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    fill="currentColor" class="bi bi-flask-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M11.5 0a.5.5 0 0 1 0 1H11v5.358l4.497 7.36c.099.162.16.332.192.503l.013.063.008.083q.006.053.007.107l-.003.09q-.001.047-.005.095-.006.053-.017.106l-.016.079q-.012.049-.028.096l-.028.086a1.5 1.5 0 0 1-.17.322 1.5 1.5 0 0 1-.395.394q-.04.028-.082.054-.045.026-.095.049l-.073.035-.09.033q-.05.02-.103.034-.04.01-.08.017-.053.012-.108.021l-.006.002-.202.013H1.783l-.214-.015a1.503 1.503 0 0 1-1.066-2.268L5 6.359V1h-.5a.499.499 0 0 1-.354-.854A.5.5 0 0 1 4.5 0zm.5 12a.5.5 0 0 0 0 1h1.885l-.61-1zm-1-2a.5.5 0 0 0 0 1h1.664l-.612-1zm-1-2a.5.5 0 0 0 0 1h1.441l-.61-1zM9 6a.5.5 0 0 0 0 1h1.22l-.147-.24A.5.5 0 0 1 10 6.5V6zm0-2a.5.5 0 0 0 0 1h1V4zm0-2a.5.5 0 0 0 0 1h1V2z" />
                                </svg> <br> LABORATORIO
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" wire:click="registrar_laboratorio(1)">LAB.
                                    CLINICO</a>
                                <a class="dropdown-item" wire:click="registrar_laboratorio(2)">BACTERIOLOGIA</a>
                                <a class="dropdown-item" wire:click="registrar_laboratorio(3)">PATOLIGIA</a>
                                <a class="dropdown-item" wire:click="registrar_laboratorio(4)">LAB.
                                    EXTERNOS</a>
                                <a class="dropdown-item" wire:click="registrar_laboratorio(5)">OTROS
                                    LAB</a>
                            </div>
                        </div>

                        <div class="dropdown mb-2">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"
                                title="IMAGENOLOGÍA" aria-expanded="false" wire:loading.attr="disabled"
                                wire:target="registrar_imagenologia">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="27"
                                    fill="currentColor" class="bi bi-lungs-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 1a.5.5 0 0 1 .5.5v5.243L9 7.1V4.72C9 3.77 9.77 3 10.72 3c.524 0 1.023.27 1.443.592.431.332.847.773 1.216 1.229.736.908 1.347 1.946 1.58 2.48.176.405.393 1.16.556 2.011.165.857.283 1.857.24 2.759-.04.867-.232 1.79-.837 2.33-.67.6-1.622.556-2.741-.004l-1.795-.897A2.5 2.5 0 0 1 9 11.264V8.329l-1-.715-1 .715V7.214c-.1 0-.202.03-.29.093l-2.5 1.786a.5.5 0 1 0 .58.814L7 8.329v2.935A2.5 2.5 0 0 1 5.618 13.5l-1.795.897c-1.12.56-2.07.603-2.741.004-.605-.54-.798-1.463-.838-2.33-.042-.902.076-1.902.24-2.759.164-.852.38-1.606.558-2.012.232-.533.843-1.571 1.579-2.479.37-.456.785-.897 1.216-1.229C4.257 3.27 4.756 3 5.28 3 6.23 3 7 3.77 7 4.72V7.1l.5-.357V1.5A.5.5 0 0 1 8 1m3.21 8.907a.5.5 0 1 0 .58-.814l-2.5-1.786A.5.5 0 0 0 9 7.214V8.33z" />
                                </svg><br> IMAGENOLOGÍA
                            </button>
                            <div class="dropdown-menu">
                                @foreach ($tipos_imagenologia as $tipo)
                                    <a class="dropdown-item"
                                        wire:click="registrar_imagenologia({{ $tipo->id }})">{{ $tipo->nombre }}</a>
                                @endforeach
                            </div>
                        </div>


                        <button class="btn btn-success mb-2" type="button" title=" BAJA MÉDICA"
                            wire:click="registrar_baja_medica" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="23"
                                fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                <path
                                    d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                                <path
                                    d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                            </svg><br> BAJA MÉDICA
                        </button>

                        <button class="btn btn-warning mb-2" type="button" title="FISIOTERAPIA"
                            style="background-color:  #1f618d; color: white; border: 1px solid #1f618d;"
                            wire:click="registrar_fisioterapia" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="23"
                                viewBox="0 -960 960 960" width="30px" fill="currentColor"
                                style="margin-bottom: 5px;">
                                <path
                                    d="M200-120v-120q0-33 23.5-56.5T280-320h400q33 0 56.5 23.5T760-240v120H200Zm40-80h480v-40q0-17-11.5-28.5T680-240H280q-17 0-28.5 11.5T240-200v40Zm120-200q-33 0-56.5-23.5T280-480q0-33 23.5-56.5T360-560q33 0 56.5 23.5T440-480q0-33-23.5-56.5T360-560Zm240 0q-33 0-56.5-23.5T520-480q0-33 23.5-56.5T600-560q33 0 56.5 23.5T680-480q0 33-23.5 56.5T600-400Zm-120-160q-33 0-56.5-23.5T400-640q0-33 23.5-56.5T480-720q33 0 56.5 23.5T560-640q0 33-23.5 56.5T480-560Zm0-200q-33 0-56.5-23.5T400-840q0-33 23.5-56.5T480-920q33 0 56.5 23.5T560-840q0 33-23.5 56.5T480-760Z" />
                            </svg>
                            <br> FISIOTERAPIA
                        </button>


                        <button class="btn btn-warning mb-2" type="button" title=" INTERNACIÓN"
                            style="background-color: #f39c12; color: white; border: 1px solid #f39c12;"
                            wire:click="registrar_internacion" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="30"
                                fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                <path
                                    d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
                            </svg> <br>INTERNACIÓN
                        </button>

                        <button class="btn btn-warning mb-2" type="button" title=" REFERENCIA"
                            style="background-color: #884ea0; color: white; border: 1px solid #884ea0;"
                            wire:click="registrar_referencia" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27"
                                fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                <path fill-rule="evenodd"
                                    d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                            </svg> <br> REFERENCIA
                        </button>

                        <button class="btn btn-warning mb-2" type="button" title="INTERCONSULTA"
                            style="background-color: rgb(196, 169, 21); color: white; border: 1px solid rgb(196, 169, 21);"
                            wire:click="registrar_interconsulta" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27"
                                fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5z" />
                                <path fill-rule="evenodd"
                                    d="M15 4.5a.5.5 0 0 0-.5-.5H2.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 1 0 .708-.708L2.707 5H14.5a.5.5 0 0 0 .5-.5z" />
                            </svg>
                            <br> INTERCONSULTA
                        </button>
                    </div>
                @endif

            </div>

        </div>
        <br>

        <div wire:loading wire:target="registrar_receta">
            <x-cargando-dos />
        </div>
        <div wire:loading wire:target="registrar_laboratorio">
            <x-cargando-dos />
        </div>
        <div wire:loading wire:target="registrar_imagenologia">
            <x-cargando-dos />
        </div>
        <div wire:loading wire:target="registrar_baja_medica">
            <x-cargando-dos />
        </div>
        <div wire:loading wire:target="registrar_fisioterapia">
            <x-cargando-dos />
        </div>
        <div wire:loading wire:target="registrar_internacion">
            <x-cargando-dos />
        </div>
        <div wire:loading wire:target="registrar_referencia">
            <x-cargando-dos />
        </div>
        <div wire:loading wire:target="registrar_interconsulta">
            <x-cargando-dos />
        </div>

        @if ($historialGuardado)

            @if ($planesRegistrados != null)
                <div class="row">
                    <table class="table table-striped table-bordered">
                        <thead style="background-color:#2471a3 ; color:white; text-align:center; height:20px;">
                            <tr>
                                <th colspan="4" style="padding-top: 0; padding-bottom: 0;"> LISTADO DE PLANES
                                    REGISTRADOS</th>
                            </tr>
                            <tr>
                                <th style="padding-top: 0; padding-bottom: 0;">#</th>
                                <th style="padding-top: 0; padding-bottom: 0;">NOMBRE DEL PLAN</th>
                                <th style="padding-top: 0; padding-bottom: 0;">NRO. DEL PLAN </th>
                                <th style="padding-top: 0; padding-bottom: 0;">OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($planesRegistrados as $index => $plan)
                                <tr>
                                    <td style="text-align:center;">{{ $index + 1 }}</td>
                                    <td>{{ $plan->Planes->nombre ?? 'Sin nombre' }}</td>
                                    <td>{{ $plan->codigo ?? 'Sin datos' }}</td>
                                    <td style="text-align:center;">
                                        <button class="btn btn-danger"
                                            wire:click="funcion_hc_planes({{ $plan['id'] }} , {{ $plan['id_historia_clinica'] }} , {{ $plan['id_plan'] }} , {{ $plan['id_corresponde'] }} )"
                                            title="IMPRIMIR">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-file-earmark-pdf-fill"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z" />
                                                <path fill-rule="evenodd"
                                                    d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay planes registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="modal-footer " style="border: none; background-color: white;">
                        <x-agregar-button onclick="finalizarRegistro()" title="FINALIZAR"> GUARDAR Y
                            FINALIZAR</x-agregar-button>
                    </div>
                </div>
            </div>

        @endif

        @include('livewire.atencion-medica.consulta-externa.modalPlanImagenologia')
        @include('livewire.atencion-medica.consulta-externa.modalPlanRecetas')
        @include('livewire.atencion-medica.consulta-externa.modalPlanLaboratorio')
        @include('livewire.atencion-medica.consulta-externa.modalVerBajaMedica')

        @include('livewire.atencion-medica.consulta-externa.modalRegistrarReceta')
        @include('livewire.atencion-medica.consulta-externa.modalRegistrarLaboratorio')
        @include('livewire.atencion-medica.consulta-externa.modalRegistrarImagenologia')
        @include('livewire.atencion-medica.consulta-externa.modalRegistrarBajaMedica')
        @include('livewire.atencion-medica.consulta-externa.modalRegistrarFisioterapia')
        @include('livewire.atencion-medica.consulta-externa.modalRegistrarInternacion')
        @include('livewire.atencion-medica.consulta-externa.modalRegistrarReferencia')
        @include('livewire.atencion-medica.consulta-externa.modalRegistrarInterconsulta')
        @include('livewire.atencion-medica.consulta-externa.historia-clinica.modalSubjetivo')
        @include('livewire.atencion-medica.consulta-externa.historia-clinica.modalSelectCie10')

        @include('livewire.atencion-medica.consulta-externa.historia-clinica.modalVerPlanFtp')



    </div>

    <!-- icono para fisioterapia -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=personal_injury" />
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
            vertical-align: middle;
        }

        /* Cambia el color al pasar el mouse */
        .dropdown-menu .dropdown-item:hover,
        .dropdown-menu .dropdown-item:focus,
        .dropdown-menu .dropdown-item:active {
            background-color: #007bff !important;
            /* Azul Bootstrap */
            color: white !important;
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
    function registrarHistorialClinico() {
        Swal.fire({
            title: '¿Guardar el Subjetivo, Objetivo y Analítico?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarRegistroHC');
            }
        });
    }

    function finalizarRegistro() {
        Swal.fire({
            title: '¿Guardar la Consulta?',
            text: "Se registrarán todos los datos ingresados del SOAP.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guarda_y_atras');
            }
        });
    }

    document.addEventListener('consultaExternaCreada', function() {
        Swal.fire({
            title: "¡Subjetivo, Objetivo y Analítico Guardado!",
            text: "Los datos fueron guardados.",
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
        });
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "¡Error!",
            text: "Ocurrio un error, comuniquese con el administrador",
            icon: "error"
        });
    });

    document.addEventListener('medicoInhabilitado', function() {
        Swal.fire({
            title: '¡Médico inhabilitado!',
            text: 'Usted no se encuentra habilitado como especialista',
            icon: 'warning',
            confirmButtonText: 'OK',
            allowOutsideClick: false, // no cierra al hacer click afuera
            allowEscapeKey: false, // no cierra con ESC
            allowEnterKey: true // Enter confirma (opcional)
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('atras_historia_clinica');
            }
        });
    });

    document.addEventListener('seleccioneDiagnosticoCie10', function() {
        Swal.fire({
            title: "¡Selección de CIE-10 Requerida!",
            text: "Por favor, seleccione al menos un diagnóstico de CIE-10 para continuar.",
            icon: "warning",
            showConfirmButton: false,
            timer: 1800,
        });
    });

    function boton_atras() {
        Swal.fire({
            title: '¿Salir del Registro?',
            text: "La consulta no será registrada y los datos no se guardarán.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('atras_historia_clinica');
            }
        });
    }

    document.addEventListener('soapCreada', function() {
        Swal.fire({
            title: "¡Guardado!",
            text: "La historia clínica fue registrada correctamente.",
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
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

    window.addEventListener('abrirPopupReceta1', event => {
        const id = event.detail.id;
        const url = `/pdf-receta-preview/${id}`;
        //console.log('dentra',url);
        // Cerrar el modal ANTES de abrir el popup
        $('#modalRecList').modal('hide');
        // Abrir popup
        window.open(
            url,
            '_blank',
            'width=900,height=700,scrollbars=yes,resizable=yes'
        );

        // Evitar que la pantalla quede oscura
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
</script>
