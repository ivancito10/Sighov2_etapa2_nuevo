@props(['id', 'labelledby', 'title', 'saveAction1', 'saveAction2'])

<div class="modal fade" {{ $id ? "id=$id" : '' }} tabindex="-1" aria-labelledby="{{ $labelledby }}" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content p-0 m-0 border shadow"
            style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
            <div class="modal-header"
                style="background-color: #eaeded; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <div style="display: flex; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#0d5eab"
                        class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                        <path d="..."/>
                        <path fill-rule="evenodd" d="..."/>
                    </svg>
                    <h5 style="font-family: Verdana, sans-serif; color:black;" class="modal-title"
                        id="{{ $labelledby }}">{{ $title }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:black">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body p-0 m-0" style="background-color: white;">
                <div class="modal-body pb-0">
                    <div class="form-group">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0"
                style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                <x-cancel-button class="p-2" data-dismiss="modal">CANCELAR</x-cancel-button>
                <x-descargar-button class="p-2" onclick="{{ $saveAction1 }}"
                    wire:loading.attr="disabled">DESCARGAR </x-descargar-button>
                <x-excel-button class="p-2" onclick="{{ $saveAction2 }}"
                    wire:loading.attr="disabled">DESCARGAR</x-excel-button>
            </div>
        </div>
    </div>
</div>