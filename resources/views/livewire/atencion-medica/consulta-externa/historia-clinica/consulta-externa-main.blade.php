<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 style="color:black;">ATENCIÓN MÉDICA - <b> LISTA DE CONSULTA EXTERNA</b></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end w-100">

                @can('super-admin-only')
                    <div>
                        <x-reportes-button wire:click="registrarReporteAtencion"><b>REPORTE DE ATENCIÓN MÉDICA</b>
                        </x-reportes-button>
                    </div>
                @endcan
                @can('ficha_extra.consulta_externa')
                    @if ($fecha_hoy)
                        <div class="ml-2">
                            <x-agregar-button wire:click="registrarFichaExtra"><b>REGISTRAR FICHA EXTRA</b>
                            </x-agregar-button>
                        </div>
                    @endif
                @endcan
                @can('ver_agendas_anteriores.consulta_externa')
                    <div class="ml-2">
                        <input type="date" wire:model="fechaFiltro" wire:keydown.enter="filtrarPorFecha"
                            class="form-control" style="border-radius:8px;" />
                    </div>
                    <div class="ml-2">
                        <button wire:click="filtrarPorFecha" class="btn btn-primary"
                            style="border-radius:8px;">Buscar</button>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <br>
    <div wire:loading wire:target="registrarHistorialClinico">
        <x-cargando-dos />
    </div>

    <div>
        @can('lista.consulta_externa')
            <div class="card-body pt-2 pr-0 pl-0">
                @livewire('atencion-medica.consulta-externa.agenda.agenda-table')
            </div>
        @endcan
        <div>
            @can('ficha_extra.consulta_externa')
                @include('livewire.atencion-medica.consulta-externa.historia-clinica.modalFichaExtraRegister')
            @endcan
            @can('agenda_anticipada.consulta_externa')
                @include('livewire.atencion-medica.consulta-externa.historia-clinica.modalAgendaAnticipada')
            @endcan
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>

    </div>
    @can('super-admin-only')
        <x-modal-small-sin-footer id="modalReporteAtencionMedica" labelledby="modalReporteAtencionMedica"
            title="REPORTE DE ATENCIÓN MÉDICA">

            @include('livewire.atencion-medica.consulta-externa.historia-clinica.modalReporteAtencion')

        </x-modal-small-sin-footer>
    @endcan
    <style>
        .swal-turno .swal-body {
            text-align: left;
        }

        .swal-turno .swal-row {
            text-align: left;
        }
    </style>

</div>

