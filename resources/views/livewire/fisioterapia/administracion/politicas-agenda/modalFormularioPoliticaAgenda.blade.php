<x-modal-small-sin-footer id="formularioPoliticaAgenda" labelledby="formularioPoliticaAgendaLabel"
    :title="$idPoliticaAgenda ? 'EDITAR POLÍTICA AGENDA' : 'REGISTRAR POLÍTICA AGENDA'" wire:ignore.self>
    <form wire:submit="solicitarGuardar">
        <div class="card"><div class="card-body">
            <p class="text-info"><em>Complete la información correspondiente a la política agenda.</em></p>
            @if ($errorOperacion)<div class="alert alert-danger" role="alert">{{ $errorOperacion }}</div>@endif
            @foreach ([
                'codigo_version' => ['CÓDIGO VERSIÓN', 50], 
                'cupos_normales' => ['CUPOS NORMALES', 100], 
                'cupos_especiales' => ['CUPOS ESPECIALES', 100], 
                'max_sesiones_normales' => ['MAXIMAS SESIONES', 100]
                ] as $campo => [$etiqueta, $maximo])
                <div class="form-group">
                    <label for="politicaagenda-{{ $campo }}">{{ $etiqueta }}: @if ($campo !== 'grupo')<span class="text-danger">*</span>@endif</label>
                    <input id="politicaagenda-{{ $campo }}" class="form-control @error($campo) is-invalid @enderror" wire:model="{{ $campo }}" maxlength="{{ $maximo }}" 
                    type="{{ ($etiqueta == 'CÓDIGO VERSIÓN') ? 'text' : 'number' }}" 
                    {!! $etiqueta !== 'CÓDIGO VERSIÓN' ? 'min="1" step="1" max="15" ': '' !!}
                    @required($campo !== 'grupo')>
                    @error($campo)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach
        </div></div>
        <div class="modal-footer border-0">
            <x-cancel-button data-dismiss="modal" wire:click="cerrar">CANCELAR</x-cancel-button>
            <x-save-button type="submit" wire:loading.attr="disabled">GUARDAR</x-save-button>
        </div>
    </form>
</x-modal-small-sin-footer>
