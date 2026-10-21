<style>
    .btn-custom-excel {
        position: relative;
        display: inline-block;
        color: #27ae60;
        border: 1px solid #27ae60;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 10px 20px;
    }

    .btn-custom-excel::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #27ae60;
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-excel:hover {
        color: white;
    }

    .btn-custom-excel:hover::before {
        left: 0;
    }

    .btn-custom-excel svg {
        position: relative;
        z-index: 1;
        fill: #27ae60;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-excel:hover svg {
        fill: white;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-excel .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-excel:hover .slot-content {
        color: white;
    }
</style>
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-excel']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
        class="bi bi-file-earmark-excel-fill" viewBox="0 0 16 16">
        <path
            d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M5.884 6.68 8 9.219l2.116-2.54a.5.5 0 1 1 .768.641L8.651 10l2.233 2.68a.5.5 0 0 1-.768.64L8 10.781l-2.116 2.54a.5.5 0 0 1-.768-.641L7.349 10 5.116 7.32a.5.5 0 1 1 .768-.64" />
    </svg>
    <span class="slot-content">{{ $slot }}</span>
</button>
