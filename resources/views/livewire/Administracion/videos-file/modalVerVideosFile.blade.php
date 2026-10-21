<div>
    <x-small-ver-modal id="modalVideosFile" labelledby="modalVideosFileLabel" title="DETALLES DEL VIDEO">
        <div class="card card-borde p-2">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                    <strong>NOMBRE:</strong>
                    <p class="p-comun-disabled p-1">{{ $nombre_v }} </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                    <strong>TÍTULO:</strong>
                    <p class="p-comun-disabled p-1">{{ $nombre_v }} </p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-2">
                    <strong>DESCRIPCIÓN:</strong>
                    <p class="p-comun-disabled p-1">{{ $nombre_v }} </p>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12 mt-2 ">
                    <strong> TIPO:</strong>
                    <p class="p-comun-disabled p-1">{{ $externo_v }} </p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 mt-2">
                    <strong> DURACIÓN:</strong>
                    <p class="p-comun-disabled p-1">{{ $permanente_v }} </p>
                </div>


                @if ($permanente2 === false)
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
                        <strong>FECHA INICIO:</strong>
                        <p class="p-comun-disabled p-1">
                            {{ \Carbon\Carbon::parse($fecha_inicio_v)->Format('d /m/ Y') }}
                        </p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-6 mt-2">
                        <strong for="fecha_fin">FECHA FIN:</strong>
                        <p class="p-comun-disabled p-1">
                            {{ \Carbon\Carbon::parse($fecha_fin_v)->Format('d /m/ Y') }}
                        </p>
                    </div>
                @endif


            </div>

        </div>
        <div class="card card-borde p-2">
            {{ $ruta_v }}
            <video width="400" height="300" controls>
                <source src="{{ asset($ruta_v) }}" type="video/mp4">
            </video>
        </div>
    </x-small-ver-modal>
</div>
<script>
    document.addEventListener('abrirModalVideos', function() {
        $('#modalVideosFile').modal('show');
    });
</script>
