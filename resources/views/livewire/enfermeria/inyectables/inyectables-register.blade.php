<div>
    {{-- 1. ENCABEZADO SUPERIOR ESTÁNDAR --}}
    <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
            <h4 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-syringe text-info mr-2"></i> ENFERMERÍA - <b>REGISTRO DE INYECCIÓN</b>
            </h4>
        </div>
        <div class="col-sm-6 text-right">
            <x-atras-button wire:click="cancelar">
                ATRÁS
            </x-atras-button>
        </div>
    </div>

    {{-- 2. PESTAÑAS SELECTORAS --}}
    <div class="card card-primary card-outline card-outline-tabs shadow-sm mb-3">
        <div class="card-header p-0 border-bottom-0 bg-light d-flex justify-content-between align-items-center pr-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <button type="button" 
                            class="nav-link font-weight-bold py-2 px-4 border-0 {{ $tipoReceta === 'SISTEMA' ? 'active text-primary' : 'text-secondary' }}" 
                            wire:click="setTipoReceta('SISTEMA')"
                            style="background: transparent;">
                        <i class="fas fa-desktop mr-2 {{ $tipoReceta === 'SISTEMA' ? 'text-primary' : '' }}"></i> RECETA SISTEMA
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" 
                            class="nav-link font-weight-bold py-2 px-4 border-0 {{ $tipoReceta === 'MANUAL' ? 'active text-primary' : 'text-secondary' }}" 
                            wire:click="setTipoReceta('MANUAL')"
                            style="background: transparent;">
                        <i class="fas fa-file-signature mr-2 {{ $tipoReceta === 'MANUAL' ? 'text-primary' : '' }}"></i> RECETA MANUAL
                    </button>
                </li>
            </ul>

            {{-- Indicador de carga visible mientras conmuta de modo --}}
            <div wire:loading wire:target="setTipoReceta" class="text-primary font-weight-bold small">
                <x-cargando-dos />
                <span class="ml-1">Cambiando modo...</span>
            </div>
        </div>
    </div>

    {{-- BLOQUE 1: MODO SISTEMA --}}
    @if ($tipoReceta === 'SISTEMA')
        <div class="card card-outline card-primary shadow-sm mb-3">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="font-weight-bold text-secondary">Introduzca receta:</label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   placeholder="Ej. 852793" 
                                   wire:model="nroRecetaBusqueda"
                                   wire:keydown.enter="buscarRecetaSistema">
                            <div class="input-group-append">
                                <button type="button" 
                                        class="btn btn-primary d-flex align-items-center"
                                        wire:click="buscarRecetaSistema" 
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="buscarRecetaSistema">
                                        <i class="fas fa-search mr-1"></i> Buscar
                                    </span>
                                    <span wire:loading wire:target="buscarRecetaSistema">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if (!empty($nombreCompletoPaciente))
                        <div class="col-md-4">
                            <label class="font-weight-bold text-muted">Asegurado:</label>
                            <input type="text" class="form-control bg-light font-weight-bold text-primary" value="{{ $nombreCompletoPaciente }}" disabled>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold text-muted">Matrícula:</label>
                            <input type="text" class="form-control bg-light" value="{{ $matriculaPaciente }}" disabled>
                        </div>
                    @endif
                </div>

                @if (!empty($medicamentosRecetaSistema))
                    <div class="mt-4">
                        <h6 class="font-weight-bold text-primary mb-2">Seleccione medicamento inyección:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center mb-0">
                                <thead style="background-color: #2471a3; color: white;">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>RECETA</th>
                                        <th>MEDICAMENTO INYECCIÓN</th>
                                        <th style="width: 220px;">VÍA PARENTERAL</th>
                                        <th style="width: 120px;">SELECCIONAR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($medicamentosRecetaSistema as $idx => $med)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td><span class="badge badge-light border">{{ $nroRecetaBusqueda }}</span></td>
                                            <td class="text-left font-weight-bold">{{ $med['nombre'] }}</td>
                                            <td>
                                                <select class="form-control form-control-sm" wire:model="medicamentosRecetaSistema.{{ $idx }}.id_via_parenteral">
                                                    @foreach ($viasParenterales as $via)
                                                        <option value="{{ is_array($via) ? $via['id'] : $via->id }}">
                                                            {{ is_array($via) ? $via['nombre'] : $via->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success" wire:click="agregarInyeccionSistema({{ $idx }})">
                                                    <i class="fas fa-check"></i> Seleccionar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- BLOQUE 2: MODO MANUAL --}}
    @if ($tipoReceta === 'MANUAL')
        <div class="card card-outline card-primary shadow-sm mb-3">
            <div class="card-header bg-light py-2">
                <b class="text-secondary"><i class="fas fa-edit mr-1"></i> DATOS DE LA RECETA MANUAL</b>
            </div>
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="font-weight-bold">SELECCIONAR PACIENTE:</label><br>
                        <button type="button" class="btn btn-outline-primary btn-block font-weight-bold" wire:click="abrirModalPacientes">
                            <i class="fas fa-search mr-1"></i> BUSCAR PACIENTE
                        </button>
                    </div>
                    <div class="col-md-6">
                        <label class="font-weight-bold">NOMBRE COMPLETO DEL PACIENTE:</label>
                        <input type="text" class="form-control bg-light font-weight-bold text-dark" value="{{ $nombreCompletoPaciente }}" placeholder="Seleccione un paciente..." disabled>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">MATRÍCULA:</label>
                        <input type="text" class="form-control bg-light" value="{{ $matriculaPaciente }}" placeholder="S/M" disabled>
                    </div>
                </div>

                <div class="row mt-3">
                    {{-- AQUÍ ENTRA EL AUTOCOMPLETADO INTERACTIVO DE ESPECIALISTAS --}}
                    <div class="col-md-8 position-relative">
                        <label class="font-weight-bold">MÉDICO SOLICITANTE / ESPECIALISTA:</label>
                        
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-user-md text-primary"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control" 
                                   placeholder="Escriba el apellido o nombre del especialista..."
                                   wire:model.live.debounce.300ms="buscarEspecialista"
                                   @if($idEspecialista) readonly style="background-color: #f8fafc; font-weight: bold;" @endif>
                            
                            @if($idEspecialista)
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-danger" wire:click="limpiarEspecialista" title="Cambiar especialista">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Desplegable flotante de resultados en tiempo real -->
                        @if($mostrarDropdownEspecialistas && count($especialistasEncontrados) > 0)
                            <ul class="list-group position-absolute w-100 shadow-lg" 
                                style="z-index: 1050; max-height: 230px; overflow-y: auto; top: 100%; left: 0;">
                                @foreach($especialistasEncontrados as $esp)
                                    @php
                                        $idEsp = $esp['idEspecialista'] ?? $esp['id'] ?? null;
                                        $nom = $esp['nombreCompleto'] ?? $esp['nombre'] ?? '';
                                        $espNombre = is_array($esp['especialidad'] ?? null) 
                                            ? ($esp['especialidad']['nombre'] ?? 'MEDICINA') 
                                            : ($esp['especialidad'] ?? 'MEDICINA');
                                    @endphp
                                    <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2"
                                        style="cursor: pointer;"
                                        wire:click="seleccionarEspecialista({{ $idEsp }}, '{{ addslashes($nom) }}', '{{ addslashes($espNombre) }}')">
                                        <div>
                                            <i class="fas fa-stethoscope text-secondary mr-2"></i>
                                            <span class="font-weight-bold text-dark">{{ $nom }}</span>
                                        </div>
                                        <span class="badge badge-primary px-2 py-1">{{ $espNombre }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @elseif($mostrarDropdownEspecialistas && strlen(trim($buscarEspecialista)) >= 2)
                            <div class="position-absolute w-100 bg-white border rounded p-2 text-muted shadow-sm" style="z-index: 1050; top: 100%; left: 0;">
                                <small><i class="fas fa-info-circle mr-1"></i> No se encontraron especialistas con ese nombre.</small>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="font-weight-bold">FECHA DE EMISIÓN:</label>
                        <input type="date" class="form-control" wire:model="fechaEmisionManual">
                    </div>
                </div>

                <hr>

                <h6 class="font-weight-bold text-primary mb-2">AGREGAR MEDICAMENTOS INYECCIÓN:</h6>
                <div class="row align-items-end">
                    <div class="col-md-5 position-relative">
    <label class="font-weight-bold">INYECCIÓN MEDICAMENTO:</label>

    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text bg-white"><i class="fas fa-pills text-info"></i></span>
        </div>
        <input type="text" 
               class="form-control" 
               placeholder="Escriba código o nombre del inyectable..."
               wire:model.live.debounce.300ms="buscarMedicamento"
               @if($idMedicamentoManual) readonly style="background-color: #f8fafc; font-weight: bold;" @endif>
        
        @if($idMedicamentoManual)
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-danger" wire:click="limpiarMedicamentoInyectable" title="Quitar selección">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Menú desplegable interactivo en tiempo real -->
    @if($mostrarDropdownMedicamentos && count($medicamentosFiltrados) > 0)
        <ul class="list-group position-absolute w-100 shadow-lg" 
            style="z-index: 1050; max-height: 230px; overflow-y: auto; top: 100%; left: 0;">
            @foreach($medicamentosFiltrados as $med)
                @php
                    $idMed = $med['id'];
                    $cod = $med['codigo_medicamento'] ?? '';
                    $nom = $med['nombre'] ?? '';
                    $forma = $med['forma_farmaceutica'] ?? 'Inyectable';
                @endphp
                <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2"
                    style="cursor: pointer;"
                    wire:click="seleccionarMedicamentoInyectable({{ $idMed }}, '{{ addslashes($cod) }}', '{{ addslashes($nom) }}')">
                    <div>
                        <span class="badge badge-light border text-secondary font-weight-bold mr-1">{{ $cod }}</span>
                        <span class="font-weight-bold text-dark">{{ $nom }}</span>
                    </div>
                    <span class="badge badge-info px-2 py-1">{{ $forma }}</span>
                </li>
            @endforeach
        </ul>
    @elseif($mostrarDropdownMedicamentos && strlen(trim($buscarMedicamento)) >= 2)
        <div class="position-absolute w-100 bg-white border rounded p-2 text-muted shadow-sm" style="z-index: 1050; top: 100%; left: 0;">
            <small><i class="fas fa-info-circle mr-1"></i> No se encontraron medicamentos inyectables con ese nombre.</small>
        </div>
    @endif
</div>
                    <div class="col-md-2">
                        <label class="font-weight-bold">CANTIDAD:</label>
                        <input type="number" min="1" class="form-control" wire:model="cantidadManual">
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">VÍA PARENTERAL:</label>
                        <select class="form-control" wire:model="idViaParenteralManual">
                            <option value="">Seleccione vía...</option>
                            @foreach ($viasParenterales as $via)
                                <option value="{{ is_array($via) ? $via['id'] : $via->id }}">
                                    {{ is_array($via) ? $via['nombre'] : $via->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success btn-block font-weight-bold" wire:click="agregarInyeccionManual">
                            <i class="fas fa-plus mr-1"></i> AGREGAR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

   {{-- BLOQUE 3: LISTADO DE INYECCIONES INGRESADOS (ESTILO ALMACÉN INSTITUCIONAL) --}}
    <div class="card shadow-sm border mb-4" style="border-radius: 8px; overflow: hidden;">
        {{-- Barra de título con fondo azul suave y texto centrado en azul corporativo --}}
        <div class="card-header py-2 text-center" style="background-color: #e8f4fc; border-bottom: 1px solid #b8daff;">
            <b style="color: #1b4f72; font-size: 13.5px; letter-spacing: 0.5px; text-transform: uppercase;">
                LISTADO DE INYECCIONES INGRESADOS
            </b>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center" style="font-size: 13px;">
                    <thead style="background-color: #1f618d; color: #ffffff; border-bottom: 2px solid #154360;">
                        <tr>
                            <th style="width: 45px; vertical-align: middle;">#</th>
                            <th style="width: 140px; vertical-align: middle;"># RECETA</th>
                            <th style="width: 220px; vertical-align: middle;">VÍA PARENTERAL</th>
                            <th style="vertical-align: middle; text-align: left; padding-left: 15px;">INYECCIÓN MEDICAMENTO</th>
                            <th style="width: 90px; vertical-align: middle;">OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inyeccionesIngresados as $k => $item)
                            <tr>
                                <td style="vertical-align: middle;" class="text-secondary font-weight-bold">
                                    {{ $k + 1 }}
                                </td>
                                <td style="vertical-align: middle;">
                                    <span class="badge {{ $item['nro_receta'] === 'S/N' ? 'badge-light border' : 'badge-primary' }} px-2 py-1 font-weight-bold" style="font-size: 12px;">
                                        {{ $item['nro_receta'] }}
                                    </span>
                                </td>
                                <td style="vertical-align: middle;" class="font-weight-bold text-dark">
                                    {{ $item['via_parenteral_nombre'] }}
                                </td>
                                <td style="vertical-align: middle; text-align: left; padding-left: 15px;" class="font-weight-bold text-secondary">
                                    {{ $item['medicamento_nombre'] }}
                                </td>
                                <td style="vertical-align: middle;">
                                    {{-- Botón idéntico al de la imagen: cuadrado outline rojo con icono de basurero centrado --}}
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="padding: 4px 8px; border-color: #dc3545; border-radius: 4px;"
                                            wire:click="quitarInyeccion({{ $k }})" 
                                            title="Quitar inyección">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-muted" style="background-color: #ffffff;">
                                    <i class="fas fa-info-circle text-info mr-1"></i>
                                    <em>No se han ingresado inyecciones todavía.</em>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- BLOQUE 4: CHECKLIST DE PROCEDIMIENTOS COMPLEMENTARIOS --}}
    <div class="card mb-3 shadow-sm border">
        <div class="card-header bg-light py-2">
            <b class="text-secondary"><i class="fas fa-stethoscope mr-1"></i> MARQUE LA CASILLA QUE CORRESPONDA SI ES NECESARIO</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_cur_plana" wire:model.live="curacion_plana">
                        <label class="custom-control-label font-weight-bold" for="chk_cur_plana">Curación plana</label>
                    </div>
                    @if($curacion_plana)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_curacion_plana">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_cur_infectada" wire:model.live="curacion_infectada">
                        <label class="custom-control-label font-weight-bold" for="chk_cur_infectada">Curación infectada</label>
                    </div>
                    @if($curacion_infectada)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_curacion_infectada">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_oxigeno" wire:model.live="oxigenoterapia">
                        <label class="custom-control-label font-weight-bold" for="chk_oxigeno">Oxigenoterapia</label>
                    </div>
                    @if($oxigenoterapia)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_oxigenoterapia">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_puntos" wire:model.live="retiro_puntos">
                        <label class="custom-control-label font-weight-bold" for="chk_puntos">Retiro puntos</label>
                    </div>
                    @if($retiro_puntos)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_retiro_puntos">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_sensibilidad" wire:model.live="prueba_sensibilidad">
                        <label class="custom-control-label font-weight-bold" for="chk_sensibilidad">Prueba sensibilidad</label>
                    </div>
                    @if($prueba_sensibilidad)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_prueba_sensibilidad">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_sangria" wire:model.live="sangria">
                        <label class="custom-control-label font-weight-bold" for="chk_sangria">Sangría</label>
                    </div>
                    @if($sangria)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_sangria">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_vitales" wire:model.live="signos_vitales">
                        <label class="custom-control-label font-weight-bold" for="chk_vitales">Signos vitales</label>
                    </div>
                    @if($signos_vitales)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_signos_vitales">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_txvo" wire:model.live="tx_vo">
                        <label class="custom-control-label font-weight-bold" for="chk_txvo">Tx VO</label>
                    </div>
                    @if($tx_vo)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_tx_vo">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_orientacion" wire:model.live="orientacion_salud">
                        <label class="custom-control-label font-weight-bold" for="chk_orientacion">Orientación salud</label>
                    </div>
                    @if($orientacion_salud)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_orientacion_salud">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_vendajes" wire:model.live="vendajes">
                        <label class="custom-control-label font-weight-bold" for="chk_vendajes">Vendajes</label>
                    </div>
                    @if($vendajes)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_vendajes">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_nebulizacion" wire:model.live="nebulizacion">
                        <label class="custom-control-label font-weight-bold" for="chk_nebulizacion">Nebulización</label>
                    </div>
                    @if($nebulizacion)
                        <input type="text" class="form-control form-control-sm" placeholder="Observación..." wire:model="obs_nebulizacion">
                    @endif
                </div>

                <div class="col-md-6 mb-2">
                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" id="chk_otros" wire:model.live="otros">
                        <label class="custom-control-label font-weight-bold" for="chk_otros">Otros</label>
                    </div>
                    @if($otros)
                        <input type="text" class="form-control form-control-sm" placeholder="Especifique..." wire:model="obs_otros">
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- BOTONES DE ACCIÓN INFERIORES --}}
   {{-- BOTONES DE ACCIÓN INFERIORES --}}
    <div class="d-flex justify-content-end mb-4">
        <x-cancel-button wire:click="cancelar" class="mr-2">CANCELAR</x-cancel-button>

        {{-- Botón nativo directo para asegurar que Livewire capture el clic --}}
        <button type="button" 
                class="btn btn-success font-weight-bold px-4" 
                wire:click="guardarAtencion" 
                wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="guardarAtencion">
                <i class="fas fa-save mr-1"></i> GUARDAR
            </span>
            <span wire:loading wire:target="guardarAtencion">
                <i class="fas fa-spinner fa-spin mr-1"></i> GUARDANDO...
            </span>
        </button>
    </div>

    {{-- MODAL REUTILIZABLE: LISTADO DE PACIENTES --}}
    <div wire:ignore.self class="modal fade" id="modalAsegurados" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #2471a3;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-users mr-2"></i> LISTADO DE PACIENTES</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" 
                               class="form-control" 
                               placeholder="Buscar por Nombre, C.I. o Matrícula..." 
                               wire:model.live.debounce.300ms="terminoBusquedaPaciente">
                    </div>

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover table-striped text-center mb-0">
                            <thead style="background-color: #2471a3; color: white;">
                                <tr>
                                    <th>#</th>
                                    <th>C.I.</th>
                                    <th>MATRÍCULA</th>
                                    <th>NOMBRE COMPLETO</th>
                                    <th>TIPO ASEGURADO</th>
                                    <th>OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($listaPacientesModal as $i => $pac)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="font-weight-bold">{{ $pac['ci'] ?? 'S/C' }}</td>
                                        <td><span class="badge badge-light border">{{ $pac['matricula'] ?? 'S/M' }}</span></td>
                                        <td class="text-left font-weight-bold">{{ $pac['nombreCompleto'] ?? $pac['nombre_completo'] ?? '' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $pac['tipoAsegurado'] ?? 'TITULAR' }}</span>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    wire:click="seleccionarPacienteModal({{ $pac['idPersona'] ?? $pac['id'] }}, '{{ $pac['nombreCompleto'] ?? $pac['nombre_completo'] ?? '' }}', '{{ $pac['ci'] ?? '' }}', '{{ $pac['matricula'] ?? '' }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-3 text-muted">
                                            No se encontraron pacientes coincidentes.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>

   

    {{-- MODAL DE BÚSQUEDA DE PACIENTES (ESTILO EXACTO A TU CAPTURA) --}}
    @if($modalBuscarPaciente)
        <div class="modal fade show" tabindex="-1" style="display: block !important; background-color: rgba(0, 0, 0, 0.6); z-index: 99999;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0" style="border-radius: 6px;">
                    
                    {{-- Encabezado con lápiz naranja como en tu imagen --}}
                    <div class="modal-header d-flex align-items-center py-2 px-3" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <h5 class="modal-title font-weight-bold text-dark d-flex align-items-center" style="font-size: 15px;">
                            <i class="fas fa-pencil-alt mr-2" style="color: #fd7e14;"></i>
                            LISTADO DE PACIENTES
                        </h5>
                        <button type="button" class="close text-muted" wire:click="cerrarModalPacientes" style="outline: none; font-size: 22px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    {{-- Cuerpo del modal con franja celeste y tabla PowerGrid --}}
                    <div class="modal-body p-3" style="background-color: #ffffff;">
                        <div class="p-2 mb-2 font-weight-bold text-primary" style="background-color: #e0f2fe; border-radius: 4px; font-size: 12px; letter-spacing: 0.5px;">
                            LISTA DE ASEGURADOS
                        </div>

                        {{-- Componente de tabla que lista pacientes --}}
                        <livewire:enfermeria.inyectables.select-paciente-table />
                    </div>

                    {{-- Pie del modal --}}
                    <div class="modal-footer py-2 px-3 d-flex justify-content-end" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                        <button type="button" 
                                class="btn btn-sm btn-outline-danger font-weight-bold d-flex align-items-center" 
                                wire:click="cerrarModalPacientes" 
                                style="border-radius: 4px; padding: 4px 14px;">
                            <i class="fas fa-times mr-1"></i> CERRAR
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>