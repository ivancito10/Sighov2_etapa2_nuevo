<x-modal-sin-footer id="modalVerBajaMedica" labelledby="modalVerBajaMedica" title="VISTA BAJA MÉDICA">
    <div>
        @forelse ($bajas_medicas as $baja_medica)
            <div class="card mb-3">
                <!--
                 <div class="card-header" style="background-color: #1A5276; color:white;">
                    <p style="font-size:21px;">  N° Baja Médica: {{ $baja_medica->nro_baja_medica }}</p>
                </div>
                -->
                <div class="card-body">



                    <div class="table-responsive">

                        <table class="table table-sm table-bordered">
                            <thead style="background-color: #1A5276; color:white;">

                                <tr>
                                    <th style="width: 20%;">FECHA INICIO</th>
                                    <th style="width: 20%;">FECHA FIN</th>
                                    <th style="width: 10%;">DÍAS DE BAJA</th>
                                    <th style="width: 50%;">MOTIVO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($baja_medica->fecha_ini_baja)) }}</td>
                                    <td>{{ date('d/m/Y', strtotime($baja_medica->fecha_fin_baja)) }}</td>
                                    <td>{{ $baja_medica->dias_baja }}</td>
                                    <td>{{ $baja_medica->descripcion }}</td>
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
            <x-cancel-button onclick="cerrarModalBajaMedica()">CERRAR</x-cancel-button>
        </div>
    </div>
</x-modal-sin-footer>

<script>
    window.addEventListener('modalMostrarBajaMedica', () => {
        $('#modalVerHistoriaClinica').modal('hide');
        $('#modalVerBajaMedica').modal('show');
    });

    function cerrarModalBajaMedica() {
        $('#modalVerBajaMedica').modal('hide');
        $('#modalVerBajaMedica').on('hidden.bs.modal', function() {
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
