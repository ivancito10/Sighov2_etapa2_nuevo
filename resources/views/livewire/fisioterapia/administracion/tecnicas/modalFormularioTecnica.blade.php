<x-modal-small-sin-footer id="formularioTecnica" labelledby="formularioTecnicaLabel"
    :title="$idTecnica ? 'EDITAR TÉCNICA' : 'REGISTRAR TÉCNICA'" wire:ignore.self>
    <form wire:submit="solicitarGuardar">
        <div class="card"><div class="card-body">
            <p class="text-info"><em>Complete la información correspondiente a la técnica.</em></p>
            @if ($errorOperacion)<div class="alert alert-danger" role="alert">{{ $errorOperacion }}</div>@endif
            @foreach ([
                'codigo' => ['CÓDIGO', 50], 
                'nombre' => ['NOMBRE', 255], 
                'grupo' => ['GRUPO', 100]
                ] as $campo => [$etiqueta, $maximo])
                <div class="form-group">
                    <label for="tecnica-{{ $campo }}">{{ $etiqueta }}: @if ($campo !== 'grupo')<span class="text-danger">*</span>@endif</label>
                    <input id="tecnica-{{ $campo }}" class="form-control @error($campo) is-invalid @enderror" wire:model="{{ $campo }}" maxlength="{{ $maximo }}" @required($campo !== 'grupo')>
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
