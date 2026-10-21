<x-modal-sin-footer id="verModalMedicamentosRest" labelledby="verModalMedicamentosRestLabel" 
title="LISTA DE MEDICAMENTOS EN TRATAMIENTO">
    <div class="card p-3" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

        <p> <span style="display: inline-block; background-color: #F9E79F; color: black;   padding: 6px 12px; border-radius: 8px; font-size: 17px;">
                                    
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                </svg>
                A continuación los siguientes medicamentos están restringidos debido a su uso en tratamientos específicos. </span></p>

        @livewire('atencion-medica.consulta-externa.receta.medicamentos-rest-table')
    </div>
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="modal-footer " style="border: none; background-color: white;">
                <x-cancel-button wire:click="cerrarModalMedRest" title="FINALIZAR"> CERRAR</x-cancel-button>
            </div>
        </div>
    </div>


</x-modal-sin-footer>

<script>
    document.addEventListener('abrirModalVerMedicamentosRest', function() {
        $('#verModalMedicamentosRest').modal('show');
    })

    document.addEventListener('cerrarModalMedicamentosRest', function() {
        $('#verModalMedicamentosRest').modal('hide');

        setTimeout(function() {
            if ($('#modalRReceta').hasClass('show')) {
                $('body').addClass('modal-open');
                $('#modalRReceta').focus();
            }
        }, 400);

    })
</script>
