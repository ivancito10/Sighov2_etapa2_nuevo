@props(['videos' => [], 'id' => 'videos'])

<style>
.video-16x9 { position: relative; width: 100%; background:#222; }
.video-16x9::before { content: ""; display: block; padding-top: 56.25%; }
.video-16x9 > video { position: absolute; inset: 0; width: 100%; height: 100%; }
</style>

<div id="{{ $id }}" class="carousel slide" data-ride="false">
  <div class="carousel-inner">
    @foreach ($videos as $i => $video)
      <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
        <div class="video-16x9">
          <video
            controls
            playsinline
            preload="none"      {{-- no descarga al entrar --}}
            data-lazy="1"
            width="100%"
            @if(!empty($video['poster'])) poster="{{ asset($video['poster']) }}" @endif
          >
            <source data-src="{{ asset($video['src']) }}" type="video/mp4">
          </video>
        </div>
        <div class="carousel-caption d-none d-md-block">
          <h6 class="mb-0">{{ $video['title'] }}</h6>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Controles (Bootstrap 4). Si usas BS5 cambia a data-bs-target / data-bs-slide --}}
  <a class="carousel-control-prev" href="#{{ $id }}" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Anterior</span>
  </a>
  <a class="carousel-control-next" href="#{{ $id }}" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Siguiente</span>
  </a>
</div>

@push('scripts')
<script>
(function () {
  var root = document.getElementById(@json($id));

  function lazyLoad(video) {
    if (!video || video.dataset.lazyInit === '1') return;
    video.querySelectorAll('source').forEach(function(s){
      if (s.dataset.src && !s.src) s.src = s.dataset.src;
    });
    video.load();
    video.dataset.lazyInit = '1';
  }

  // 0) Cargar el PRIMER slide visible para que el botón Play funcione de una
  var first = root.querySelector('.carousel-item.active video[data-lazy]');
  if (first) lazyLoad(first);

  // 1) Cargar cuando el video entre al viewport
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function(entries, obs){
      entries.forEach(function(e){
        if (e.isIntersecting) {
          lazyLoad(e.target);
          obs.unobserve(e.target);
        }
      });
    }, { rootMargin: '200px' });

    root.querySelectorAll('video[data-lazy]').forEach(function(v){ io.observe(v); });
  }

  // 2) Al cambiar de slide, pausar y cargar el nuevo
  root.addEventListener('slide.bs.carousel', function () {
    root.querySelectorAll('video').forEach(function(v){ v.pause(); });
  });

  root.addEventListener('slid.bs.carousel', function (ev) {
    var item = ev.relatedTarget || root.querySelector('.carousel-item.active');
    var vid = item ? item.querySelector('video[data-lazy]') : null;
    if (vid) lazyLoad(vid);
  });

  // 3) Si el usuario hace click/play y aún no hay src, cargar y reproducir
  root.querySelectorAll('video[data-lazy]').forEach(function(v){
    var handler = function () {
      if (v.dataset.lazyInit !== '1') {
        lazyLoad(v);
        v.addEventListener('canplay', function once(){ v.removeEventListener('canplay', once); v.play().catch(()=>{}); });
      }
    };
    v.addEventListener('click', handler, { once:false });
    v.addEventListener('play', handler, { once:false });
  });
})();
</script>
@endpush
