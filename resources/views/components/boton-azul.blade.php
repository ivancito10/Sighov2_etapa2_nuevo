<style>
        .btn-custom-azulcito {
            position: relative;
            display: inline-block;
            color: #3E5F8A;
            border: 1px solid #3E5F8A;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.5s;
            background-color: white;
            padding: 7px 20px;
        }

        .btn-custom-azulcito::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 100%;
            width: 100%;
            background-color: #3E5F8A;
            z-index: 0;
            transition: left 0.5s;
        }

        .btn-custom-azulcito:hover {
            color: white;
        }

        .btn-custom-azulcito:hover::before {
            left: 0;
        }

        .btn-custom-azulcito .slot-content {
            position: relative;
            z-index: 1;
            transition: color 0.5s;
        }

        .btn-custom-azulcito:hover .slot-content {
            color: white;
        }
    </style>
<button {{ $attributes->merge(['type' => 'button','class' => 'btn btn-custom-azulcito'])}}"  >
   
        <span class="slot-content">{{ $slot }}</span>
    </button>