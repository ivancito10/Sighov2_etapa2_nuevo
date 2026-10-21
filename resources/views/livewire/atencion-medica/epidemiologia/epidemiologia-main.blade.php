<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">ATENCIÓN MÉDICA - <b> EPIDEMIOLOGÍA</b></h4>
    </div>
    <br>
    <div>
        <div class="scroll-container-epi">
            <div class=" row">
                @foreach ($archivos as $index => $file)
                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 p-2 text-center ">
                        <div class="card-pdf h-100  "
                            onclick="window.open('{{ asset('docs/ConsultaExterna/FichasEpidemiologicas/' . $file['archivo']) }}', '_blank', 'width=800,height=600'); return false;">
                            <div class="d-flex justify-content-center">
                                <x-ver-pdf-button-estatico />
                            </div>
                            <b>{{ $file['nombre'] }}</b>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
    <style>
        .scroll-container-epi {
            max-height: 500px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .card-pdf {
            border-radius: 6px;
            padding: 5px;
            background-color: #d0d0d0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</div>
