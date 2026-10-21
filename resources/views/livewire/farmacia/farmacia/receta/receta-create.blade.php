<div>
    <link href="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/styles/choices.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <div class="card-borde p-2">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                <p style="color:#2471a3" class="h5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em class="ml-2"><b> Seleccione y complete los siguientes campos: </b></em>
                    <span style="color:#cb4335;"><b>*</b></span>
                </p>
            </div>
        </div>

        <div class="row p-2">
            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                <strong>SELECCIONAR PACIENTE:<b style="color:#cb4335;">*</b></strong>
                <x-buscar-button-tres wire:click="botonModalPersona" style="margin-top:8px;"> BUSCAR
                    PACIENTE</x-buscar-button-tres>
            </div>


            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <label for="paciente"> NOMBRE COMPLETO DEL PACIENTE:</label>
                <input type="text" class="form-control @error('paciente') is-invalid @enderror input-comun"
                    placeholder=" Nombre completo " name="paciente" id="paciente" wire:model=paciente disabled>
                @error('paciente')
                    <div class="text-danger">
                        <b>Este campo es obligatorio</b>
                    </div>
                @enderror

            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-12">
                <label for="matricula"> MATRÍCULA:</label>
                <input type="text" class="form-control @error('matricula') is-invalid @enderror input-comun"
                    placeholder="Matrícula" name="matricula" id="matricula" wire:model=matricula disabled>
                @error('matricula')
                    <div class="text-danger">
                        <b>Este campo es obligatorio</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12" style="text-align:left;">
                <label for="nro_receta_anterior"> NRO. RECETA ANTERIOR: <b style="color:#cb4335;">*</b></label>
                <input type="number"
                    class="form-control @error('nro_receta_anterior') is-invalid @enderror input-comun"
                    placeholder=" Ingrese nro. receta anterior " name="nro_receta_anterior" id="nro_receta_anterior"
                    wire:model=nro_receta_anterior>
                @error('nro_receta_anterior')
                    <div class="text-danger">
                        <b>Este campo es obligatorio</b>
                    </div>
                @enderror
            </div>

        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12" wire:ignore>
                <label for="id_especialista"> MÉDICO SOLICITANTE: <b style="color:#cb4335;">*</b></label>

                <select id="id_especialista" class="form-control @error('id_especialista') is-invalid @enderror"
                    title="Selecciona una médico" wire:model="id_especialista" style="text-align:left;">
                    <option value="" selected>Selecciona un médico...</option>
                    @foreach ($datos_medico as $medico)
                        <option value="{{ $medico['id'] }}" data-custom-properties="">
                            {{ $medico['persona_especialista']['p_apellido'] }}
                            {{ $medico['persona_especialista']['s_apellido'] }}
                            {{ $medico['persona_especialista']['nombres'] }}
                            -
                            [ {{ $medico['especialidad_especialista']['especialidad'] }} ]
                        </option>
                    @endforeach

                </select>
                @error('id_especialista')
                    <div class="text-danger">
                        <b>Este campo es obligatorio</b>
                    </div>
                @enderror

            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-12" style="text-align:left;">
                <label for="tipo_atencion"> TIPO ATENCIÓN: <b style="color:#cb4335;">*</b></label>
                <select id="tipo_atencion" class="form-control @error('id_tipo_atencion') is-invalid @enderror"
                    title="Selecciona una opción..." style="border-radius:8px;" wire:model.live="id_tipo_atencion">
                    <option value="" selected>Selecciona un tipo de atención...</option>
                    @foreach ($tipo_atencion as $tipo)
                        <option value="{{ $tipo['id'] }}"> {{ $tipo['nombre'] }}</option>
                    @endforeach

                </select>
                @error('id_tipo_atencion')
                    <div class="text-danger">
                        <b>Este campo es obligatorio</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12" style="text-align:left;">
                <label for="fecha_emision"> FECHA DE EMISIÓN: <b style="color:#cb4335;">*</b></label>
                <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror input-comun"
                    placeholder=" Ingrese fecha emision " name="fecha_emision" id="fecha_emision"
                    wire:model=fecha_emision max="{{ $fecha_maxima }}">
                @error('fecha_emision')
                    <div class="text-danger">
                        <b>Este campo es obligatorio</b>
                    </div>
                @enderror
            </div>
        </div>


        <div class="row" @if (!in_array((int) $id_tipo_atencion, [5, 6])) style="display:none;" @endif>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12" wire:ignore>
                <label for="obser-quirofano" style="display: block; text-align: left;">
                    OBSERVACIÓN DE QUIRÓFANO:
                    <span style="color:#cb4335;"><b>*</b></span>
                </label>

                <div id="quill-observacion" style="height: 120px;">
                </div>
            </div>
        </div>

        <input type="hidden" wire:model="observacion_quirofano" id="observacion_quirofano">

    </div>
    <br>
    <div class="card-comun p-2">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
                <div class="card cabecera p-1">
                    <b> AGREGAR MEDICAMENTOS</b>
                </div>
            </div>
        </div>

        <div class="row p-2">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;" wire:ignore>
                <label for="id_medicamento"> MEDICAMENTO: <b style="color:#cb4335;">*</b></label>

                <select id="id_medicamento" class="form-control" title="Selecciona un medicamento"
                    style="text-align:left;" wire:model="id_medicamento" wire:change="stockActualMed">
                    <option value="" disabled selected>Selecciona una opción...</option>
                    @forelse ($medicamentos as $med)
                        <option value="{{ $med['id'] }}" data-custom-properties='@json([
                            'virtualDisabled' => $med['stock_actual'] == 0, // <- nuestra “restricción”
                            'stock' => $med['stock_actual'],
                        ])'>
                            {{ $med['codigo_medicamento'] }} - {{ $med['nombre'] }}
                            - [{{ $med['concentracion'] }}] [{{ $med['forma_farmaceutica'] }}]
                            {{ $med['stock_actual'] == 0 ? ' ⚠️(sin stock)' : '' }}
                        </option>
                    @empty
                        <option disabled selected>No hay medicamentos disponibles</option>
                    @endforelse
                </select>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-12" style="text-align:left;">
                <label for="stock_actual"> STOCK FARMACIA: <b style="color:#cb4335;">*</b></label>
                <input type="number" class="form-control @error('stock_actual') is-invalid @enderror input-comun"
                    placeholder=" Stock " name="stock_actual" id="stock_actual" wire:model=stock_actual disabled>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12 col-12" style="text-align:left;">
                <label for="cantidad"> CANTIDAD: <b style="color:#cb4335;">*</b></label>
                <input type="number" class="form-control @error('cantidad') is-invalid @enderror input-comun"
                    placeholder=" Ingrese cantidad " name="cantidad" id="cantidad" wire:model=cantidad>
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12 col-12" style="text-align:right;">
                <br>
                <x-agregar-button wire:click="agregarMedicamento"> Agregar</x-agregar-button>
            </div>

            <div wire:loading wire:target="agregarMedicamento"> <x-cargando-dos /></div>
        </div>


        <div style="max-height: 500px; overflow-y: auto;">
            <table class="table table-bordered">
                <thead style="background-color: #1f618d; color:white; text-align:center;">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">CÓDIGO</th>
                        <th scope="col">MEDICAMENTO</th>
                        <th scope="col">CANTIDAD</th>
                        <!-- <th scope="col">DÍAS DE VALIDEZ</th> -->
                        <th scope="col">FORMA FARMACÉUTICA</th>
                        <th scope="col">OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicamentosSeleccionados as $i => $item)
                        <tr>
                            <td style="text-align:center;"> <b>{{ $loop->iteration }}. </b></td>
                            <td style="text-align:center;">{{ $item['codigo'] }}</td>
                            <td>{{ $item['medicamento'] }}</td>
                            <td style="text-align:center;">{{ $item['cantidad'] }}</td>
                            <td>{{ $item['forma_farmaceutica'] }}</td>
                            <td style="text-align:center;">
                                <x-eliminar-button
                                    wire:click="eliminarReceta({{ $i }})"></x-eliminar-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <br>

    <div class="modal-footer" style="border: none; background-color: white;">


        <x-cancel-button onclick="cerrarModalReceta()"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarRecetaManual()">GUARDAR</x-save-button>

    </div>
    @include('livewire.farmacia.farmacia.receta.modalPersonasLista')


    <style>
        /* Aumentar la altura del select2 (vista cerrada) */
        .select2-container .select2-selection--single {
            height: 45px !important;
            /*   Ajusta aquí */
            display: flex;
            align-items: center;
            padding-left: 8px;
            font-size: 15px;
            border-radius: 8px;
        }

        /* Aumentar altura del texto dentro */
        .select2-container .select2-selection__rendered {
            line-height: 45px !important;
            /*   igual que height */
        }

        /* Aumentar altura del ícono flecha */
        .select2-container .select2-selection__arrow {
            height: 45px !important;
        }

        .choices__inner {
            width: 100% !important;
            /* Asegurarse que ocupe todo el ancho disponible */
            border: 1px solid #ced4da;
            /* Borde similar a Bootstrap */
            border-radius: 0.375rem;
            /* Bordes redondeados como Bootstrap */
            padding: 0.375rem 0.75rem;
            /* Padding similar a los formularios de Bootstrap */
            font-size: 1rem;
            /* Tamaño de fuente igual al de los formularios de Bootstrap */
        }

        .choices__list {
            max-height: 300px;
            overflow-y: auto;
            width: 100% !important;
        }

        .choices__item {
            writing-mode: unset !important;
            transform: unset !important;
            white-space: normal !important;
            text-align: left;
        }

        .choices__list .choices__item[aria-disabled="true"] {
            display: list-item !important;
            opacity: 0.5;
            /* para diferenciarlo */
            pointer-events: none;
        }

        #obser-quirofano .ql-editor {
            min-height: 120px;
        }
    </style>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Choices.js -->
