@props([
    'id',
    'labelledby',
    'title',
])

<div class="modal fade" {{ $id ? "id=$id" : "" }} tabindex="-1" aria-labelledby="{{ $labelledby }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#34495e; color:white;">
                <h5 class="modal-title" id="{{ $labelledby }}">  {{ $title ?? 'Modal' }}</h5>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
