<div>
    <div class="input-group pr-4 mb-3" style="justify-content: space-between; align-items: center;">
        <h4 style="color: black; margin: 0;">ENFERMERÍA - <b>REGISTRO DE INYECCIONES</b></h4>
        
        @can('menu.enfermeria')
            <x-agregar-button onclick="window.location.href='{{ route('enfermeria.inyectables.create') }}'" title="REGISTRAR INYECCIÓN">
                REGISTRAR
            </x-agregar-button>
        @endcan
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body">
            @livewire('enfermeria.inyectables.inyectables-table')
        </div>
    </div>

    <!-- VENTANA EMERGENTE (MODAL VER) -->
    @if($modalVerAbierto && $detalleAtencion)
        <div class="modal fade show" 
             tabindex="-1" 
             style="display: block !important; background-color: rgba(0, 0, 0, 0.6); z-index: 99999;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0" style="border-radius: 6px;">
                    
                    <!-- Encabezado con Icono Ojo Azul y Cruz Superior de Cierre -->
                    <div class="modal-header d-flex align-items-center py-2 px-3" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <h5 class="modal-title font-weight-bold text-dark d-flex align-items-center" style="font-size: 15px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#007bff" class="bi bi-eye-fill mr-2" viewBox="0 0 16 16">
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                            </svg>
                            DETALLE DE ATENCIÓN DE INYECCIÓN #{{ $detalleAtencion['id'] }}
                        </h5>
                        <button type="button" class="close text-muted" wire:click="cerrarModalVer" style="outline: none; font-size: 22px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Cuerpo: Paneles estilo institucional -->
                    <div class="modal-body p-3" style="background-color: #ffffff;">
                        <div class="row">
                            <!-- Columna Izquierda: Datos del Paciente -->
                            <div class="col-md-5">
                                <div class="border rounded p-2 mb-3" style="background-color: #f8fafc; border-color: #e2e8f0;">
                                    <div class="text-center py-1 mb-2 font-weight-bold text-primary" style="background-color: #e0f2fe; border-radius: 4px; font-size: 12px; letter-spacing: 0.5px;">
                                        DATOS DEL PACIENTE
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-muted mb-0"><i class="fas fa-user mr-1"></i> NOMBRE COMPLETO:</label>
                                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $detalleAtencion['paciente']['nombre'] }}" readonly>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-muted mb-0"><i class="fas fa-id-card mr-1"></i> CARNET:</label>
                                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $detalleAtencion['paciente']['ci'] }}" readonly>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-muted mb-0"><i class="fas fa-barcode mr-1"></i> MATRÍCULA:</label>
                                        <input type="text" class="form-control form-control-sm bg-light" value="{{ $detalleAtencion['paciente']['matricula'] }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha: Atención y Medicamentos -->
                            <div class="col-md-7">
                                <div class="border rounded p-2 mb-3" style="background-color: #f8fafc; border-color: #e2e8f0;">
                                    <div class="text-center py-1 mb-2 font-weight-bold text-primary" style="background-color: #e0f2fe; border-radius: 4px; font-size: 12px; letter-spacing: 0.5px;">
                                        DATOS DE LA ATENCIÓN
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label class="small font-weight-bold text-muted mb-0"><i class="fas fa-file-medical mr-1"></i> # RECETA:</label>
                                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $detalleAtencion['nro_receta'] }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small font-weight-bold text-muted mb-0"><i class="fas fa-calendar-alt mr-1"></i> FECHA REGISTRO:</label>
                                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $detalleAtencion['fecha_registro'] }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="border rounded p-2" style="background-color: #f8fafc; border-color: #e2e8f0;">
                                    <div class="text-center py-1 mb-2 font-weight-bold text-primary" style="background-color: #e0f2fe; border-radius: 4px; font-size: 12px; letter-spacing: 0.5px;">
                                        INYECCIONES / MEDICAMENTOS REGISTRADOS
                                    </div>

                                    <table class="table table-sm table-bordered table-striped mb-0 text-center" style="font-size: 12px;">
                                        <thead class="bg-light">
                                            <tr>
                                                <th style="width: 45%;">MEDICAMENTO</th>
                                                <th style="width: 55%;">OBSERVACIÓN / VÍA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($detalleAtencion['medicamentos'] as $med)
                                                <tr>
                                                    <td class="text-left font-weight-bold">{{ $med['nombre'] }}</td>
                                                    <td class="text-left text-muted">{{ $med['observacion'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-muted">Sin medicamentos registrados.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Procedimientos Complementarios (si existen) -->
                        @if($detalleAtencion['procedimiento'])
                            <div class="border rounded p-2 mt-2" style="background-color: #f8fafc; border-color: #e2e8f0;">
                                <div class="text-center py-1 mb-2 font-weight-bold text-primary" style="background-color: #e0f2fe; border-radius: 4px; font-size: 12px; letter-spacing: 0.5px;">
                                    PROCEDIMIENTOS DE ENFERMERÍA REALIZADOS
                                </div>

                                <div class="d-flex flex-wrap justify-content-around py-1">
                                    <span class="badge {{ $detalleAtencion['procedimiento']['curacion_plana'] ? 'badge-success' : 'badge-light text-muted' }} p-2 m-1">
                                        <i class="fas {{ $detalleAtencion['procedimiento']['curacion_plana'] ? 'fa-check-circle' : 'fa-circle' }}"></i> Curación Plana
                                    </span>
                                    <span class="badge {{ $detalleAtencion['procedimiento']['curacion_infectada'] ? 'badge-success' : 'badge-light text-muted' }} p-2 m-1">
                                        <i class="fas {{ $detalleAtencion['procedimiento']['curacion_infectada'] ? 'fa-check-circle' : 'fa-circle' }}"></i> Curación Infectada
                                    </span>
                                    <span class="badge {{ $detalleAtencion['procedimiento']['oxigenoterapia'] ? 'badge-success' : 'badge-light text-muted' }} p-2 m-1">
                                        <i class="fas {{ $detalleAtencion['procedimiento']['oxigenoterapia'] ? 'fa-check-circle' : 'fa-circle' }}"></i> Oxigenoterapia
                                    </span>
                                    <span class="badge {{ $detalleAtencion['procedimiento']['retiro_puntos'] ? 'badge-success' : 'badge-light text-muted' }} p-2 m-1">
                                        <i class="fas {{ $detalleAtencion['procedimiento']['retiro_puntos'] ? 'fa-check-circle' : 'fa-circle' }}"></i> Retiro de Puntos
                                    </span>
                                    <span class="badge {{ $detalleAtencion['procedimiento']['prueba_sensibilidad'] ? 'badge-success' : 'badge-light text-muted' }} p-2 m-1">
                                        <i class="fas {{ $detalleAtencion['procedimiento']['prueba_sensibilidad'] ? 'fa-check-circle' : 'fa-circle' }}"></i> Prueba Sensibilidad
                                    </span>
                                    <span class="badge {{ $detalleAtencion['procedimiento']['sangria'] ? 'badge-success' : 'badge-light text-muted' }} p-2 m-1">
                                        <i class="fas {{ $detalleAtencion['procedimiento']['sangria'] ? 'fa-check-circle' : 'fa-circle' }}"></i> Sangría
                                    </span>
                                </div>

                                <div class="mt-2 px-2 small">
                                    <strong>Notas adicionales:</strong> <span class="text-muted">{{ $detalleAtencion['procedimiento']['observaciones'] }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Footer con botón CERRAR rojo (con su cruz) -->
                    <div class="modal-footer py-2 px-3 d-flex justify-content-end" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                        <button type="button" 
                                class="btn btn-sm btn-outline-danger font-weight-bold d-flex align-items-center" 
                                wire:click="cerrarModalVer" 
                                style="border-radius: 4px; padding: 4px 14px;">
                            <i class="fas fa-times mr-1"></i> CERRAR
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- Scripts de SweetAlert2 -->
    <script>
        document.addEventListener('estadoActualizado', function () {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '¡Actualizado!',
                    text: 'El estado de la atención se actualizó con éxito.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });

        document.addEventListener('errorActualizacion', function () {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo actualizar el estado del registro.',
                    icon: 'error'
                });
            }
        });
    </script>
</div>