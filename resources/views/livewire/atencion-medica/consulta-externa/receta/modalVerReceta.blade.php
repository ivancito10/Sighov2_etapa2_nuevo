<x-modal-sin-footer id="modalVerReceta" labelledby="modalVerReceta" title="VISTA  RECETA">
    <div>
        @forelse ($recetas as $receta)
            <!-- <div>
                <strong style="color: #1E8449;"> <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                </svg><em> Solicitud registrada</em> </strong>
            </div> -->
            <div class="card mb-3">
                <div class="card-header" style="background-color: #CAD5E2; color:black; text-align:center; height:40px;">
                    <p style="font-size:21px;"><b> N° Receta: </b> {{ $receta->nro_receta ?? '' }} </p> 
                </div>
                <div class="card-body">
                    @if (isset($receta) && $receta->medicamentosReceta->isNotEmpty())
                    <div class="table-responsive">
 
                        <table class="table table-sm table-bordered">
                            <thead style="background-color: #1A5276; color:white;">
                                <tr>
                                    <th colspan="1"> <span><strong>Fecha de transcripción:</strong> {{ \Carbon\Carbon::parse( $receta->fecha_transcripcion )->format('d/m/Y')}}</span></th>
                                    <th colspan="6" style="text-align:center;"> MEDICAMENTOS</th>
                                </tr>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>CANTIDAD</th>
                                    <th>FORMA ADM.</th>
                                    <th>INDICACIONES</th>
                                    <th>RECOMENDACIÓN</th>
                                    <th>FECHA SOLICITUD</th>
                                    <th>FECHA FIN RESTRICCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receta->medicamentosReceta as $med)
                                    <tr>
                                        <td>{{ $med->nombre }}</td>
                                        <td>{{ $med->pivot->cantidad }}</td>
                                        <td>{{ $med->pivot->forma_adm }}</td>
                                        <td>{{ $med->pivot->indicaciones }}</td>
                                        <td>{{ $med->pivot->recomendacion }}</td>
                                        <td>{{\Carbon\Carbon::parse($med->pivot->fecha_ini)->format('d/m/Y') }}</td>
                                        <td>{{\Carbon\Carbon::parse($med->pivot->fecha_fin)->format('d/m/Y') }}</td>
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
            <x-cancel-button onclick="cerrarModalReceta()">CERRAR</x-cancel-button>
        </div>
    </div>
</x-modal-sin-footer>

<script>
    window.addEventListener('modalMostrarReceta', () => {
        $('#modalVerHistoriaClinica').modal('hide');
        $('#modalVerReceta').modal('show');
    });

    function cerrarModalReceta() {
        $('#modalVerReceta').modal('hide');
        $('#modalVerReceta').on('hidden.bs.modal', function() {
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
