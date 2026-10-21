<!-- este es el modal para cargar con circulos-->
<div class="modal-backdrop fade show" style="z-index: 1040; opacity: 0.5;"></div>
<div class="modal fade show" id="modalEspera" tabindex="-1" aria-labelledby="modalEsperaLabel" aria-hidden="true" style="display: block;">
    <div class="modal-dialog modal-dialog-centered modal-lg d-flex justify-content-center align-items-center">
        <div class="modal-content-animacion ">
            <div class="modal-body-animacion">
                <div class="spinner-container">
                    <div class="spinner">
                        <div class="circle circle1"></div>
                        <div class="circle circle2"></div>
                        <div class="circle circle3"></div>
                        <div class="circle circle4"></div>
                    </div>
                    <div class="text">Cargando...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content-animacion{
       background-color: transparent; 
    }
    .modal-body-animacion {
        padding: 0;
        margin: 0;
        height: 30vh;
        display: grid;
        place-items: center center;
    }

    .spinner-container {
        position: relative;
        height: 200px;
        width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .spinner {
        position: absolute;
        height: 100%;
        width: 100%;
        animation: spin 1.5s infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    .circle {
        position: absolute;
        border-radius: 50%;
    }

    .circle1 {
        height: 50px;
        width: 50px;
        background-image: linear-gradient(45deg,
                #f7d000,
                #e6970e);
        top: 20px;
        left: 20px;
        box-shadow: 0 0 3px #f7d000;
        animation: move1 1.5s infinite;
    }

    @keyframes move1 {
        50% {
            transform: translate(-15px, -15px) scale(0.3);
        }
    }

    .circle2 {
        height: 30px;
        width: 30px;
        background-image: linear-gradient(45deg,
                #00d7f7,
                #04c0f5);
        top: 25px;
        right: 30px;
        box-shadow: 0 0 2px #00d7f7;
        animation: move2 1.5s infinite;
    }

    @keyframes move2 {
        50% {
            transform: translate(7px, -10px) scale(0.55);
        }
    }

    .circle3 {
        height: 50px;
        width: 50px;
        background-image: linear-gradient(45deg,
                #f60180,
                #f7014f);
        bottom: 20px;
        right: 20px;
        box-shadow: 0 0 3px #f60180;
        animation: move3 1.5s infinite;
    }

    @keyframes move3 {
        50% {
            transform: translate(15px, 15px) scale(0.3);
        }
    }

    .circle4 {
        height: 30px;
        width: 30px;
        background-image: linear-gradient(45deg,
                #da00f7,
                #b501f6);
        bottom: 25px;
        left: 30px;
        box-shadow: 0 0 2px #da00f7;
        animation: move4 1.5s infinite;
    }

    @keyframes move4 {
        50% {
            transform: translate(-7px, 10px) scale(0.55);
        }
    }

    .text {
        position: relative;
        color: white;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }
</style>