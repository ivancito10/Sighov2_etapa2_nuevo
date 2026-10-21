<x-small-ver-modal id="verTecnica" labelledby="verTecnicaLabel" title="DATOS DE LA TÉCNICA" wire:ignore.self>
    <div class="card"><div class="card-body">
        <dl class="mb-0">

            <div class="d-flex flex-column justify-content-between">
                <div class="d-flex flex-column" style="gap: 2px;">
                    <dt>CÓDIGO:</dt><dd>{{ $codigo }}</dd>
                </div>
                <div class="d-flex flex-column" style="gap: 2px;">
                    <dt>NOMBRE:</dt><dd>{{ $nombre }}</dd>
                </div>
                <div class="d-flex flex-column" style="gap: 2px;">
                    <dt>GRUPO:</dt><dd>{{ $grupo ?: '—' }}</dd>
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
    </div></div>
</x-small-ver-modal>