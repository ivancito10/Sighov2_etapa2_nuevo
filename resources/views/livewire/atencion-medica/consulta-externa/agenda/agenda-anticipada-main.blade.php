<div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card-comun p-2"> 
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card cabecera">
                            <b>DATOS DEL ASEGURADO</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong> NOMBRE COMPLETO: </strong>
                        <p class="p-comun-disabled p-1">{{ $datosPaciente['nombre_persona'] ?? ''}} </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <strong> EDAD: </strong>
                        <p class="p-comun-disabled p-1">{{ $datosPaciente['edad'] ?? ''}} </p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                        <strong> GÉNERO: </strong>
                        <p class="p-comun-disabled p-1">
                            {{ ($datosPaciente['genero'] ?? '') === 'F' ? 'Femenino' : 'Masculino' }}
                        </p>

                    </div>

                </div>
            </div>
        </div>


        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card-comun p-3"> 
                
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div style="margin-left: 7px;">
                            <div class="mr-2">
                                <input
                                    type="date"
                                    wire:model="fechaAnticipadaFiltro"
                                    wire:change="filtrarPorFechaAnticipada"
                                    class="form-control"
                                    min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                    max="{{ \Carbon\Carbon::today()->addMonth()->format('Y-m-d') }}"
                                    style="border-radius:8px;"
                                />
                            </div>
                        </div> 
                    </div>
                    @if (!empty($turnos))
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="d-flex align-items-center gap-2">
                            
                            <label class="mb-0 text-nowrap">TURNO:</label>
                            
                            <select wire:model="turnoSeleccionado" class="form-control" wire:change="filtrarPorFechaAnticipada" style="border-radius:8px; width: auto;">
                                @foreach ($turnos as $turno)
                                    <option value="{{ $turno['index'] }}">{{ $turno['nombre'] }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                    @endif
                    
                </div>
                <br>
                <div class="row" style="text-align:left;">
                    <div class="col-lg-12 col-md-12 col-sm-12"> 
                        <div style="margin-left: 5px;">
                            <button wire:click="buscarAgenda" class="btn btn-primary" style="border-radius:10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg> Buscar Agenda
                            </button>
                            <div wire:loading wire:target="buscarAgenda">
                                <x-cargando-dos />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
   
    @if($fechaAnticipadaFiltro != null)
    <br>
        <div>
            @livewire('atencion-medica.consulta-externa.agenda.agenda-anticipada-select')
        </div>
    @endif
    <div wire:loading wire:target="registrarAgendaAnticipadaSeleccionada">
        <x-cargando-dos />
    </div>

<script>
     
    window.addEventListener('alertaPersonalizada', function(event) {
        const data = event.detail[0];
        
        // Validamos los campos para que si son null muestren un espacio vacío
        const nombres = data.persona_agenda.nombres || '';
        const p_apellido = data.persona_agenda.p_apellido || '';
        const s_apellido = data.persona_agenda.s_apellido || '';
        const matricula = data.persona_agenda.matricula_seguro || 'No asignada';

        Swal.fire({
            title: data.title,
            text: data.text,
            icon: data.icon,    
            html: `
                <hr><em>Datos del paciente en agenda: </em>
                <p><strong>Persona:</strong> 
                    ${p_apellido} ${s_apellido} ${nombres}
                </p>
                <p><strong>Matrícula:</strong> 
                    ${matricula}
                </p>
            `,
        });
    });

    document.addEventListener('medicoSinHorarios', function() {
        Swal.fire({
            title: "¡Sin horarios!",
            text: "Usted no tiene horarios agendados para esta fecha.",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar"
        });
    });
 
    document.addEventListener('confirmarAgenda', function () { 
        $('#modalAgendaAnticipada').modal('hide');
        const data = event.detail[0];
        console.log(data);
        Swal.fire({
            title: '¿Guardar agenda anticipada?',
            html: ` <div>
                        <h5>Está a punto de registrar una agenda anticipada</h5>
                        <p><strong>Fecha:</strong> ${data.fecha}</p>
                        <p><strong>Hora:</strong> ${data.hora}</p>
                        <input id="motivo" class="swal2-input" placeholder="Ingrese el motivo">
                    </div>
                `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'No, cancelar',
            preConfirm: () => {
            const inputMotivo = Swal.getPopup().querySelector('#motivo');
            const motivo = (inputMotivo?.value || '').trim();

            if (!motivo) {
                Swal.showValidationMessage('Por favor ingresa un motivo');
                return false;
            }

            return { motivo }; // 👈 objeto, no string
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registrarAgendaAnticipadaSeleccionada', result.value);
            }
        });
    });
    
    document.addEventListener('consultaExternaCreada', function() {
        Swal.fire({
            title: "¡Historia clinica registrada!",
            text: "La historia clinica fue registrada con exito.",
            icon: "success"
        });
    });

    document.addEventListener('limiteSuperado', function() {
        Swal.fire({
            title: '¡Límite Excedido!',
            icon: 'warning',
            text: 'Usted ha superado el límite permitido de fichas anticipadas (Recuerde máximo: 2 fichas por día).',
        });
    });

    document.addEventListener('fechaInvalida', function() {
        Swal.fire({
            title: "¡Fecha fuera de rango!",
            text: "La fecha seleccionada excede lo permitido (1 mes).",
            icon: "error"
        });
    });
</script>
