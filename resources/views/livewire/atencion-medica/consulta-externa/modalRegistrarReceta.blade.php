<div>
    
    <x-modal-sin-footer id="modalRReceta" labelledby="modalRRecetaLabel"
        title="AGREGAR RECETA">    
        @livewire('atencion-medica.consulta-externa.receta.registrar-receta')
    </x-modal-sin-footer>
  
</div>

<script>
    window.addEventListener('modal_registrar_receta', event => {
        $('#modalRReceta').modal('show');
    });
    
</script>