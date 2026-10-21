<x-modal-sin-footer id="modalVerInternacion" labelledby="modalVerInternacion" title="VISTA INTERNACIÓN">
    <div>
        @forelse ($internaciones as $inter)
            <div class="card mb-3">
                 
                <div class="card-body">
                    

                    <div class="table-responsive">
 
                        <table class="table table-sm table-bordered">
                            <thead style="background-color: #1A5276; color:white;">
                                 
                                <tr>
                                    <th style="width: 15%;">FECHA INTERNACIÓN</th>
                                    <th style="width: 85%;">INDICACIONES</th> 
                                </tr>
                            </thead>
                            <tbody> 
                                    <tr>
                                        <td> {{ date('d/m/Y', strtotime($inter->fecha_internacion)) }}</td>
                                        <td>  {{ $inter->indicaciones }}</td>
                                         
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
            <x-cancel-button onclick="cerrarModalInternacion()">CERRAR</x-cancel-button>
        </div>
    </div>
</x-modal-sin-footer>

<script>
    window.addEventListener('modalMostrarInternacion', () => {
        $('#modalVerHistoriaClinica').modal('hide');
        $('#modalVerInternacion').modal('show');
    });

    function cerrarModalInternacion() {
        $('#modalVerInternacion').modal('hide');
        $('#modalVerInternacion').on('hidden.bs.modal', function() {
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
