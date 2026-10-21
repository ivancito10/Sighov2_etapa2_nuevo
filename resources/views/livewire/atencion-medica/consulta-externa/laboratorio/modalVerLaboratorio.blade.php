<x-modal-sin-footer id="modalVerLaboratorio" labelledby="modalVerLaboratorio" title="VISTA LABORATORIO">
    <div>
        @forelse ($laboratorios as $lab)
            <div class="card mb-3">
                <div class="card-header" style="background-color: #CAD5E2; color:black; text-align:center; height:40px;">
                    <p style="font-size:21px;"><b> N° lab: </b> {{ $lab->nro_solicitud }} </p> 
                </div>
                <div class="card-body">
                    @if ($lab->examenesSolicitud->isNotEmpty())
                        <div class="table-responsive"> 
                            <table class="table table-sm table-bordered">
                                <thead style="background-color: #1f618d; color:white;">
                                    <tr>
                                        <th style="width: 30%;">NOMBRE EXAMEN</th>
                                        <th style="width: 30%;">GRUPO</th>
                                        <th style="width: 40%;">TIPO LABORATORIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lab->examenesSolicitud as $exa)
                                        <tr>
                                            <td>{{ $exa->nombre_examen }}</td>
                                            <td>{{ $exa->grupo }}</td>
                                            <td>{{ $exa->tipo_laboratorio }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <p class="text-muted"><em> No hay medicamentos registrados.</em></p>
                    @endif
                </div>
            </div>
        @empty
            <p class="alert alert-danger text-center"><strong>SIN SOLICITUDES REGISTRADAS</strong></p>
        @endforelse
        
        <div class="modal-footer pt-0"
            style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
            <x-cancel-button onclick="cerrarModalLaboratorio()">CERRAR</x-cancel-button>
        </div>
    </div>
</x-modal-sin-footer>

<script>
    window.addEventListener('modalMostrarLaboratorio', () => {
        $('#modalVerHistoriaClinica').modal('hide');
        $('#modalVerLaboratorio').modal('show');
    });

    function cerrarModalLaboratorio() {
        $('#modalVerLaboratorio').modal('hide');
        $('#modalVerLaboratorio').on('hidden.bs.modal', function() {
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