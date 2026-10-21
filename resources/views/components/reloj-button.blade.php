<style>
    .btn-custom-reloj {
        position: relative;
        display: inline-block;
        color: #0F5685;
        border: 1px solid #0F5685;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 7px 20px;
    }

    .btn-custom-reloj::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #0F5685;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-reloj:hover {
        color: white;
    }

    .btn-custom-reloj:hover::before {
        left: 0;
    }

    .btn-custom-reloj svg {
        position: relative;
        z-index: 1;
        fill: #0F5685;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-reloj:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-reloj .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-reloj:hover .slot-content {
        color: white;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-reloj']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clock-fill"
        viewBox="0 0 16 16">
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
