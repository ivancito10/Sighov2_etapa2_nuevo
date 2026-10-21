<style>
    .css-button-arrow--red {
        min-width: 150px;
        height: 55px;
        color: #fff;
        padding: 0 20px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        border: none;
        background-color: #ef233c;
        overflow: hidden;
        gap: 10px;
    }

    .css-button-arrow--red svg {
        transition: transform 0.3s ease;
        color: white;
    }

    .css-button-arrow--red:hover svg {
        transform: translateX(-8px);
        color: white;
    }

    .slot-content-salir {
        font-size: 18px;
        white-space: nowrap;
        color: white;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn css-button-arrow--red']) }}" data-toggle="tooltip"
    data-placement="top">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="white" stroke-width="2.5"
        stroke-linecap="round" stroke-linejoin="round" class="bi bi-arrow-left" viewBox="0 0 24 24">
        <path d="M19 12H5M12 5l-7 7 7 7" />
    </svg>

    <span class="slot-content-salir">{{ $slot }}</span>
</button>
