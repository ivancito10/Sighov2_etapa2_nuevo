<style>
    .btn-custom-agregar {
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

    .btn-custom-agregar::before {
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

    .btn-custom-agregar:hover {
        color: white;
    }

    .btn-custom-agregar:hover::before {
        left: 0;
    }

    .btn-custom-agregar svg {
        position: relative;
        z-index: 1;
        fill: #008f39;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-agregar:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-agregar .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-agregar:hover .slot-content {
        color: white;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-agregar']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-plus-square-fill"
        viewBox="0 0 16 16">
        <path
            d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
