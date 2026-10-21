<div>
    <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
        <div class="card cabecera">
            <b> REPORTES DE AGENDAS </b>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <label for="tipo_asegurado">TIPO DE ASEGURADO <span style="color:#cb4335;"><b>*</b></span></label>
            <select wire:model.live="tipo_asegurado" name="tipo_asegurado" id="tipo_asegurado"
                class="form-control  @error('tipo_asegurado') is-invalid @enderror" style=" border-radius:10px;"
                tabindex="-1" required>
                <option selected="selected">Seleccione el tipo de asegurado..</option>
                <option value="TITULAR">TITULAR</option>
                <option value="BENEFICIARIO">BENEFICIARIO</option>
                <option value="CONVENIO">CONVENIO</option>
                <option value="ESTUDIANTES">ESTUDIANTES</option>
            </select>
            @error('tipo_asegurado')
                <div class="text-danger">
                    <b>{{ $message }}</b>
                </div>
            @enderror
        </div>
        @if ($mostrarListado == true)
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label for="id_dato">INSTITUCIÓN
                    <span style="color:#cb4335;"><b>*</b></span>
                </label>
                <select wire:model="id_dato" name="id_dato" id="id_dato"
                    class="form-control @error('id_dato') is-invalid @enderror" style="width: 100%; border-radius:10px;"
                    tabindex="-1" required>
                    <option selected="selected">Selecciona la nueva Institución </option>
                    @foreach ($instituciones as $dato)
                        <option value="{{ $dato->id }}">{{ $dato->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_dato')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
        @endif
        @if ($tipo_asegurado == 'CONVENIO')
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label for="id_dato">INSTITUCIÓN
                    <span style="color:#cb4335;"><b>*</b></span>
                </label>
                <select wire:model="id_dato" name="id_dato" id="id_dato"
                    class="form-control @error('id_dato') is-invalid @enderror" style="width: 100%; border-radius:10px;"
                    tabindex="-1" required>
                    <option selected="selected">Selecciona la nueva Institución </option>
                    @foreach ($segurosInterior as $dato)
                        <option value="{{ $dato->id }}">{{ $dato->nombre_seguro }}
                        </option>
                    @endforeach
                </select>
                @error('id_dato')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
        @endif

        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
            <label for="fecha_inicio">FECHA INICIO <span style="color:#cb4335;"><b>*</b></span></label>
            <input type="date" class="form-control input-comun @error('fecha_inicio') is-invalid @enderror"
                wire:model="fecha_inicio" name="fecha_inicio" id="fecha_inicio" min="1900-01-01"
                max="{{ now()->toDateString() }}">
            @error('fecha_inicio')
                <div class="text-danger">
                    <b>{{ $message }}</b>
                </div>
            @enderror
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
            <label for="fecha_fin">FECHA FIN <span style="color:#cb4335;"><b>*</b></span></label>
            <input type="date" class="form-control input-comun @error('fecha_fin') is-invalid @enderror"
                wire:model="fecha_fin" name="fecha_fin" id="fecha_fin" min="1900-01-01"
                max="{{ now()->toDateString() }}">
            @error('fecha_fin')
                <div class="text-danger">
                    <b>{{ $message }}</b>
                </div>
            @enderror
        </div>

    </div>
    <div style="height: 0.5rem;"></div>
    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-descargar-button onclick="preguntarDescarga()">DESCARGAR</x-descargar-button>
    </div>
</div>
<script>
    function preguntarDescarga() {
        Swal.fire({
            title: 'Desea generar el excel?',
            text: "El reporte será descargado en formato excel",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('descargarExcelAgendasLiquidacion');

            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Usted canceló la descarga.",
                    icon: "warning"
                });
                // $('#agregarInstitucionModal').modal('hide');
            }
        });
    }

    document.addEventListener('reporteGeneradoExito', function() {
        Swal.fire({
            title: "Excel descargado",
            text: "Se descargo correctamente",
            icon: "success",
            //showConfirmButton: false,
            //timer: 2000,
        });

        //$('#agregarInstitucionModal').modal('hide');
    });
</script>
