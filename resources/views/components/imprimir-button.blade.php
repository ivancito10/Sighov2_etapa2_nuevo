<style>
    .btn-custom-imprimir {
        position: relative;
        display: inline-block;
        color: #226687;
        border: 1px solid #226687;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 7px 20px;
    }

    .btn-custom-imprimir::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #226687;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-imprimir:hover {
        color: white;
    }

    .btn-custom-imprimir:hover::before {
        left: 0;
    }

    .btn-custom-imprimir svg {
        position: relative;
        z-index: 1;
        fill: #226687;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-imprimir:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-imprimir .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-imprimir:hover .slot-content {
        color: white;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-imprimir']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-printer-fill"
        viewBox="0 0 16 16">
        <path
            d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1" />
        <path
            d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
