<div>
    <x-modal-sin-footer id="verListaPersonas" labelledby="verListaPersonasLabel" 
    title=" LISTADO DE PACIENTES">
     
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
                <div class="card cabecera">
                    <b> LISTA DE ASEGURADOS</b>
                </div>
            </div> 
        </div>
        @livewire('farmacia.farmacia.receta.paciente-table')
        <br>
        <div class="modal-footer pt-0"
            style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
            <x-cancel-button onclick="cerrarModalPersonasLista()"> CANCELAR</x-cancel-button> 
        </div> 
    </x-modal-sin-footer>
   
</div> 
<script>
    window.addEventListener('modalPersonasLista', event => {
        $('#verListaPersonas').modal('show'); 
    });

    window.addEventListener('cerrarModalPersonasLista', event => {
        $('#verListaPersonas').modal('hide');  
    });
    function cerrarModalPersonasLista() {
        $('#verListaPersonas').modal('hide');
    }

     

</script>