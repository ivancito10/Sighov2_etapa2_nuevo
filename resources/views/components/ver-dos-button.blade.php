<style>
    .btn-custom-green {
        position: relative;
        display: inline-block;
        color: #a5210a;
        border: 1px solid #a5210a;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 10px 20px;
    }

    .btn-custom-green::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #a5210a;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-green:hover {
        color: white;
    }

    .btn-custom-green:hover::before {
        left: 0;
    }

    .btn-custom-green svg {
        position: relative;
        z-index: 10;
        fill: #a5210a;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-green:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-green .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-green:hover .slot-content {
        color: white;
    }
</style>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-green']) }}" data-toggle="tooltip"
    data-placement="top" title="VER UBICACIÓN">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-geo-alt-fill"
        viewBox="0 0 16 16">
        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
