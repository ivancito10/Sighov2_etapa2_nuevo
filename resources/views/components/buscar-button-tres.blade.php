<style>
        .btn-buscarTres {
            position: relative;
            display: inline-block;
            color: #2c2b6e;
            border: 1px solid #03639d;
            border-radius:10px;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.5s;
            background-color: white;
            white-space: nowrap;
            width: 100%;
           /*  padding: 10px 20px; */
        }

        .btn-buscarTres::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 100%;
            width: 100%;
            background-color: #03639d;
            z-index: 0;
            transition: left 0.5s;
        }

        .btn-buscarTres:hover {
            color: white;
        }

        .btn-buscarTres:hover::before {
            left: 0;
        }

        .btn-buscarTres svg {
            position: relative;
            z-index: 1;
            fill: #03639d; /* Color inicial del SVG */
            transition: fill 0.5s;
        }

        .btn-buscarTres:hover svg {
            fill: white; /* Color del SVG al hacer hover */
        }

        .btn-buscarTres .slot-content {
            position: relative;
            z-index: 1;
            transition: color 0.5s;
            color:#03639d;
        }

        .btn-buscarTres:hover .slot-content {
            color: white;
        }
    </style>
<button {{ $attributes->merge(['type' => 'button','class' => 'btn btn-buscarTres'])}}" data-toggle="tooltip" data-placement="top" title="AGREGAR NUEVO" >
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
</svg>
        <span class="slot-content">{{ $slot }}</span>
    </button>