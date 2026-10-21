<x-small-ver-modal id="verPoliticaAgenda" labelledby="verPoliticaAgendaLabel" title="DATOS DE LA POLÍTICA AGENDA" wire:ignore.self>
    <div class="card"><div class="card-body">
        <dl class="mb-0">
            <div class="d-flex flex-column justify-content-between">
                <div class="d-flex flex-column" style="gap: 2px;">
                    <dt>CÓDIGO VERSIÓN:</dt><dd>{{ $codigo_version }}</dd>
                </div>
                <div class="d-flex flex-column" style="gap: 2px;">
                    <dt>CUPOS NORMALES:</dt><dd>{{ $cupos_normales }}</dd>
                </div>
                <div class="d-flex flex-column" style="gap: 2px;">
                    <dt>CUPOS ESPECIALES:</dt><dd>{{ $cupos_especiales }}</dd>
                </div>
                <div class="d-flex flex-column" style="gap: 2px;">
                    <dt>MAXIMAS SESIONES:</dt><dd>{{ $max_sesiones_normales }}</dd>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-center">
                <div class="d-flex flex-row" style="gap: 10px;">
                    <dt>ESTADO:</dt>
                    <dd 
                        class="{{ ($estado) ? 'badge badge-success' : 'badge badge-danger' }}" 
                        style="font-size:100%;"
                    >
                        {{ ($estado) ? 'ACTIVA' : 'INACTIVA' }}
                    </dd>
                </div>

            </div>
        </dl>
    </div>
</div>
</x-small-ver-modal>
