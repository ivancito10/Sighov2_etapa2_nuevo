<x-ver-modal id="verPdfSolicitudAlmacenModal" labelledby="verPdfSolicitudAlmacenModalLabel" title=" ARCHIVO PDF SOLICITUD">
    <iframe src="data:application/pdf;base64,{{ $this->pdf_vista }}" width="100%" height="600px" ></iframe>

    
<!--
#toolbar=0
si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>
<script>
    window.addEventListener('verPdfSolicitud', event => {
        
        $('#verPdfSolicitudAlmacenModal').modal('show');
        

    });

</script>
