<style>
    .btn-custom-guardar {
        position: relative;
        display: inline-block;
        color: #008f39;
        border: 1px solid #008f39;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 7px 20px;
    }

    .btn-custom-guardar::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #008f39;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-guardar:hover {
        color: white;
    }

    .btn-custom-guardar:hover::before {
        left: 0;
    }

    .btn-custom-guardar svg {
        position: relative;
        z-index: 1;
        fill: #008f39;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-guardar:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-guardar .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-guardar:hover .slot-content {
        color: white;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-guardar']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-floppy-fill"
        viewBox="0 0 16 16">
        <path
            d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z" />
        <path
            d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
