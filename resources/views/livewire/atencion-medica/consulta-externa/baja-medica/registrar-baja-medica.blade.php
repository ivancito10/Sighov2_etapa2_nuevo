<div>
     
    <div class="card-borde p-3"> 

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes datos para el
                            registro.</b>
                    </em>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4" wire:ignore>
                <label for="id_tipo_baja"> TIPO BAJA MEDICA: <b style="color:#cb4335;">*</b></label>
                <div class="col-lg-12 col-md-6 col-sm-12">
                    <select id="id_tipo_baja" class="form-control" wire:model="id_tipo_baja"
                        title="Selecciona una opción..." style="border-radius:8px;" wire:change="actualizarFecha">
                        <option value="" selected>Selecciona una opción...</option>
                        @foreach ($tipos_baja as $tipo)
                            <option value="{{ $tipo['id'] }}">{{ $tipo['nombre'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if($id_tipo_baja != null)
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <label for="fecha_inicio">FECHA INICIO: <span style="color:#cb4335;"><b>*</b></span></label>
                <input id="fecha_inicio" class="form-control @error('fecha_ini') is-invalid @enderror"
                    wire:model.live="fecha_ini" min="{{ $fecha_min }}" max="{{ $fecha_max}}" type="date" style="border-radius:8px;">
                @error('fecha_ini')
                    <div class="invalid-feedback">El campo es necesario</div>
                @enderror
            </div>
            @endif

            @if($id_tipo_baja != 4)
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label for="cantidad_dias">CANTIDAD DE DÍAS: <span class="text-danger">*</span></label>
                <input id="cantidad_dias" 
                    class="form-control @error('dias_baja') is-invalid @enderror"
                    wire:model.live="dias_baja" 
                    type="number"
                    min="1" 
                    max="{{ $max_dias }}"
                    placeholder="Ingrese la cantidad de días" 
                    style="border-radius:8px;">

                    @error('dias_baja')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>
            @endif

            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <label for="fecha_fin">FECHA FIN: <span style="color:#cb4335;"><b>*</b></span></label>
                <input id="fecha_fin" class="form-control" wire:model.live="fecha_fin" type="date"
                    style="border-radius:8px;" 
                    @if($id_tipo_baja != 4) 
                        disabled 
                    @endif>
            </div>

            @if($id_tipo_baja == 7)
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label for="nro_resolucion">NRO DE RESOLUCION: <span class="text-danger">*</span></label>
                <input id="nro_resolucion" 
                    class="form-control @error('nro_resolucion') is-invalid @enderror"
                    wire:model.live="nro_resolucion" 
                    type="text"
                    placeholder="Ingrese el numero de resolucion" 
                    style="border-radius:8px;">

                    @error('nro_resolucion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>
            @endif

        </div> 
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="descripcion"> MOTIVO DE LA BAJA:  </label>
                <textarea type="text" id="descripcion" placeholder="Ingrese el motivo de la baja" class="form-control"
                    oninput="this.value = this.value.toUpperCase()" wire:model="descripcion" style="border-radius:8px;" rows="2"></textarea>

            </div>
        </div>
    </div>
         
    <div class="modal-footer " style="border: none; background-color: white;">
        <x-cancel-button onclick="cerrarModalBajaMedica()"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarBajaMedica()" wire:target="guardarBajaMedica">GUARDAR</x-save-button>

            <div wire:loading wire:target="guardarBajaMedica">
                <x-cargando-dos />
            </div>
    </div>

    <style>
        /* Cambia el fondo y color del select cuando tiene elementos seleccionados */
        .select2-container--default .select2-selection--multiple {
            background-color: white !important;
            /* Fondo blanco */
            color: #2874A6 !important;
            /* Texto azul */
            border-color: #ccc !important;
            /* Borde */
        }

        /* Cambia el color de los ítems seleccionados */
        .select2-container--default .select2-selection__choice {
            background-color: #2874A6 !important;
            /* Fondo azul */
            color: white !important;
            /* Texto blanco */
            border-color: #2874A6 !important;
            /* Borde del ítem */
        }

        /* Cambia los colores de los ítems en la lista desplegable */
        .select2-container--default .select2-results__option {
            background-color: white !important;
            /* Fondo blanco */
            color: #2874A6 !important;
            /* Texto azul */
        }

        /* Cambia el color del ítem que está resaltado al hacer hover o está seleccionado en la lista */
        .select2-container--default .select2-results__option--highlighted {
            background-color: #2874A6 !important;
            /* Fondo azul */
            color: white !important;
            /* Texto blanco */
        }
    </style>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#examenes').select2();

        $('#examenes').on('change', function() {
            let seleccionadas = $(this).val();
            console.log("examenes seleccionadas (JS):", seleccionadas);
            @this.call('agregarFuncion', seleccionadas);
        });
    });

    function guardarBajaMedica() {
        Swal.fire({
            title: '¿Guardar Baja Médica?',
            text: "Se registrarán los datos de la baja médica.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registrarBajaMedica');
            }
        });
    }

    document.addEventListener('bajaCreada', function() {
        $('#modalRBMedica').modal('hide');
        Swal.fire({
            title: '¡Guardado!',
            text: 'La baja médica se registro correctamente.',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
        });
    })

    document.addEventListener('error', function() {
        Swal.fire({
            title: '¡Error!',
            text: 'Comuniquese con el administrador',
            icon: 'error'
        });
    })

    function cerrarModalBajaMedica() {
        $('#modalRBMedica').modal('hide');
    }

     

    document.addEventListener('HayBajasVigentes', function(event) { 
        const datosBaja = event.detail[0]; 
 
        const mensaje = `
            <em> Detalles de la Baja Vigente:</em>
            <br><br>
            Paciente: <b>  ${datosBaja.id_persona}</b>
            <br>
            Fecha Inicio: <b>  ${datosBaja.fecha_inicio}</b>
            <br>
            Fecha Fin: <b>  ${datosBaja.fecha_fin}</b>
            <br>
            Días de Baja: <b>  ${datosBaja.dias}</b>
            <br>
            Especialidad: <b>  ${datosBaja.especialidad}</b>
            <br>
            Doctor/a: <b>  ${datosBaja.nombre_doctor}</b>
        `;

        Swal.fire({
            title: '¡Existe una Baja Médica Vigente!', 
            html: mensaje,  
            icon: 'warning'
        });
    });

    document.addEventListener('SinTransfAportes', function() {
        Swal.fire({
            title: '¡No se pudo realizar la baja!',
            text: 'El Beneficiario no cuenta con transferencia de aportes.',
            icon: 'warning'
        });
    })
    
</script>