<script src="https://cdn.jsdelivr.net/npm/choices.js@11.1.0/public/assets/scripts/choices.min.js"></script>


<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    let quillInstance = null;
    const defaultListContent = '<ol><li><br></li></ol>';

    function cerrarModalReceta() {
        $('#modalRReceta').modal('hide');
    }

    const initializeQuill = (targetId) => {

        const container = document.querySelector(targetId);

        if (!container) return;

        if (quillInstance) {

            quillInstance.root.innerHTML = defaultListContent;

            document.getElementById('observacion_quirofano').value =
                defaultListContent;

            return;
        }


        quillInstance = new Quill(targetId, {

            theme: 'snow',

            placeholder: 'Introduzca la observación',

            modules: {

                toolbar: [

                    [{
                            'list': 'ordered'
                        },
                        {
                            'list': 'bullet'
                        }
                    ],

                    [
                        'bold',
                        'italic',
                        'underline'
                    ],

                    [
                        'clean'
                    ]

                ]

            }

        });

        quillInstance.root.innerHTML = defaultListContent;

        document.getElementById('observacion_quirofano').value =
            defaultListContent;

        quillInstance.on('text-change', function() {

            document.getElementById('observacion_quirofano').value =
                quillInstance.root.innerHTML;

        });

    };

    document.addEventListener('DOMContentLoaded', function() {

        const selectMedicamento = document.getElementById('id_medicamento');
        const selectMedSoli = document.getElementById('id_especialista');

        const medicamentoSelect = new Choices(selectMedicamento, {
            searchEnabled: true,
            itemSelectText: 'Presiona para seleccionar',
            shouldSort: false,
            searchResultLimit: 8, // <-- muestra hasta 10 resultados al buscar
            renderChoiceLimit: 10 // <-- limita cuántas opciones renderiza en el dropdown
        });

        const MedSolicSelect = new Choices(selectMedSoli, {
            searchEnabled: true,
            itemSelectText: 'Presiona para seleccionar',
            shouldSort: false
        });

        window.addEventListener('medicamentoRegistrado', () => {
            medicamentoSelect.removeActiveItems();
            Swal.fire({
                title: 'Medicamento Registrado!',
                text: 'El medicamento se registró exitosamente',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
            });
        });

        window.addEventListener('recetaCreada', () => {
            $('#registroManual').modal('hide');
            MedSolicSelect.removeActiveItems();
            //limpiar quill
            if (quillInstance) {
                quillInstance.root.innerHTML = defaultListContent;
                document.getElementById('observacion_quirofano').value = defaultListContent;
            }

            Swal.fire({
                title: 'Receta Registrada!',
                text: 'La receta se registro correctamente',
                icon: 'success'
            });
        });

        window.addEventListener('limpiarSelectEspecialista', () => {
            MedSolicSelect.removeActiveItems();
            if (quillInstance) {
                quillInstance.root.innerHTML = defaultListContent;
                document.getElementById('observacion_quirofano').value = defaultListContent;
            }
        });

        initializeQuill('#quill-observacion');
    });

    function guardarRecetaManual() {
        Swal.fire({
            title: '¿Guardar Receta?',
            text: "Se registrarán las recetas y datos agregados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // document.getElementById('cargando_receta').style.display = 'block';
                // Aquí tomamos el contenido del Quill
                const contenidoQuill = quillInstance.root.innerHTML;
                //Y lo enviamos como parámetro al evento Livewire
                Livewire.dispatch('funcionGuardarRecetaManual', {
                    observacion_quirofano: contenidoQuill
                });
            }
        });
    }

    window.addEventListener('personaSeleccionada', function(event) {
        const nombre = event.detail.nombrePaciente;

        Swal.fire({
            title: "¡Seleccionado!",
            html: `El/La paciente <b>${nombre}</b> fue seleccionado exitosamente.`,
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
        });
    });


    document.addEventListener('personaNoAsegurada', function(event) {
        Swal.fire({
            title: "Persona no asegurada",
            text: `La persona no cuenta con registros de afiliación.`,
            icon: "warning",
        });
    });

    document.addEventListener('medicamentoYaRegistrado', function() {
        Swal.fire({
            title: 'Medicamento Duplicado!',
            text: 'El medicamento ya se registro anteriormente',
            icon: 'warning'
        });
    })

    document.addEventListener('stockInsuficiente', function() {
        Swal.fire({
            title: 'Stock Insuficiente',
            text: 'No hay suficiente stock para completar la solicitud',
            icon: 'warning',
            showConfirmButton: false,
            timer: 1000,
        });
    });
</script>
