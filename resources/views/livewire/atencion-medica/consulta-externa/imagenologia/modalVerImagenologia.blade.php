<x-modal-sin-footer id="modalVerImagenologia" labelledby="modalVerImagenologia" title="VISTA IMAGENOLOGÍA">
    <div>
        @forelse ($imagenologias as $imagen)
            <div>
                @if ($imagen->imagenologiaEstudio->isNotEmpty())
                    <!-- <strong>Solicitudes de imagenologia:</strong> -->
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead style="background-color: #1f618d; color:white;">
                                <tr>
                                    <th style="width: 35%;">NOMBRE</th>
                                    <th style="width: 65%;">TIPO DE IMAGENOLOGÍA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($imagen->imagenologiaEstudio as $img)
                                    <tr>
                                        <td>{{ $img->nombre }}</td>
                                        <td>{{ $img->estudioTipo->nombre }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p><strong>INDICACIONES:</strong> {{ $imagen->indicaciones }}</p>
                @else
                    <p class="text-muted"><em> No hay solicitudes registrados. </em></p>
                @endif
            </div>
        @empty
            <p class="alert alert-danger text-center"><strong>SIN SOLICITUDES REGISTRADAS</strong></p>
        @endforelse
        <div class="modal-footer pt-0"
            style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
            <x-cancel-button onclick="cerrarModalImagenologia()">CERRAR</x-cancel-button>
        </div>
    </div>
</x-modal-sin-footer>

<script>
    window.addEventListener('modalMostrarImagenologia', () => {
        $('#modalVerHistoriaClinica').modal('hide');
        $('#modalVerImagenologia').modal('show');
    });

    function cerrarModalImagenologia() {
        $('#modalVerImagenologia').modal('hide');
        $('#modalVerImagenologia').on('hidden.bs.modal', function() {
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
