@props([
    'id',
    'labelledby',
    'title',
    'saveAction',
])

<div class="modal fade" {{ $id ? "id=$id" : "" }} tabindex="-1" aria-labelledby="{{ $labelledby }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-0 m-0 ">
        <div class="modal-header" style="background-color:#eaeded ">
               <div style="display: flex; align-items: center;">
               <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#ba4a00" class="bi bi-pen-fill" viewBox="0 0 16 16" style="margin-right: 5px;">
                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                </svg>
                    <h5 style="font-family: Verdana, sans-serif; color:black;" class="modal-title" id="{{ $labelledby }}">{{ $title }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
             <div class="card-body p-0 m-0 ">
                 <div class="modal-body pb-0">
                <div class="form-group">
                {{ $slot }}
                </div>
                </div>
             </div>
            <div class="modal-footer pt-0" style="border: none; background-color: white;">
                <x-cancel-button data-dismiss="modal">CANCELAR</x-cancel-button>
                <x-save-button onclick="{{ $saveAction }}">GUARDAR</x-save-button>
            </div>
        </div>
    </div> 
</div>