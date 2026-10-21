<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align:right;">
        <p style="color:#2471a3" class="h5">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path
                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
            </svg>
            <em class="ml-2"><b>Seleccione el turno:</b><span style="color:#cb4335;"><b>*</b></span> </em>

        </p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <label>TURNO:<span style="color:#cb4335;"><b>*</b></span></label>
        <select id="select-turno" wire:model="id_asig_horario" class="form-control" style="border-radius:8px;">
            <option value="" > Seleccione una opción </option>
            @foreach ($turnos as $turno)
                <option value="{{ $turno['id_asignacion_horario'] }}">
                    {{ $turno['hora_inicio'] }} - {{ $turno['hora_fin'] }} ({{ $turno['turno'] }})
                </option>
            @endforeach
        </select>
    </div>
    
</div>
<br>
<div class="row justify-content-center">
    <div class="col-5 text-center">
        <x-ver-pdf-button wire:click="generarPdfReporteAtencion" 
            title="GENERAR REPORTE ATENCION">
            GenerarPDF
        </x-ver-pdf-button>
    </div>
</div>



