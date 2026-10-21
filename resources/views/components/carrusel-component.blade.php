@props([
    'id' => 'carouselDefault', // ID único del carrusel
    'images' => [], // Array de imágenes
    'withIndicators' => true, // Mostrar indicadores
    'withControls' => true, // Mostrar controles
])

<div id="{{ $id }}" class="carousel slide" data-ride="carousel">

    {{-- Indicadores --}}
    @if ($withIndicators)
        <ol class="carousel-indicators">
            @foreach ($images as $index => $img)
                <li data-target="#{{ $id }}" data-slide-to="{{ $index }}"
                    class="{{ $index === 0 ? 'active' : '' }}"></li>
            @endforeach
        </ol>
    @endif

    {{-- Imágenes --}}
    <div class="carousel-inner" style="background-color:#f2f4f4; padding-right:1rem; border-radius:10px;">
        @foreach ($images as $index => $img)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <div class="image-container position-relative d-flex justify-content-center">
                    {{-- Imagen --}}
                    <img src="{{ asset($img) }}" class="d-block mx-auto" width="400" height="300"
                        alt="slide-{{ $index }}" style="cursor: zoom-in;"
                        data-toggle="modal" data-target="#modalZoom-{{ $id }}"
                        data-img="{{ asset($img) }}">
                </div>
            </div>
        @endforeach
    </div>

    {{-- Controles --}}
    @if ($withControls)
        <a class="carousel-control-prev" href="#{{ $id }}" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#{{ $id }}" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
        </a>
    @endif
</div>

{{-- Modal para zoom --}}
<div class="modal fade" id="modalZoom-{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body text-center">
                <img id="zoomImg-{{ $id }}" src="" class="img-fluid rounded shadow-lg"
                    alt="zoom">
            </div>
        </div>
    </div>
</div>

{{-- Script para cambiar dinámicamente la imagen en el modal --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("modalZoom-{{ $id }}");
        const modalImg = document.getElementById("zoomImg-{{ $id }}");

        $('#modalZoom-{{ $id }}').on('show.bs.modal', function(event) {
            const img = $(event.relatedTarget); // en BS4 usamos jQuery
            modalImg.src = img.data('img');
        });
    });
</script>
