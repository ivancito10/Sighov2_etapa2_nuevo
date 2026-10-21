<div>
    <div class="col-lg-12 text-center">
        <strong style="letter-spacing: 1px; color: #a93226;font-size:20px">
            FORMULARIO DE INCAPACIDAD TEMPORAL
        </strong>
    </div>
    <div style="height: 0.5rem;"></div>
    <div class="col-lg-12">
        <div class="card-borde p-2">
            <table style="width: 100%">
                <tr>
                    <td class="text-right" colspan="2"><b> Código de baja Médica: </b> <b
                            style="font-size:20px">BM/N°/{{ $cod_baja }}/{{ \Carbon\Carbon::parse($fecha_creacion)->format('Y') }}
                        </b> </td>
                </tr>
                <tr>
                    <td colspan="1"><b>Paciente: </b> {{ $paciente }}</td>
                    <td colspan="1"><b>Edad:</b> {{ $edad }} años </td>
                </tr>
                <tr>
                    <td colspan="1"><b>Matrícula:</b> {{ $matricula }} {{ $codigo_institucional }} </td>
                    <td colspan="1"><b>Sexo:</b> {{ $sexo }} </td>
                </tr>
                <tr>
                    <td colspan="1"><b>Carnet:</b> {{ $ci }} </td>
                    <td colspan="1"><b>Tipo Paciente:</b>
                        @if ($tipo_asegurado === 'TITULAR')
                            ASEGURADO
                        @else
                            {{ $tipo_asegurado }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="1"><b>Empleador:</b> {{ $instituciones }} </td>
                </tr>
                <tr>
                    <td colspan="2"><b>Motivo de Baja:</b> {{ $motivo }} </td>
                </tr>
            </table>
        </div>
        <div style="height: 0.5rem;"></div>
        <div class="card-borde p-2">
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em>Ingresar los siguientes campos para modificar la baja:</em>
                    <span>*</span>
                </legend>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <label for="tipo_baja">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-caret-down-square-fill" viewBox="0 0 16 16">
                                <path
                                    d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z" />
                            </svg>
                            TIPO DE BAJA: </label>
                        <select wire:model.live="id_tipo_baja" id="id_tipo_baja"
                            class="form-control select2 @error('id_tipo_baja') is-invalid @enderror"
                            style="width: 100%; border-radius:10px;" tabindex="-1" required>
                            <option selected=" ">Seleccione el tipo de baja... </option>
                            @foreach ($tipo_bajas as $baja)
                                <option value="{{ $baja->id }}">{{ $baja->nombre }}
                                </option>
                            @endforeach
                        </select>

                        @error('tipo_baja')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <label for="fecha_inicio">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-calendar-week" viewBox="0 0 16 16">
                                <path
                                    d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                            </svg>
                            FECHA INICIO: </label>
                        <input type="date"
                            class="form-control input-comun @error('fecha_inicio') is-invalid @enderror"
                            name="fecha_inicio" id="fecha_inicio" wire:model.live=fecha_inicio_mostrar
                            wire:change="modificarNroDias">
                        @error('fecha_inicio_mostrar')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <label for="fecha_fin">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-calendar-week" viewBox="0 0 16 16">
                                <path
                                    d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                            </svg>
                            FECHA FIN: </label>
                        <input type="date" class="form-control input-comun @error('fecha_fin') is-invalid @enderror"
                            name="fecha_fin" id="fecha_fin" wire:model=fecha_fin_mostrar wire:change="modificarNroDias">
                        @error('fecha_fin_mostrar')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                        <label for="nro_dias">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-hash" viewBox="0 0 16 16">
                                <path
                                    d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                            </svg>
                            DIAS DE BAJA: </label>
                        <input type="number" class="form-control input-comun @error('nro_dias') is-invalid @enderror"
                            name="nro_dias" id="nro_dias" wire:model=nro_dias disabled>
                        @error('nro_dias')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
            </fieldset>

        </div>
    </div>

    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button data-dismiss="modal">
            CANCELAR</x-cancel-button>
        <x-save-button wire:click="preguntarEditarBaja">GUARDAR</x-save-button>
    </div>
</div>
<script>
    document.addEventListener('guardarEditarBaja', function() {
        Swal.fire({
            title: '¿Actualizar registro?',
            text: "Se registrarán los datos actualizados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {

            if (result.isConfirmed) {
                // console.log('llega');
                Livewire.dispatch('actualzarBaja');
                // console.log('llega asds');
                // document.getElementById('cargando').style.display = 'block';
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Se canceló la edición.",
                    icon: "warning"
                });
                //Livewire.dispatch('cancelarProveedor');
                //$('#editarAdmBajaMedica').modal('hide');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Escuchar el evento emitido desde Livewire
        Livewire.on('actualizarBajaExitoso', function() {
            // Aquí puedes ejecutar lo que quieres que pase cuando se emite el evento
            $('#editarAdmBajaMedica').modal('hide'); // Cerrar el modal
            Swal.fire({
                title: "Baja médica modificado",
                text: "La baja médica fue modificado con éxito",
                icon: "success"
            });
        });
    });
</script>
