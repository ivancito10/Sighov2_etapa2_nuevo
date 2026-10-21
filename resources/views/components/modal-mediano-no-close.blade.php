@props(['id', 'labelledby', 'title'])

<div class="modal fade" {{ $id ? "id=$id" : '' }} tabindex="-1" aria-labelledby="{{ $labelledby }}" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog custom-modal-size-no-close modal-dialog-centered">
        <div class="modal-content p-0 m-0 border shadow"
            style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
            <div class="modal-header"
                style="background-color: #34495e; color:white; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <h5 class="modal-title" id="{{ $labelledby }}"> {{ $title ?? 'Modal' }}</h5>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
<style>
.custom-modal-size-no-close {
    max-width: 57%; 
    width: 57%;
    height: 45vh; 
    margin-left: auto;   /* empuja hacia la derecha */
    margin-right: 5%; 
}
</style>