<style>
    .btn-custom-anular {
        position: relative;
        display: inline-block;
        color: #bd1605;
        border: 1px solid #bd1605;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 3px 10px;
    }

    .btn-custom-anular::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #bd1605;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-anular:hover {
        color: white;
    }

    .btn-custom-anular:hover::before {
        left: 0;
    }

    .btn-custom-anular svg {
        position: relative;
        z-index: 1;
        fill: #bd1605;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-anular:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-anular .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-anular:hover .slot-content {
        color: white;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-anular']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-x-lg"
        viewBox="0 0 16 16">
        <path
            d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
