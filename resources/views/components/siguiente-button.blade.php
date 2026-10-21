<style>
    .siguiente-button {
        min-width: 160px;
        height: 55px;

        color: #fff;
        padding: 5px 10px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: inline-block;
        outline: none;
        overflow: hidden;
        border-radius: 5px;
        border: none;
        background-color: #27ae60
    }

    .siguiente-button:hover {
        border-radius: 5px;
        padding-right: 24px;
        padding-left: 8px;
    }

    .siguiente-button:after {
        content: "\2192";
        position: absolute;
        opacity: 0;
        font-size: 40px;
        line-height: 50px;
        top: 0;
        right: -20px;
        transition: 0.4s;
    }

    .siguiente-button .slot-content {
        color: white;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn siguiente-button']) }}" data-toggle="tooltip"
    data-placement="top">

    <span class="slot-content">{{ $slot }}</span>
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" stroke="white"
        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="bi bi-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
    </svg>
</button>
