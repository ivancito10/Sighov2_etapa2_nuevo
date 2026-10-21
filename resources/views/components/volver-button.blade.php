<style>
        .btn-custom-volver {
            min-width: 130px;
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
            background-color: #616a6b;
        }

        .btn-custom-volver:hover {
            border-radius: 5px;
            padding-left: 24px; /* Aumenta el padding a la izquierda */
            padding-right: 8px;
        }

        .btn-custom-volver:hover::after {
            opacity: 1;
            left: 10px; /* La flecha se moverá hacia la izquierda */
            color:white;
        }

        .btn-custom-volver:after {
            position: absolute;
            opacity: 0;
          
            line-height: 40px;
            top: 0;
            left: 10px; /* La flecha comienza fuera del botón */
            transition: 0.6s;
        }

        .btn-custom-volver:hover .slot-content {
            color: white;
            
        }

        .btn-custom-volver svg {
            fill: white; /* Color inicial del SVG */
            transition: fill 0.5s;
          /*   right:10px; */
         
        }

    </style>
<button {{ $attributes->merge(['type' => 'button','class' => 'btn btn-custom-volver'])}}" data-toggle="tooltip" data-placement="top" title="AGREGAR NUEVO" >
<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
  <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
  <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
</svg>
        <span class="slot-content">{{ $slot }}</span>
    </button>