<script>
    document.addEventListener('llenarMotivo', (e) => {
        $('#modalFichaExtraRegister').modal('hide');
        //console.log(e.detail);
        const datosPaciente = e.detail.datosPaciente;
        const turnosDisponibles = e.detail.turnos ?? [];

        if (datosPaciente && Array.isArray(turnosDisponibles)) {

            const optionsHTML = turnosDisponibles.map(turnoObj => `
                <option value="${turnoObj.id_asignacion_horario}">${turnoObj.turno}</option>
            `).join('');

            // Inyectar estilos una sola vez
            if (!document.getElementById('swal-responsive-styles')) {
                const style = document.createElement('style');
                style.id = 'swal-responsive-styles';
                style.textContent = `
                .swal-turno { width: min(560px, 92vw) !important; padding: 1.25rem !important; }
                .swal-turno .swal-body {
                display: grid; gap: .75rem;
                }
                .swal-turno .swal-row { line-height: 1.35; }
                .swal-turno label { display:block; font-weight: 600; margin-top: .5rem; }
                .swal-turno select, .swal-turno input.swal2-input {
                width: 100% !important; box-sizing: border-box; margin: 0; /* sobreescribe márgenes de SweetAlert */
                }
                /* Tacto amigable en móvil */
                @media (max-width: 480px) {
                .swal-turno select, .swal-turno input.swal2-input { height: 44px; font-size: 16px; }
                .swal-turno .swal2-actions { flex-wrap: wrap; gap: .5rem; }
                .swal-turno .swal2-confirm, .swal-turno .swal2-cancel { flex: 1 1 auto; min-width: 130px; }
                }
            `;
                document.head.appendChild(style);
            }

            Swal.fire({
                title: '¿Motivo de la Ficha Extra?',
                customClass: {
                    popup: 'swal-turno'
                },
                html: `
                    <div class="swal-body">
                        <div class="swal-row">Nombre del Paciente:
                            <strong> 
                            ${datosPaciente.p_apellido || ''} 
                            ${datosPaciente.s_apellido || ''} 
                            ${datosPaciente.nombres || ''} </strong> 
                        </div>
                        <div class="swal-row">Matrícula:<strong> ${datosPaciente.matricula_seguro}</strong> </div>
                        <div class="swal-row">Carnet:<strong>  ${datosPaciente.ci}</strong></div>
                        <div class="swal-row">Sexo:<strong> ${datosPaciente.sexo === 'M' ? 'Masculino' : 'Femenino'}</strong> </div>
                        <div class="swal-row">Fecha de Nacimiento:<strong> ${datosPaciente.fecha_nacimiento.split('-').reverse().join('/')}</strong> </div>

                        <label for="select-turno"> <b style="color: #1F618D;"><em>Ingrese el turno y el motivo</em>:</b>   <b style="color:#cb4335;">*</b></label>
                        <select id="select-turno" class="swal2-select" style="border-radius:8px;">
                            <option value="" disabled selected>Seleccione turno</option>
                            ${optionsHTML}
                        </select>

                        <input id="swal2-input" class="swal2-input" placeholder="Ingrese el motivo" style="border-radius:8px;">
                    </div>
                `,
                confirmButtonText: 'GUARDAR',
                confirmButtonColor: '#28a745',
                showCancelButton: true,
                cancelButtonText: 'CANCELAR',
                cancelButtonColor: '#dc3545',
                focusConfirm: false,
                didOpen: (popup) => {
                    // Asegura foco en el select en desktop; en móvil evita abrir teclado
                    const sel = popup.querySelector('#select-turno');
                    if (window.matchMedia('(pointer:fine)').matches) sel?.focus();
                },
                preConfirm: () => {
                    const popup = Swal.getPopup();

                    const selectTurno = popup.querySelector('#select-turno');
                    const inputMotivo = popup.querySelector('#swal2-input');

                    // Si por alguna razón no encuentra el select
                    if (!selectTurno) {
                        Swal.showValidationMessage('No se encontró el select de turnos');
                        return false;
                    }

                    // La opción 0 es la de "Seleccione turno"
                    if (selectTurno.selectedIndex === 0) {
                        Swal.showValidationMessage('Por favor selecciona un turno');
                        return false;
                    }

                    const turno = selectTurno.value;
                    const motivo = (inputMotivo?.value || '').trim();

                    if (!motivo) {
                        Swal.showValidationMessage('Por favor ingresa un motivo');
                        return false;
                    }

                    return {
                        turno,
                        motivo
                    };
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    const {
                        turno,
                        motivo
                    } = result.value;
                    Livewire.dispatch('registrarFichaExtraServicio', {
                        turno,
                        motivo
                    });
                }
            });

        }
    });


    document.addEventListener('fichaExtraRegistrada', function() {
        Swal.fire({
            title: '¡Ficha Extra Registrada!',
            icon: "success",
            text: "La ficha extra se registro correctamente.",
            showConfirmButton: false,
            timer: 1200,
        })
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: 'Error',
            icon: "error",
            text: "Ocurrio un error, comuniquese con el administrador.",
        })
    });

    document.addEventListener('fichaAnticipadaRegistrada', function() {
        $('#modalAgendaAnticipada').modal('hide');
        Swal.fire({
            title: 'Ficha Anticipada Registrada!',
            icon: "success",
            text: "La ficha anticipada se registro correctamente.",
        })
    });
    document.addEventListener('fechaFiltrada', function() {
        Swal.fire({
            title: '¡Lista Actualizada!',
            icon: 'success',
            text: 'La lista de atención médica se actualizó correctamente.',
        });
    });
    document.addEventListener('confirmacionFalta', function() {
        Swal.fire({
            title: '¿Desea marcar esta agenda como falta?',
            html: `
            <div class="swal-body">
                <h5>Está a punto de registrar esta agenda como una falta.</h5>
                <label><b style="color:#1F618D;"><em>Ingrese el motivo</em>:</b> <b style="color:#cb4335;">*</b></label>
                <input id="motivo" class="swal2-input" placeholder="Ingrese el motivo" style="border-radius:8px;">
            </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar',
            preConfirm: () => {
                const inputMotivo = Swal.getPopup().querySelector('#motivo');
                const motivo = (inputMotivo?.value || '').trim();

                if (!motivo) {
                    Swal.showValidationMessage('Por favor ingresa un motivo');
                    return false;
                }

                return {
                    motivo
                }; // 👈 objeto, no string
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registrarFalta', result.value); // 👈 ahora es {motivo: "..."}
            }
        });
    });



    document.addEventListener('faltaRegistrada', function() {
        Swal.fire({
            title: '¡Falta Registrada!',
            icon: 'success',
            text: 'Se registro la falta exitosamente.',
            showConfirmButton: false,
            timer: 1500,
        });
    });

    document.addEventListener('modalReporteAtencion', function() {
        $('#modalReporteAtencionMedica').modal('show');
    });

    document.addEventListener('cerrarModalReporteAtencion', function() {
        $('#modalReporteAtencionMedica').modal('hide');
    });


    document.addEventListener('SinAsignacionHorario', function() {
        Swal.fire({
            title: 'Sin horario asignado',
            icon: 'warning',
            text: 'No cuenta con horarios de atencion habilitados',
        });
    });

    document.addEventListener('fichaExtraObservada', function() {
        $('#modalFichaExtraRegister').modal('hide');
        Swal.fire({
            title: 'Agenda no permitida',
            icon: "warning",
            text: "El paciente tiene observaciones en la Unidad de Afiliación y/o Vigencia de Derechos.",
        })
    });

    document.addEventListener('SinDatosEspecialista', function() {
        Swal.fire({
            title: '¡Usted no es Médico!',
            icon: 'warning',
            text: 'Necesita permisos de médico, por lo cual no puede registrar fichas extras.',
        });
    });
</script>
