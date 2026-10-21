<style>
    .btn-custom-archivo {
        position: relative;
        display: inline-block;
        color: #020a0e ;
        border: 1px solid #f5b041 ;
        cursor: pointer;
        overflow: hidden;
        transition: color 0.5s;
        background-color: white;
        padding: 7px 20px;
    }

    .btn-custom-archivo::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100%;
        width: 100%;
        background-color: #f5b041;
        
        z-index: 0;
        transition: left 0.5s;
    }

    .btn-custom-archivo:hover {
        color: white;
    }

    .btn-custom-archivo:hover::before {
        left: 0;
    }

    .btn-custom-archivo svg {
        position: relative;
        z-index: 1;
        fill: #020a0e ;
        /* Color inicial del SVG */
        transition: fill 0.5s;
    }

    .btn-custom-archivo:hover svg {
        fill: black;
        /* Color del SVG al hacer hover */
    }

    .btn-custom-archivo .slot-content {
        position: relative;
        z-index: 1;
        transition: color 0.5s;
    }

    .btn-custom-archivo:hover .slot-content {
        color: black;
    }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-archivo']) }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-folder2-open" viewBox="0 0 16 16">
        <path d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v.64c.57.265.94.876.856 1.546l-.64 5.124A2.5 2.5 0 0 1 12.733 15H3.266a2.5 2.5 0 0 1-2.481-2.19l-.64-5.124A1.5 1.5 0 0 1 1 6.14zM2 6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174-1.154C6.374 3.334 5.82 3 5.264 3H2.5a.5.5 0 0 0-.5.5zm-.367 1a.5.5 0 0 0-.496.562l.64 5.124A1.5 1.5 0 0 0 3.266 14h9.468a1.5 1.5 0 0 0 1.489-1.314l.64-5.124A.5.5 0 0 0 14.367 7z"/>
    </svg> 
    <span class="slot-content">{{ $slot }}</span>
</button>
