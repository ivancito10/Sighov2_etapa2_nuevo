@props(['id', 'labelledby', 'title'])
<div class="accordion" id="{{ $id }}">
    <div class="card " style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
        <div class="card-header p-1" style="background-color:#f2f8fd" id="{{ $labelledby }}">
            <h2 class="mb-0">
                <button
                    class="btn btn-outline btn-block text-left collapsed d-flex justify-content-between align-items-center"
                    type="button" style="color: #1b5989" data-toggle="collapse"
                    data-target="#{{ $id }}Contenido" aria-expanded="false"
                    aria-controls="c{{ $id }}Contenido">
                    <strong>{{ $title }} </strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-caret-down-fill " viewBox="0 0 16 16">
                        <path
                            d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                    </svg>
                </button>
            </h2>
        </div>
        <div id="{{ $id }}Contenido" class="collapse" aria-labelledby="{{ $labelledby }}"
            data-parent="#{{ $id }}">
            <div class="card-body p-2">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
