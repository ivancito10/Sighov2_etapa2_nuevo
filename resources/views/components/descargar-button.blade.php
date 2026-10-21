<style>
    .btn-custom-descargar {
        position: relative;
        display: inline-block;
        color: #0d5eab;
        border: 1px solid #0d5eab;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 10px 20px;
    }

    .btn-custom-descargar::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #0d5eab;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-descargar:hover {
        color: white;
    }

    .btn-custom-descargar:hover::before {
        left: 0;
    }

    .btn-custom-descargar svg {
        position: relative;
        z-index: 1;
        fill: #0d5eab;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-descargar:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-descargar .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-descargar:hover .slot-content {
        color: white;
    }
</style>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-descargar']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download"
        viewBox="0 0 16 16">
        <path
            d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
        <path
            d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
