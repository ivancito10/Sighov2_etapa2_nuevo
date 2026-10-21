@props([
  'src'   => null,   // URL de la imagen (ej. asset('images/doctor.png'))
  'title' => 'Rx'
])

@php
  $id = $attributes->get('id') ?? ('zimg-'.uniqid());
@endphp

<div id="{{ $id }}" {{ $attributes->merge(['class'=>'zimg']) }} data-src="{{ $src }}">
  <style>
    /* Scope por id para no chocar estilos */
    #{{ $id }}{
      --bg:#0f1115; --fg:#e7e9ee; --soft:#1a1f29; --border:#222838; --accent:#4da3ff;
      background:var(--bg); color:var(--fg); border:1px solid var(--border);
      border-radius:.5rem; display:flex; flex-direction:column; min-height:280px; overflow:hidden;
    }
    #{{ $id }} .bar{
      display:flex; gap:.5rem; align-items:center; padding:.5rem .75rem;
      background:linear-gradient(180deg,#141822,#0f1115); border-bottom:1px solid var(--border);
      flex-wrap:wrap;
    }
    #{{ $id }} .sp{flex:1}
    #{{ $id }} button{
      background:var(--soft); color:var(--fg); border:1px solid #273046; border-radius:.5rem;
      padding:.4rem .7rem; font-weight:600; cursor:pointer;
    }
    #{{ $id }} button:hover{ border-color:var(--accent) }
    #{{ $id }} .stage{
      position:relative; background:#0b0e14; height:100%; overflow:hidden;
    }
    #{{ $id }} .stage::before{
      content:""; position:absolute; inset:0;
      background-image:linear-gradient(0deg,rgba(255,255,255,.03) 1px,transparent 1px),
                       linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);
      background-size:24px 24px; pointer-events:none;
    }
    #{{ $id }} .holder{
      position:absolute; top:50%; left:50%;
      transform: translate(-50%,-50%) scale(1); transform-origin:center center;
      will-change: transform;
    }
    #{{ $id }} img{
      max-width: none; max-height: none; user-select:none; -webkit-user-drag:none; display:block;
    }
    #{{ $id }} .ctr{ font-variant-numeric:tabular-nums; opacity:.8; }
  </style>

  <div class="bar">
    <strong style="opacity:.85">{{ $title }}</strong>
    <div class="sp"></div>
    <button type="button" data-act="out">–</button>
    <button type="button" data-act="in">+</button>
    <button type="button" data-act="fit">Ajustar</button>
    <button type="button" data-act="one">100%</button>
    <span style="margin-left:.5rem;opacity:.75">Zoom:</span>
    <span class="ctr" data-role="zoom">100%</span>
  </div>

  <div class="stage" data-role="stage" title="Rueda para zoom • Doble clic: +/–">
    <div class="holder"><img alt="imagen"></div>
  </div>

  <script>
  (() => {
    const root   = document.getElementById(@json($id));
    const stage  = root.querySelector('[data-role="stage"]');
    const holder = root.querySelector('.holder');
    const img    = holder.querySelector('img');
    const lbl    = root.querySelector('[data-role="zoom"]');
    const src    = root.dataset.src;

    // estado
    let scale = 1;
    let baseFit = 1;
    const MIN = 0.1, MAX = 8, STEP = 1.2;

    function apply(){
      holder.style.transform = `translate(-50%,-50%) scale(${scale})`;
      lbl.textContent = Math.round(scale*100) + '%';
    }

    function fit(){
      const r = stage.getBoundingClientRect();
      if(!img.naturalWidth || !img.naturalHeight || !r.width || !r.height) return;
      const m = 0.92;
      baseFit = Math.min((r.width*m)/img.naturalWidth, (r.height*m)/img.naturalHeight);
      scale = baseFit;
      apply();
    }

    function clamp(v,a,b){ return Math.max(a, Math.min(b, v)); }

    function zoom(factor){
      scale = clamp(scale * factor, MIN, MAX);
      apply();
    }

    // botones
    root.querySelector('[data-act="in"]').onclick  = ()=> zoom(STEP);
    root.querySelector('[data-act="out"]').onclick = ()=> zoom(1/STEP);
    root.querySelector('[data-act="fit"]').onclick = ()=> fit();
    root.querySelector('[data-act="one"]').onclick = ()=> { scale = 1; apply(); };

    // rueda y doble clic (zoom centrado)
    stage.addEventListener('wheel', e => { e.preventDefault(); zoom(e.deltaY<0?STEP:1/STEP); }, {passive:false});
    stage.addEventListener('dblclick', e => { zoom(scale < baseFit*1.05 ? (2.2*(1/baseFit)) : 1/1.8); });

    // cargar y ajustar
    function load(){
      if(!src){ lbl.textContent='—'; return; }
      img.onload = fit;
      img.src = src;
    }

    // responsive
    let raf=0; window.addEventListener('resize', ()=>{ cancelAnimationFrame(raf); raf=requestAnimationFrame(fit); });

    load();
  })();
  </script>
</div>
