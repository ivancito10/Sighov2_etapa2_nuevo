<style>
    .btn-custom-agotarse {
        position: relative;
        display: inline-block;
        color: #D6482D;
        border: 1px solid #D6482D;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 3px 10px;
    }

    .btn-custom-agotarse::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #D6482D;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-agotarse:hover {
        color: white;
    }

    .btn-custom-agotarse:hover::before {
        left: 0;
    }

    .btn-custom-agotarse svg {
        position: relative;
        z-index: 1;
        fill: #bd1605;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-agotarse:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-agotarse .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-agotarse:hover .slot-content {
        color: white;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-agotarse']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
        class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
