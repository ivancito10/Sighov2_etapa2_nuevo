<x-ver-modal id="verPdfImagen" labelledby="verPdfImagenLabel" title=" IMAGENOLOGÍA ">
    @if($pdf_vista_imagen)
        <div class="alert alert-success">
            <strong>PDF Cargado Correctamente</strong>
        </div>

        <iframe src="data:application/pdf;base64,{{ $this->pdf_vista_imagen }}#toolbar=1" width="100%" height="600px" ></iframe>
    @else
        <div class="alert alert-danger">
            <strong>PDF No Encontrado</strong>
        </div>

    @endif

    
<!--
#toolbar=0
si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>

<script>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
    window.addEventListener('modalPdfImagenologia', event => {
        $('#verPdfImagen').modal('show');
    });

</script>