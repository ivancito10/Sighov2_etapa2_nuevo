<x-modal-mediano-no-close id="modalRegistroSubjetivo" labelledby="modalRegistroSubjetivo" title="REGISTRO SUBJETIVO">
    <div class="content">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="motivoConsulta">MOTIVO CONSULTA:<span style="color:#cb4335;"><b>*</b></span></label>
                <textarea class="form-control" id="motivoConsulta" wire:model="motivo_consulta"
                    placeholder ="Introduzca el motivo de la consulta" rows="2"></textarea>
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="enfermedadActual">ENFERMEDAD ACTUAL: </label>
                <textarea class="form-control" id="enfermedadActual" wire:model="enfermedad_actual"
                    placeholder ="Introduzca la enfermedad actual" rows="2"></textarea>
            </div>

        </div>




        <div class="modal-footer">

            <x-atras-button class="p-1" wire:click="atrasSubjetivo">ATRÁS</x-atras-button>
            <button type="button" class="btn btn-primary" wire:click="registrarSubjetivo">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-check-lg" viewBox="0 0 16 16">
                    <path
                        d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                </svg> CONTINUAR
            </button>
        </div>

    </div>
</x-modal-mediano-no-close>


<script>
    window.addEventListener('modalRegistroSubjetivo', event => {
        $('#modalRegistroSubjetivo').modal('show');
    });
    window.addEventListener('cerrarModalSubjetivo', event => {
        $('#modalRegistroSubjetivo').modal('hide');
    });

    window.addEventListener('mostrarAlertaCamposVacios', () => {
        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, completa todos los campos antes de continuar.',
            confirmButtonText: 'OK'
        });
    });
</script>
