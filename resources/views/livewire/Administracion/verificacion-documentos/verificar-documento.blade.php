<div>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            margin: auto;
            overflow: auto;
            background: linear-gradient(315deg,
                    rgba(101, 0, 94, 1) 3%,
                    rgba(60, 132, 206, 1) 38%,
                    rgb(48, 64, 238) 68%,
                    rgba(255, 25, 25, 1) 98%);
            animation: gradient 15s ease infinite;
            background-size: 400% 400%;
            background-attachment: fixed;
        }

        .logo {
            position: absolute;
            width: 230px;
            opacity: 0.4;
            right: 20px;
            top: 20px;
            z-index: -1;
        }

        .sighov2 {
            top: 0;
            width: 300px;
            height: 280px;
            z-index: -1;
            /* height: calc(100% - 00px); */
            /* object-fit: cover; Asegura que la imagen cubra el área disponible */
            /* object-position: center; */
            /* background-color: tomato; */
            margin-bottom: -70px;
        }

        .escudo {
            position: absolute;
            width: 220px;
            opacity: 0.2;
            left: -30px;
            top: 10px;
            z-index: -1;
        }

        .contenido {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex-direction: column;
            padding-left: 50px;
            padding-right: 50px;
            margin: auto;
            color: white;
            /* background-color: brown; */
            height: 100vh;
        }

        .card {
            background-color: hsla(229, 28%, 37%, 0.5);
            padding: 30px;
        }

        .imagessu {
            position: absolute;
            width: 500px;
            opacity: 0.4;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        @media (max-width: 600px) {
            .escudo {
                width: 200px;
            }

            .container {
                padding: 20px;
            }

            .imagessu {
                width: 300px;
            }

            .logo {
                position: absolute;
                width: 120px;
                bottom: 0;
                top: auto;
                left: 0;
                /* background-color: tomato; */
            }

            .card {
                padding: 10px;
            }
        }

        @media (max-width: 300px) {
            .escudo {
                width: 150px;
            }

            .imagessu {
                width: 250px;
            }
        }

        .icono {
            width: 100px;
            height: 100px;
            color: #4bc435;
        }

        .icono2 {
            width: 100px;
            height: 100px;
            color: crimson;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 0%;
            }

            50% {
                background-position: 100% 100%;
            }

            100% {
                background-position: 0% 0%;
            }
        }

        .wave {
            background: rgb(255 255 255 / 25%);
            border-radius: 1000% 1000% 0 0;
            position: fixed;
            width: 200%;
            height: 12em;
            animation: wave 10s -3s linear infinite;
            transform: translate3d(0, 0, 0);
            opacity: 0.8;
            bottom: 0;
            left: 0;
            z-index: -1;
        }

        .wave:nth-of-type(2) {
            bottom: -1.25em;
            animation: wave 18s linear reverse infinite;
            opacity: 0.8;
        }

        .wave:nth-of-type(3) {
            bottom: -2.5em;
            animation: wave 20s -1s reverse infinite;
            opacity: 0.9;
        }

        @keyframes wave {
            2% {
                transform: translateX(1);
            }

            25% {
                transform: translateX(-25%);
            }

            50% {
                transform: translateX(-50%);
            }

            75% {
                transform: translateX(-25%);
            }

            100% {
                transform: translateX(1);
            }
        }


        .boton_ini {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #4bc435;
            color: white;
            font-weight: bold;
            cursor: pointer;
            /* display: flex; */
            width: 100%;
            text-align: center;
        }

        .boton_ini:hover {
            background-color: azure;
            color: royalblue;
            transition: 0.5s;
        }

        .campo {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: self-end;
            font-weight: bold;
            gap: 10px;
        }

        .item_campo {
            text-align: end;
            font-weight: bold;
        }

        .respuesta {
            text-align: start;
            gap: 10px;
        }

        .datos {
            /* background-color: indianred; */
            display: grid;
            gap: 10px;
            grid-template-columns: auto auto;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .datos {
                grid-template-columns: auto;
            }

            .item_campo {
                text-align: center;
                font-weight: bold;
            }

            .respuesta {
                text-align: center;
            }
        }
    </style>

    <body>
        <img src="{{ asset('images/edificio_png_2024.png') }}" alt="" class="imagessu" />

        <img class="escudo" src="{{ asset('images/carnets/titular/ssu3.png') }}" alt="" />
        <img class="logo" src="{{ asset('images/logoSSUHDCarnet.png') }}" alt="" />
        <div class="contenido">
            <img class="sighov2" src="{{ asset('images/LogoBlanco - copia.png') }}" alt="" />
            <div class="card">
                <h1>Verificacion de Documentos SSU</h1>
                <div>
                    @if ($vigente)
                        <svg class="icono" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="size-6">
                            <path fill-rule="evenodd"
                                d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                                clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="icono2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="size-6">
                            <path fill-rule="evenodd"
                                d="M11.484 2.17a.75.75 0 0 1 1.032 0 11.209 11.209 0 0 0 7.877 3.08.75.75 0 0 1 .722.515 12.74 12.74 0 0 1 .635 3.985c0 5.942-4.064 10.933-9.563 12.348a.749.749 0 0 1-.374 0C6.314 20.683 2.25 15.692 2.25 9.75c0-1.39.223-2.73.635-3.985a.75.75 0 0 1 .722-.516l.143.001c2.996 0 5.718-1.17 7.734-3.08ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75ZM12 15a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75v-.008a.75.75 0 0 0-.75-.75H12Z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif
                    <div>
                        @if ($documento)
                            <h1>Documento Auténtico</h1>
                            <div class="datos">
                                <div class="item_campo">Tipo de Documento:</div>
                                <div class="respuesta">
                                    {{ $tipoDocumentoDescripcion . ' ' . $tipoDocumentoNombre ?? 'N/A' }}</div>
                                <div class="item_campo">Fecha de Generación:</div>
                                <div class="respuesta">{{ $fecha_formateada }}</div>
                                <div class="item_campo">Hora:</div>
                                <div class="respuesta">{{ $hora_formateada }}</div>
                                <div class="item_campo">Generado por la unidad:</div>
                                <div class="respuesta">{{ $documento->modulo }}</div>
                            </div>
                            {{-- <p>El documento con código de seguridad <strong>{{ $documento->codigo_seguridad }}</strong>
                                es auténtico.
                            </p> --}}

                            @if ($vigente)
                                <p>Este documento está vigente.</p>
                            @else
                                <p>Este documento ya no está vigente.</p>
                            @endif

                            <div>
                                @if ($documento->id_tipo_documento == 9 || $documento->id_tipo_documento == 10 || $documento->id_tipo_documento == 14)
                                <p>Fue generado por {{ $nombres . ' ' . $apellidos ?? 'N/A' }}</p>
                                @elseif($documento->id_tipo_documento == 5)
                                <p></p>   
                                @elseif($documento->id_tipo_documento == 3)
                                <p>Representante Legal: {{ $nombres . ' ' . $apellidos ?? 'N/A' }}</p> 
                                @else
                                <p>Pertenece al Afiliado: {{ $nombres . ' ' . $apellidos ?? 'N/A' }}</p>
                                @endif
                            </div>
                        @else
                            <h1>Documento No Válido</h1>
                            <p>El código de seguridad proporcionado no corresponde a un documento válido.</p>
                            <p>Por favor, verifique que el código sea correcto o póngase en contacto con el
                                administrador del sistema.
                            </p>
                        @endif

                    </div>
                    <a href="https://ssulapaz.org" >
                        <button class="boton_ini">Ir a la Página</button>
                    </a>
                </div>

            </div>
            <div class="wave"></div>
            <div class="wave"></div>
            <div class="wave"></div>
        </div>
    </body>
</div>
