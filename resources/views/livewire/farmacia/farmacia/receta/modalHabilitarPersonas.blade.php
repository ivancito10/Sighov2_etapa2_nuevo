<div>
    <x-ver-modal id="verListaPersonasH" labelledby="verListaPersonasHLabel" 
    title=" HABILITAR MEDICAMENTOS">
     
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
                <div class="card cabecera">
                    <b> LISTA DE PACIENTES ASEGURADOS</b>
                </div>
            </div>
        </div>
        @livewire('farmacia.farmacia.receta.personas-habilitar-table')
    </x-ver-modal>
   
</div> 
<script>
    window.addEventListener('modalAbrirListaPersonas', event => {
        $('#verListaPersonasH').modal('show'); 
    }); 

    window.addEventListener('cerrarPersonasListaHabilitarMed', event => {
        $('#verListaPersonasH').modal('hide'); 
        $('#verListaPersonasH').modal('show');
    });

    document.addEventListener('alertaNoAfiliado', function() {
        Swal.fire({
            title: "¡No es Afiliado!",
            text: "No puede habilitar medicamentos.",
            icon: "error", 
        }); 
    });
 

</script>