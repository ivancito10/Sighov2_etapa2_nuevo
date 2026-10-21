<div>
    @if ($id_persona != null)
        <div class="card-body pt-2 pr-0 pl-0">
            @livewire('atencion-medica.consulta-externa.imagenologia.imagen-persona-table', ['id_persona' => $id_persona])
        </div>
    @endif
    @include('livewire.atencion-medica.consulta-externa.imagenologia.modalPdfImagenologia')
    @include('livewire.atencion-medica.consulta-externa.imagenologia.modalVerResultadosRX')

</div>
<script>
        document.addEventListener('sin_resultados_placas_rx', function() {
        Swal.fire({
            title: 'Resultados no encontrados.',
            icon: 'warning',
            text: 'No se encontraron los resultados de las solicitudes de imagenología.',
        });
    });


    
</script>

