<style>
    .btn-custom-red {
        position: relative;
            display: inline-block;
            color: #df4759;
            border: 1px solid #df4759;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.5s;
            background-color: white; 
        
    }
    .btn-custom-red::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 100%;
            width: 100%;
            background-color: #df4759 ;
            z-index: 0;
            transition: left 0.5s;
        }

        .btn-custom-red:hover {
            color: white;
        }

        .btn-custom-red:hover::before {
            left: 0;
        }

        .btn-custom-red svg {
            position: relative;
            z-index: 1;
            transition: fill 0.5s;
        }

        .btn-custom-red:hover svg {
            fill: white;
        }
</style>

<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-custom-red']) }} data-toggle="tooltip" data-placement="top" title="DESHABILITAR">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg"
        viewBox="0 0 16 16">
        <path
            d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
    </svg>
    {{ $slot }}
</button>