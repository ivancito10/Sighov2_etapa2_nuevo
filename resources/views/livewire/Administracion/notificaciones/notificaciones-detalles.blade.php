<div>
    <div wire:offline>
        <div class="alert text-center shadow-lg rounded" role="alert" 
            style="width: 30%;
                   position: fixed;
                   right: 20px;
                   top: 15%;
                   padding: 15px;
                   border-left: 5px solid #dc3545;
                   background-color: #f8d7da;
                   color: #721c24;
                   font-weight: bold;
                   z-index: 1050;">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Sin Conexión</h4>
            <p>No hay conexión a internet. Verifica tu red o contacta al administrador.</p>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="ModalNotificaciones" tabindex="-1" aria-labelledby="ModalNotificacionesLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">NOTIFICACIONES</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('livewire.Administracion.notificaciones.notificacionesLista')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    window.addEventListener('ModalNotificaciones', event => {

        $('#ModalNotificaciones').modal('show');
    });
</script>