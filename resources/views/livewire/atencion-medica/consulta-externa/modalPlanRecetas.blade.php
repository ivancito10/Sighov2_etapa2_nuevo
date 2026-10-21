<div> 
    <x-modal-sin-footer id="modalRecList" 
    labelledby="modalRecListLabel" title="LISTADO DE RECETAS REGISTRADAS">

        @if($id_persona)
            @if($variable == 1)
                @livewire('atencion-medica.consulta-externa.receta.receta-persona-table' , ['id_persona' => $id_persona]) 
                
            @elseif($variable == 2)
                
                @livewire('atencion-medica.consulta-externa.receta.receta-persona-main-table' , ['id_persona' => $id_persona]) 
            
            @elseif($variable == 3)
                
                @livewire('atencion-medica.otras-consultas.receta-o-personas-main-table' , ['id_persona' => $id_persona]) 
            @elseif($variable == 4)
                
                @livewire('atencion-medica.otras-consultas.receta-o-personas-table' , ['id_persona' => $id_persona]) 
            @endif
        @endif 
         
          
    </x-modal-sin-footer>
</div>
<script>
    // Abrir modal normalmente
    window.addEventListener('modalRecetaListado', event => {
        $('#modalRecList').modal('show');
    });

    // Abrir popup correctamente
    window.addEventListener('abrirPopupReceta1', event => {
        const id = event.detail.id;
        const url = `/pdf-receta-preview/${id}`; 
        // Cerrar el modal ANTES de abrir el popup
        $('#modalRecList').modal('hide'); 
        // Abrir popup
        window.open(
            url,
            '_blank',
            'width=900,height=700,scrollbars=yes,resizable=yes'
        );

        // Evitar que la pantalla quede oscura
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
</script>


