<style>
    .btn-custom-green {
        position: relative;
        display: inline-block;
        color: #008000;
        border: 1px solid #008000;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 6px 12px;
    }

    .btn-custom-green::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #008000;
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
        z-index: 1;
        transition: fill 0.5s;
    }

    .btn-custom-green:hover svg {
        fill: white;
    }
</style>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-green']) }} data-toggle="tooltip"
    data-placement="top" title="HABILITAR">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check"
        viewBox="0 0 16 16">
        <path
            d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
    </svg>
    {{ $slot }}
</button>
