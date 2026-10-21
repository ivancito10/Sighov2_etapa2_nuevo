<style>
    .btn-buscar {
        position: relative;
        display: inline-block;
        color: #2c2b6e;
        border: 1px solid #D6680B;
        border-radius: 10px;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 10px 20px;
    }

    .btn-buscar::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #D6680B;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-buscar:hover {
        color: white;
    }

    .btn-buscar:hover::before {
        left: 0;
    }

    .btn-buscar svg {
        position: relative;
        z-index: 1;
        fill: #D6680B;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-buscar:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-buscar .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
        color: #D6680B;
    }

    .btn-buscar:hover .slot-content {
        color: white;
    }
</style>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-buscar']) }}" data-toggle="tooltip"
    data-placement="top" title="AGREGAR NUEVO">
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-search" 
        viewBox="0 0 16 16">
        <path
            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
    </svg>
    <span class="slot-content"><b>{{ $slot }}</b></span>
</button>
