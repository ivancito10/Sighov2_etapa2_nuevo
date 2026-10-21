@props(['id', 'labelledby', 'title'])
<div>
    <div class="modal fade " {{ $id ? "id=$id" : '' }} tabindex="-1" aria-labelledby="{{ $labelledby }}"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog custom-modal-size">
            <div class="modal-content p-0 m-0 border shadow"
                style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
                <div class="modal-header"
                    style="background-color: #eaeded; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <div style="display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#ba4a00"
                            class="bi bi-pen-fill" viewBox="0 0 16 16" style="margin-right: 5px;">
                            <path
                                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                        </svg>
                        <h5 style="font-family: Arial, sans-serif; color:black;" class="modal-title"
                            id="{{ $labelledby }}"><b>{{ $title }} </b> </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:black">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="max-height: 750px; overflow-y: auto;">
                    <div class="card-body p-0 m-0">
                        <div class="modal-body pb-0">
                            <div class="form-group">
                                {{ $slot }}
                            </div>
                        </div>
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
