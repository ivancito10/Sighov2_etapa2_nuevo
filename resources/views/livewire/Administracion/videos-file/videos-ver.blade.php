<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">VISTA
                DE VIDEOS</h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12 col-12">
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
        </div>
        <div class="col-lg-5 col-md-12 col-sm-12 col-12">
            <div class="card card-borde p-2">
                <video width="auto" height="auto" controls>
                    <source src="{{ asset($ruta_v) }}" type="video/mp4">
                </video>
            </div>
        </div>
    </div>


</div>
