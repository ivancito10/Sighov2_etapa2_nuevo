<style>
        .btn-custom-azul {
            position: relative;
            display: inline-block;
            color: #005b8f;
            border: 1px solid #005b8f;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.5s;
            background-color: white;
            padding: 10px 20px;
        }

        .btn-custom-azul::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 100%;
            width: 100%;
            background-color: #005b8f;
            z-index: 0;
            transition: left 0.5s;
        }

        .btn-custom-azul:hover {
            color: white;
        }

        .btn-custom-azul:hover::before {
            left: 0;
        }

        .btn-custom-azul svg {
            position: relative;
            z-index: 1;
            fill: #005b8f; /* Color inicial del SVG */
            transition: fill 0.5s;
        }

        .btn-custom-azul:hover svg {
            fill: white; /* Color del SVG al hacer hover */
        }

        .btn-custom-azul .slot-content {
            position: relative;
            z-index: 1;
            transition: color 0.5s;
        }

        .btn-custom-azul:hover .slot-content {
            color: white;
        }
    </style>
<button {{ $attributes->merge(['type' => 'button','class' => 'btn btn-custom-azul'])}}"  >
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
            class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
            <path
                d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z" />
        </svg>
        <span class="slot-content">{{ $slot }}</span>
    </button>