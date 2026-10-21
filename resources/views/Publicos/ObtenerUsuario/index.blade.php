<head>
    <meta charset="UTF-8">
    <title>Obtener Usuario - SIGHO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/ObtenerUsuario.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/ObtenerUsuario.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/ObtenerUsuario.ico') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @livewireStyles
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            margin: auto;
            overflow: auto;
            background: linear-gradient(315deg,
                    rgb(5, 114, 90) 3%,
                    rgba(60, 132, 206, 1) 38%,
                    rgb(48, 64, 238) 68%,
                    rgb(30, 173, 209) 98%);
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
            flex-direction: column;
            padding: 20px;
            margin: auto;
            color: white;
            min-height: 100vh;
        }

        .card-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
            width: 100%;
            max-width: 800px;
        }

        .card {
            background-color: hsla(229, 28%, 37%, 0.7);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 20px -30px;
            text-align: center;
        }

        .imagessu {
            position: absolute;
            width: 500px;
            opacity: 0.4;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        /* Botón mejorado */
        .btn-verificar {
            padding: 15px 30px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #1e88e5, #0d47a1);
            color: white;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-verificar:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #2196f3, #1565c0);
        }

        .btn-verificar:active {
            transform: translateY(1px);
        }

        .btn-verificar::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .btn-verificar:hover::after {
            left: 100%;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.3);
        }

        .form-label {
            font-weight: bold;
            color: white;
            margin-bottom: 8px;
            display: block;
            text-align: left;
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

        /* Card de resultados */
        .result-card {
            display: none;
            /* Inicialmente oculta */
        }



        .show-result .result-card {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-volver {
            background: linear-gradient(135deg, #757575, #424242);
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }

            .logo,
            .escudo {
                width: 150px;
            }

            .imagessu {
                width: 300px;
            }

            .btn-verificar {
                padding: 12px 20px;
                font-size: 1rem;
            }
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            padding: 1.5rem 0;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: white;
            font-size: 1rem;
            text-align: left;
        }

        .form-input {
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            color: #333;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        /* Estilos específicos para el CI dividido */
        .ci-container {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .ci-number-group {
            flex: 3;
        }

        .ci-complemento-group {
            flex: 1;
        }

        .ci-number {
            width: 100%;
        }

        .ci-complemento {
            width: 100%;
            appearance: none;
            /* background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); */
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 15px;
        }

        /* Estilos para errores */
        .form-error {
            color: #ff6b6b;
            font-size: 0.85rem;
            text-align: left;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-container {
                gap: 1rem;
                padding: 1rem 0;
            }

            .form-input {
                padding: 10px 12px;
            }

            .ci-container {
                flex-direction: column;
                gap: 0.5rem;
            }

            .ci-number-group,
            .ci-complemento-group {
                flex: auto;
                width: 100%;
            }
        }

        .logo-header {
            max-height: 50px;
            /* Ajusta según el tamaño de tu logo */
            width: auto;
            margin: 0 auto;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        /* Para versión responsive en móviles */
        @media (max-width: 768px) {
            .logo-header {
                max-height: 50px;
            }
        }
    </style>
</head>

<body>
    <img src="{{ asset('images/edificio_png_2024.png') }}" alt class="imagessu" />
    <img class="escudo" src="{{ asset('images/carnets/titular/ssu3.png') }}" alt />
    <img class="logo" src="{{ asset('images/logoSSUHDCarnet.png') }}" alt />

    <div class="contenido">
        <div class="card-container">
            <!-- Card del formulario -->
            <div class="card form-card">
                <div class="card-header">
                    <h1>Solicita tu Acceso al Sistema</h1>

                    <img src="{{ asset('images/LOGO_SIGHOV2.png') }}" alt="Logo SIGHO" class="logo-header">
                </div>
                @livewire('publicos.obtener-usuario.obtener-usuario')

            </div>
            <!-- Card de resultados (inicialmente oculta) -->
            <div class="card result-card">
                <div class="card-header">
                    <h2>Resultado de Verificación</h2>
                </div>

                <!-- Aquí iría el contenido dinámico de resultados -->
                <div id="result-content">
                    <!-- Se llenará con Livewire o JavaScript -->
                </div>
                <button class="btn-verificar btn-volver" onclick="toggleCards()">
                    ← Volver a verificar
                </button>
            </div>
        </div>

    </div>

    @livewireScripts

    {{-- reCAPTCHA v3 --}}
    <script src="https://www.google.com/recaptcha/api.js?render=6LdabRQrAAAAAAmL58eVgucILPj77fV81_9O-BB6"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>


        document.addEventListener("livewire:init", function () {
            function getRecaptchaToken() {
                grecaptcha.ready(function () {
                    grecaptcha.execute("{{ config('services.recaptcha.site_key') }}", {
                        action: "verificar"
                    }).then(function (token) {
                        Livewire.dispatch('setRecaptcha', { token: token });
                    });
                });
            }

            // Obtener token inicial
            getRecaptchaToken();

            // Escuchar evento para resetear el reCAPTCHA
            Livewire.on('resetRecaptcha', () => {
                getRecaptchaToken();
            });

            // Escuchar cuando la verificación es exitosa
            Livewire.on('verificacionExitosa', (data) => {
                document.querySelector('.card-container').classList.add('show-result');
                document.getElementById('result-content').innerHTML = `
                    <div style="text-align: center; margin: 20px 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="#4caf50" style="margin-bottom: 15px;">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <h3 style="color: #4caf50;">Verificación Exitosa</h3>
                        <p>Usuario válido encontrado en el sistema</p>
                        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; margin-top: 20px;">
                            <p><strong>Nombre:</strong> ${data.nombre}</p>
                            <p><strong>CI:</strong> ${data.ci}</p>
                            <p><strong>Matrícula:</strong> ${data.matricula}</p>
                        </div>
                    </div>
                `;
            });


        });



    </script>
    <script>
        window.addEventListener('reload-page', function () {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "La página expiró por inactividad",
                showConfirmButton: false,
                timer: 5500
            });

            setTimeout(function () {
                location.reload();
            }, 5500); // Espera lo mismo que el tiempo del SweetAlert
        });
    </script>


</body>