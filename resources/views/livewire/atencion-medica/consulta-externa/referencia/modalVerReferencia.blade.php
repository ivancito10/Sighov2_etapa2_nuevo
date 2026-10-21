<x-modal-sin-footer id="modalVerReferencia" labelledby="modalVerReferencia" title="VISTA REFERENCIA">
    <div>
        @forelse ($referencias as $ref)
            <div class="card mb-3">
                <!--
                <div class="card-header" style="background-color: #1A5276; color:white;">
                    <p style="font-size:21px;"> <b>N° Referencia: </b> {{ $ref->nro_referencia }}</p>
                </div>
                -->
                <div class="card-body">
                     
                    <div class="table-responsive">
 
                        <table class="table table-sm table-bordered">
                            <thead style="background-color: #1A5276; color:white;">
                                 
                                <tr>
                                    <th style="width: 40%;">ESPECIALIDAD</th>
                                    <th style="width: 60%;">INDICACIONES</th> 
                                </tr>
                            </thead>
                            <tbody> 
                                    <tr>
                                        <td> {{ $ref->nombre_especialidad }}</td>
                                        <td>  {{ $ref->indicaciones }}</td>
                                         
                                    </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <p class="alert alert-danger text-center"><strong>SIN SOLICITUDES REGISTRADAS</strong></p>
        @endforelse
        <div class="modal-footer pt-0"
            style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
            <x-cancel-button onclick="cerrarModalReferencia()">CERRAR</x-cancel-button>
        </div>
    </div>
</x-modal-sin-footer>

<script>
    window.addEventListener('modalMostrarReferencia', () => {
        $('#modalVerHistoriaClinica').modal('hide');
        $('#modalVerReferencia').modal('show');
    });

    function cerrarModalReferencia() {
        $('#modalVerReferencia').modal('hide');
        $('#modalVerReferencia').on('hidden.bs.modal', function() {
            if ($('.modal.show').length > 0) {
                $('body').addClass('modal-open');
            }
        });
        $('#modalVerHistoriaClinica').modal('show');
    };

    window.addEventListener('mostrarAlertaCamposVacios', () => {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, completa todos los campos antes de continuar.',
            confirmButtonText: 'OK'
        });
    });
</script>
