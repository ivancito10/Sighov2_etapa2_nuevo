@props([
    'id' => 'videoCarousel',
    'videos' => [],
])

<div id="{{ $id }}" class="carousel slide carousel-vertical-videos" data-ride="carousel" data-interval="false">
    <div class="carousel-inner">
        @foreach ($videos as $index => $video)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <video class="d-block w-100" controls playsinline preload="none"
                       data-src="{{ asset($video['src']) }}">
                </video>
                <div class="carousel-caption">
                    <p style="font-size: clamp(16px, 3vw, 16px);">{{ $video['title'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Controles --}}
    <a class="carousel-control-prev" href="#{{ $id }}" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#{{ $id }}" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const carouselEl = $("#{{ $id }}");
        const videos = carouselEl.find("video");

        // Función para cargar un video si aún no tiene src
        function loadVideo(video) {
            if (video && video.dataset.src && !video.src) {
                video.src = video.dataset.src;
                video.load();
            }
        }

        // Cargar y preparar el primer video
        let firstVideo = carouselEl.find(".carousel-item.active video")[0];
        loadVideo(firstVideo);

        // Cuando un video termina, pasar al siguiente slide
        videos.each(function () {
            this.addEventListener("ended", () => {
                carouselEl.carousel("next");
            });
        });

        // Al cambiar de slide
        carouselEl.on("slid.bs.carousel", function (e) {
            // Pausar y reiniciar todos los videos
            videos.each(function () {
                this.pause();
                this.currentTime = 0;
            });

            // Cargar y reproducir el video del slide activo
            const currentVideo = $(e.relatedTarget).find("video")[0];
            if (currentVideo) {
                loadVideo(currentVideo);
                currentVideo.play().catch(() => {
                    // algunos navegadores bloquean autoplay si no hay interacción
                    console.log("Autoplay bloqueado, el usuario debe dar play.");
                });
            }
        });
    });
</script>
