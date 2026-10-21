<style>
        .btn-custom-ver {
            position: relative;
            display: inline-block;
            color: #2471a3;
            border: 1px solid #2471a3;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.5s;
            background-color: white;
            padding: 10px 20px;
        }

        .btn-custom-ver::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 100%;
            width: 100%;
            background-color: #2471a3;
            z-index: 0;
            transition: left 0.5s;
        }

        .btn-custom-ver:hover {
            color: white;
        }

        .btn-custom-ver:hover::before {
            left: 0;
        }

        .btn-custom-ver svg {
            position: relative;
            z-index: 1;
            fill: #2471a3; /* Color inicial del SVG */
            transition: fill 0.5s;
        }

        .btn-custom-ver:hover svg {
            fill: white; /* Color del SVG al hacer hover */
        }

        .btn-custom-ver .slot-content {
            position: relative;
            z-index: 1;
            transition: color 0.5s;
        }

        .btn-custom-ver:hover .slot-content {
            color: white;
        }
    </style>
<button {{ $attributes->merge(['type' => 'button','class' => 'btn btn-custom-ver'])}}" data-toggle="tooltip" data-placement="top" title="VER" >
<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-eye-fill"
        viewBox="0 0 16 16">
        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
    </svg>
        <span class="slot-content">{{ $slot }}</span>
    </button>



