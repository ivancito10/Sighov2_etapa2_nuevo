@props(['id', 'labelledby'])

<div class="modal fade" {{ $id ? "id=$id" : '' }} tabindex="-1" aria-labelledby="{{ $labelledby }}" aria-hidden="true">
    <div class="modal-dialog custom-modal-size">
        <div class="modal-content p-0 m-0 border shadow "  style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
            <div class="pr-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:black">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body ">
                <div class="modal-body p-0" style="max-height: 800px; overflow-y: auto; overflow-x: hidden; ">
                    <div class="form-group">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .custom-modal-size {
        max-width: 90%;
        /* Ajusta el ancho máximo del modal */
        width: 90%;
        height: 40vh;
        /* Ajusta la altura del modal */
    }
</style>
