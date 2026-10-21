<style>
    body {
        font-family: 'Montserrat', sans-serif;
        font-weight: 90;
    }

    .cargando {
        position: relative;
        color: #FFF;
        margin-top: 2em;
        letter-spacing: 0.08em;
        text-transform: uppercase
    }

    .contenedor-loader {
        height: 6em;
    }

    #contenedor {
        background-color: rgba(177, 188, 199, 1.5);
        display: grid;
        place-content: center;
        height: 20vh;
        color: #000;
    }

    .loader,
    .loader:before,
    .loader:after {
        /**este es el color de la carga abajo */
        background: white;
        animation: cargando 1s infinite ease-in-out;
        width: 1em;
        height: 4em;
    }

    .loader:before,
    .loader:after {
        position: absolute;
        top: 0;
        content: '';
    }

    .loader:before {
        left: -1.5em;
    }

    .loader {
        text-indent: -9999em;
        margin: 40% auto;
        position: relative;
        font-size: 11px;
        animation-delay: 0.16s;
    }

    .loader:after {
        left: 1.5em;
        animation-delay: 0.32s;
    }

    @keyframes cargando {

        0%,
        80%,
        100% {
            box-shadow: 0 0 #FFF;
            height: 4em;
        }

        40% {
            box-shadow: 0 -2em #ffffff;
            height: 5em;
        }
    }
</style>
<div id="contenedor">
    <div class="contenedor-loader">
        <div class="loader"></div>
    </div>
    <div class="cargando">Cargando...</div>
</div>