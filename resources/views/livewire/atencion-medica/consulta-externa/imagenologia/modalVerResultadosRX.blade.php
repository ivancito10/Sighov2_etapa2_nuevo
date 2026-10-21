<style>
    .zoom-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        transform-origin: center center;
        transition: transform 0.2s ease;
    }

    .rx-imagen {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .zoom-controls {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    }

    .zoom-controls button {
        font-size: 18px;
        font-weight: bold;
    }

    .zoom-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        width: fit-content;
        height: fit-content;
        transform-origin: center center;
        transition: transform 0.1s ease;
        cursor: grab;
    }

    .zoom-wrapper:active {
        cursor: grabbing;
    }

    .rx-imagen {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        user-select: none;
        pointer-events: none;
    }
    }
</style>

<x-ver-dos-modal id="verRxs" labelledby="verRxsLabel" title="RESULTADOS IMAGENOLOGÍA">
    <div>
        <p>
                <b>OBSERVACIONES:</b> {{ $resultadoObservaciones }}
        </p>
    </div>
    <div class="row g-0" x-data="visorRX()" x-init="init()">

        <!-- MINIATURAS -->
        <div class="col-12 col-md-3 border-end" style="max-height:70vh; overflow-y:auto;">
            @forelse ($resultadosRxfotos as $foto)
                @if ($foto->imagen_base64)
                    <img src="data:image/jpeg;base64,{{ $foto->imagen_base64 }}" class="img-thumbnail mb-2 w-100"
                        style="cursor:pointer;" @click="seleccionar(@js($foto->imagen_base64))">
                @endif
            @empty
                <p class="text-muted text-center mt-3">No hay imágenes</p>
            @endforelse
        </div>

        <!-- VISOR PRINCIPAL -->
        <div class="col-12 col-md-9 bg-dark position-relative d-flex justify-content-center align-items-center"
            style="height:70vh; overflow:auto;">

            <!-- BOTONES ZOOM -->

            <div class="zoom-controls btn-group-vertical">
                <button class="btn btn-light btn-sm" @click="zoomIn()" title="Acercar">
                    ➕
                </button>
                <button class="btn btn-light btn-sm" @click="zoomOut()" title="Alejar">
                    ➖
                </button>
                <button class="btn btn-light btn-sm" @click="resetZoom()" title="Restablecer">
                    🔄
                </button>
            </div>

            <!-- VISOR -->
            <div class="visor-central w-100 h-100 d-flex justify-content-center align-items-center">
                <template x-if="imagenSeleccionada">
                    <div class="zoom-wrapper" @mousedown="startDrag($event)" @mousemove="onDrag($event)"
                        @mouseup="endDrag" @mouseleave="endDrag" :style="transformStyle">
                        <img :src="imagenSeleccionada" alt="RX" class="rx-imagen" draggable="false">
                    </div>
                </template>
            </div>

        </div>

    </div>

</x-ver-dos-modal>


<script>
    function visorRX() {
        return {
            imagenSeleccionada: null,

            // Zoom
            escala: 1,

            // Pan (desplazamiento)
            offsetX: 0,
            offsetY: 0,

            // Drag control
            dragging: false,
            startX: 0,
            startY: 0,

            init() {
                @if (!empty($resultadosRxfotos) && count($resultadosRxfotos) > 0)
                    this.seleccionar(@js($resultadosRxfotos[0]['imagen_base64']));
                @endif
            },

            seleccionar(imagenBase64) {
                this.imagenSeleccionada = 'data:image/jpeg;base64,' + imagenBase64;
                this.resetZoom();
            },

            zoomIn() {
                if (this.escala < 4) {
                    this.escala += 0.2;
                }
            },

            zoomOut() {
                if (this.escala > 0.4) {
                    this.escala -= 0.2;
                }
            },

            resetZoom() {
                this.escala = 1;
                this.offsetX = 0;
                this.offsetY = 0;
            },

            startDrag(event) {
                if (this.escala <= 1) return;

                this.dragging = true;
                this.startX = event.clientX - this.offsetX;
                this.startY = event.clientY - this.offsetY;
            },

            onDrag(event) {
                if (!this.dragging) return;

                this.offsetX = event.clientX - this.startX;
                this.offsetY = event.clientY - this.startY;
            },

            endDrag() {
                this.dragging = false;
            },

            get transformStyle() {
                return `
                transform: 
                    translate(${this.offsetX}px, ${this.offsetY}px)
                    scale(${this.escala});
            `;
            }
        }
    }
</script>




<script>
    window.addEventListener('abrirModalResultadosPlacasRXImagenes', event => {

        $('#verRxs').modal('show');
    });
</script>
