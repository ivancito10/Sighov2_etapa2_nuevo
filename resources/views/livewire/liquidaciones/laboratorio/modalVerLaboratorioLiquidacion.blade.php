<div>
    <x-ver-modal id="verLaboratorioLiquidacion" labelledby="verLaboratorioLiquidacionLabel"
        title="DETALLES DE LABORATORIO">
        <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
            <div class="card cabecera">
                <b> DETALLE DEL LABORATORIO</b>
            </div>
        </div>
        <div class="">

        </div>
        <table class="tabla-ploma" width=100%>
            <tr>
                <td colspan="3">
                    <b>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-person-square" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path
                                d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
                        </svg>
                        ASEGURADO:</b> {{ $asegurado }}
                </td>
                <td>
                    <b><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                            <path
                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                        </svg> MATRÍCULA:</b> {{ $matricula }}
                </td>
            </tr>
            <tr>
                <td>
                    <b><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg> ESPECIALISTA:</b> {{ $especialista }}
                </td>
                <td><b>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                            <path
                                d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                        </svg>
                        TIPO DE CONSULTA:</b> {{ $tipo_consulta }} </td>
                <td><b>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-calendar-check-fill" viewBox="0 0 16 16">
                            <path
                                d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                        </svg>
                        FECHA :</b> {{ Carbon\Carbon::parse($fecha_solicitud)->format('d/m/Y') }} </td>
                <td><b>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-clock-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                        </svg>
                        HORA :</b> {{ Carbon\Carbon::parse($fecha_solicitud)->format('H:i A') }} </td>
            </tr>
        </table>
        <div style="height: 0.5rem;"></div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
            <div class="card cabecera">
                <b> EXAMENES DE LABORATORIO</b>
            </div>
        </div>
        <div style="max-height: 300px; overflow-y: auto;">
            <table class="tabla-ploma" width=100%>
                <thead>
                    <tr>
                        <td>#</td>
                        <td>EXAMEN</td>
                        <td>TIPO </td>
                    </tr>
                </thead>
                @if (!empty($solicitudes_examen))
                    @foreach ($solicitudes_examen as $sol)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td> {{ $sol['nombre_examen'] }} </td>
                            <td> {{ $sol['tipo_lab'] }} </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No hay</td>
                    </tr>
                @endif

                <tbody>

                </tbody>
            </table>
        </div>

    </x-ver-modal>

</div>
<script>
    window.addEventListener('abrirLaboratorioLiquidacion', event => {
        $('#verLaboratorioLiquidacion').modal('show');
    });
</script>
