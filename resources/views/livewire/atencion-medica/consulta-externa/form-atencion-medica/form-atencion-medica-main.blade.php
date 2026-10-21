<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">

            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                INFORME DIARIO DE CONSULTA EXTERNA
            </h6>
        </div>
        <x-atras-button wire:click="cancelar" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
     
     
         
        <div class="card p-2 card-comun">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card cabecera">
                        <b>DATOS DEL ASEGURADO</b>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-8 col-sm-8"> 
                    <p class="m-0"> <b>NOMBRE COMPLETO:</b> 
                        <span style="background-color: #e5e8e8; padding: 2px 5px; margin-left: 3px; margin-right: 3px; border-radius: 8px; display: inline-block;">
                            {{ $nombre }}
                        </span> 
                    </p>
                </div>
                <div class="col-lg-5 col-md-4 col-sm-4">
                     
                    <p class="m-0"> <b>MATRÍCULA:</b> <span style="background-color: #e5e8e8; padding: 2px 5px; margin-left: 3px; margin-right: 3px; border-radius: 8px; display: inline-block;">
                            {{ $matricula_seguro }}
                        </span>  </p>
                </div>
                  
            </div>
             
            <div class="row" style="margin-top:15px;">
                <div class="col-lg-7 col-md-6 col-sm-12">
                     
                    <p class="m-0"><b>TIPO ASEGURADO: </b><span style="background-color: #e5e8e8; padding: 2px 5px; margin-left: 3px; margin-right: 3px; border-radius: 8px; display: inline-block;">
                            {{ $tipo_asegurado }}
                        </span>  </p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-5">
                    <p class="m-0"><b>GÉNERO: </b><span style="background-color: #e5e8e8; padding: 2px 5px; margin-left: 3px; margin-right: 3px; border-radius: 8px; display: inline-block;">
                            {{ $genero == 'F' ? 'FEMENINO' : 'MASCULINO' }}
                        </span> </p>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-5">
                    <p class="m-0"><b>EDAD: </b><span style="background-color: #e5e8e8; padding: 2px 5px; margin-left: 3px; margin-right: 3px; border-radius: 8px; display: inline-block;">
                            {{ $edad }}
                        </span> </p>
                </div> 
            </div> 
             
        </div>
          
        <div class="row">
            <div class="col-lg-12 col-md-6 col-sm-12 col-12">
                <div class="card cabecera">
                    <b>VARIABLES DE ATENCIÓN MÉDICA</b>
                </div>
            </div>
        </div>
        <div class="card shadow-sm"> 
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em>Formulario a registrar </em>
                    
                </legend>
                 
                <div class="row g-3">

                    @php
                        $variablesNoBoolean = $variables->filter(fn($v) => $v->especialidadVariableVariable->tipo !== 'boolean');
                        $variablesBoolean   = $variables->filter(fn($v) => $v->especialidadVariableVariable->tipo === 'boolean');
                    @endphp

                    @foreach($variablesNoBoolean as $variable)
                        <div class="col-md-4" style="margin-top:0px;">
                            <label class="form-label">
                                {{ $variable->especialidadVariableVariable->nombre ?? '' }} 
                                <b style="color: #C0392B;">*</b>
                            </label>

                            @php
                                $key = 'valores.'.$variable->id;
                            @endphp

                            {{-- integer y decimal --}}
                            @if ($variable->especialidadVariableVariable->tipo === 'integer' || 
                                $variable->especialidadVariableVariable->tipo === 'decimal')

                                <input
                                    type="number"
                                    step="{{ $variable->especialidadVariableVariable->tipo === 'decimal' ? '0.01' : '1' }}"
                                    class="form-control @error($key) is-invalid @enderror"
                                    wire:model.defer="valores.{{ $variable->id }}"
                                    style="border-radius:8px;"
                                    placeholder="Ingrese el dato"
                                >

                            {{-- varchar --}}
                            @elseif ($variable->especialidadVariableVariable->tipo === 'varchar')

                                <input
                                    type="text"
                                    class="form-control @error($key) is-invalid @enderror"
                                    wire:model.defer="valores.{{ $variable->id }}"
                                    style="border-radius:8px;"
                                    placeholder="Ingrese el dato"
                                >

                            {{-- date --}}
                            @elseif ($variable->especialidadVariableVariable->tipo === 'date')

                                <input
                                    type="date"
                                    class="form-control @error($key) is-invalid @enderror"
                                    wire:model.defer="valores.{{ $variable->id }}"
                                    style="border-radius:8px;"
                                >

                            {{-- por defecto --}}
                            @else
                                <input
                                    type="text"
                                    class="form-control @error($key) is-invalid @enderror"
                                    wire:model.defer="valores.{{ $variable->id }}"
                                    style="border-radius:8px;"
                                    placeholder="Ingrese el dato"
                                >
                            @endif

                            @error($key)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    @if($variablesBoolean->count() > 0)
                        <div class="col-12 mt-4">
                            <label class="form-label fw-bold" style="color: #2471A3;">Validaciones y opciones:<b style="color: #C0392B;">*</b></label>
                            
                            <div class="row">
                            
                                @foreach($variablesBoolean->chunk(5) as $chunk) 
                                
                                    <div class="col-md-auto d-flex flex-column me-5"> 

                                        @foreach($chunk as $variable)

                                            @php
                                                $key = 'valores.'.$variable->id;
                                            @endphp

                                            <div class="form-check form-switch custom-green-switch mb-2">
                                                <input 
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="var{{ $variable->id }}"
                                                    wire:model.defer="valores.{{ $variable->id }}"
                                                >
                                                <label class="form-check-label" for="var{{ $variable->id }}" style="margin-left: 15px;">
                                                    <b> {{ $variable->especialidadVariableVariable->nombre ?? '' }}</b> 
                                                </label>

                                                @error($key)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        @endforeach
                                    </div>
                                
                                @endforeach
                                
                            </div>
                        </div>
                    @endif




                </div>

                 
            </fieldset>
        </div>

        <div class="mt-3 d-flex justify-content-end gap-2">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                <x-cancel-button wire:click="cancelar">CANCELAR</x-cancel-button>
                <x-save-button onclick="registrarFormularioAtencion()"
                    title="REGISTRAR FORMULARIO DE ATENCION"> GUARDAR </x-save-button>
            </div>
        </div>
         
    <style>
        /* --- ESTILOS DE TAMAÑO --- */
        .custom-green-switch .form-check-input {
            width: 2.5em;  
            height: 1.5em;
            background-position: left center; /* Asegura la posición del pulgar */
        }

        .custom-green-switch .form-check-input:checked {
            background-position: right center; /* Asegura la posición del pulgar al seleccionar */
        }

        .custom-green-switch .form-check-label {
            font-size: 1.03rem; 
            line-height: 1.5em; 
            margin-left: 0.5rem;
        }
          

    </style>
</div>

<script>
    function registrarFormularioAtencion() {
        Swal.fire({
            title: '¿Desea guardar el registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarVariables');
            }
        });
    }

    document.addEventListener('variablesGuardadas', function() {
        Swal.fire({
            title: "Se registro correctamente!",
            text: "El datos del formulario fueron registrados correctamente",
            icon: "success"
        });
    });
</script>