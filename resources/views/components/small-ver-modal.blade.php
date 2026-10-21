@props(['id', 'labelledby', 'title'])

<div class="modal fade" {{ $id ? "id=$id" : '' }} tabindex="-1" aria-labelledby="{{ $labelledby }}" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content p-0 m-0  border shadow "
            style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
            <div class="modal-header"  style="background-color: #eaeded; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <div style="display: flex; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#2471a3"
                        class="bi bi-eye-fill" viewBox="0 0 16 16" style="margin-right: 5px;">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                        <path
                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                    </svg>
                    <h5 style="font-family: Arial, sans-serif; color:black;" class="modal-title"
                        id="{{ $labelledby }}"> <b>{{ $title }} </b></h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:black">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body p-0 m-0" style="border: none; background-color: white;">
                <div class="modal-body">
                    <div class="form-group">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0"  style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                <x-cancel-button data-dismiss="modal">CANCELAR</x-cancel-button>
            </div>
        </div>
    </div>
</div>
