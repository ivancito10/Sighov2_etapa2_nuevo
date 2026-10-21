<x-ver-modal id="verModalNotificacion" labelledby="verModalNotificacionLabel" title="Detalles Notificación">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-10 col-sm-12">
            <!-- Emisor, Fecha y Hora -->
            <div class="row mb-3">
                <!-- Emisor -->
                <div class="col-md-6 mb-3">
                    <label for="notificacion_emisor" class="form-label fw-bold" style="color: #4a4a4a;">
                        <i class="fas fa-user"></i> Emisor:
                    </label>
                    <input wire:model="notificacion_emisor" type="text" id="notificacion_emisor"
                           class="form-control" disabled style="height: 40px; font-size: 16px; background-color: #f7f7f7;">
                </div>
                <!-- Fecha -->
                <div class="col-md-3 mb-3">
                    <label for="notificacion_fecha_recibida" class="form-label fw-bold" style="color: #4a4a4a;">
                        <i class="fas fa-calendar"></i> Fecha Recibida:
                    </label>
                    <input wire:model="notificacion_fecha_recibida" type="text" id="notificacion_fecha"
                           class="form-control" disabled style="height: 40px; font-size: 16px; background-color: #f7f7f7;">
                </div>
                <!-- Hora -->
                <div class="col-md-3">
                    <label for="notificacion_hora_recibida" class="form-label fw-bold" style="color: #4a4a4a;">
                        <i class="fas fa-clock"></i> Hora Recibida:
                    </label>
                    <input wire:model="notificacion_hora_recibida" type="text" id="notificacion_hora"
                           class="form-control" disabled style="height: 40px; font-size: 16px; background-color: #f7f7f7;">
                </div>
            </div>
            <!-- Prioridad -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="notificacion_prioridad" class="form-label fw-bold" style="color: #4a4a4a;">
                        <i class="fas fa-flag"></i> Prioridad:
                    </label>
                    <input wire:model="notificacion_prioridad" type="text" id="notificacion_prioridad"
                           class="form-control" disabled style="height: 40px; font-size: 16px; background-color: #f7f7f7;">
                </div>
                <!-- Fecha -->
                <div class="col-md-3 mb-3">
                    <label for="notificacion_fecha_abierto" class="form-label fw-bold" style="color: #4a4a4a;">
                        <i class="fas fa-calendar"></i> Fecha Notificacion Abierta:
                    </label>
                    <input wire:model="notificacion_fecha_abierto" type="text" id="notificacion_fecha"
                           class="form-control" disabled style="height: 40px; font-size: 16px; background-color: #f7f7f7;">
                </div>
                <!-- Hora -->
                <div class="col-md-3">
                    <label for="notificacion_hora_abierto" class="form-label fw-bold" style="color: #4a4a4a;">
                        <i class="fas fa-clock"></i> Hora Notificacion Abierta:
                    </label>
                    <input wire:model="notificacion_hora_abierto" type="text" id="notificacion_hora"
                           class="form-control" disabled style="height: 40px; font-size: 16px; background-color: #f7f7f7;">
                </div>
            </div>
            
            <!-- Asunto -->
            <div class="mb-3">
                <label for="notificacion_id" class="form-label fw-bold text-center" style="color: #4a4a4a;">
                    <i class="fas fa-info-circle"></i> Asunto:
                </label>
                <input wire:model="notificacion_asunto" type="text" id="notificacion_id"
                       class="form-control" disabled style="height: 40px; font-size: 16px; background-color: #f7f7f7;">
            </div>
            <!-- Mensaje -->
            <div class="mb-3">
                <label for="notificacion_mensaje" class="form-label fw-bold text-center" style="color: #4a4a4a;">
                    <i class="fas fa-envelope"></i> Mensaje:
                </label>
                <textarea wire:model="notificacion_mensaje" id="notificacion_mensaje"
                          class="form-control" rows="5" disabled
                          style="font-size: 15px; background-color: #f7f7f7; resize: none;"></textarea>
            </div>
        </div>
    </div>
</x-ver-modal>

<script>
    window.addEventListener('modalVerNotificacion', event => {
        $('#verModalNotificacion').modal('show');
    });
</script>
