<div>
    <x-ver-modal id="verImagen" labelledby="verImagenLabel" title=" VER IMAGEN">

        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-header p-1 cabecera " >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                        </svg>
                        <b> IMAGEN REGISTRADA</b>
                    </div> 
                    <div class="row">
                        <div class="col-lg-12 col-md-12" align="center">  
                            <img 
                            class="img-thumbnail"
                            style="max-width: 100%; height: auto;"
                            @if($foto) src="{{ $foto }}" @endif>


                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                     

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                        <p> <b>ESTADO: </b> 
                        @if($estado == true)
                            <span style="color: #117A65; font-weight:bold;"> ACTIVO</span>
                        @elseif ($estado == false)
                            <span style="color: #C0392B; font-weight:bold;"> INACTIVO</span>
                        @endif
                        </p>
                            
                    </div>
                </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE DE IMAGEN: </strong>
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px; text-align:left;">
                                    {{ $nombre_imagen ?? 'sin dato'}}</p>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <strong> DESCRIPCIÓN: </strong>
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px; text-align:left;">
                                    {{ $descripcion ?? 'sin dato'}}</p>
                                 
                        </div>
                    </div>
                     
                </div>
            </div>

        </div>
    </x-ver-modal>
</div>
<script>
    window.addEventListener('ver_modal_imagen', event => {

        $('#verImagen').modal('show');
    });
</script>