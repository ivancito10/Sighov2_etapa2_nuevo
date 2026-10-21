<x-ver-modal id="verPdfFtp" labelledby="verPdfFtpLabel" title="PLAN ">
    @if($pdf_plan != null)
        <div class="alert alert-success">
            <strong>PDF Cargado Correctamente</strong>
        </div>
        <iframe src="data:application/pdf;base64,{{ $this->pdf_plan }}#toolbar=1" width="100%" height="600px" ></iframe>
    @else
        <p class="alert alert-danger text-center"><strong>No se encontró el Pdf</strong></p>
    @endif
 
<!--
#toolbar=0
si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>

<script>
    window.addEventListener('modal_ftp_plan', event => {
        $('#verPdfFtp').modal('show');
    });

</script>