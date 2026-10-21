<div>
    
    <x-modal-sin-footer-grande id="modalRReceta" labelledby="modalRRecetaLabel"
        title="AGREGAR RECETA">    
        @livewire('farmacia.farmacia.receta.receta-create')
        
    </x-modal-sin-footer-grande>
  
</div>

<script>
    window.addEventListener('modal_registrar_receta', event => {
        $('#modalRReceta').modal('show');
    }); 
    window.addEventListener('cerrar_modal_receta', event => {
        $('#modalRReceta').modal('hide');
    });
    
</script>