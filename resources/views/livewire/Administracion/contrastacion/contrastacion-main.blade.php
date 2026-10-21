<div>
    @if ($tipo_formulario == 1)
        @include('livewire.Administracion.contrastacion.inicioContrastacion')
    @else
        @include('livewire.Administracion.contrastacion.formContrastacion')
    @endif
    <div wire:loading wire:target="verificarDatosIntroducidos">
        <x-cargando-dos />
    </div>
    <div wire:loading wire:target="atras">
        <x-cargando-dos />
    </div>
    <div wire:loading wire:target="formularioActualizacion">
        <x-cargando-dos />
    </div>
    <div wire:loading wire:target="verDatos">
        <x-cargando-dos />
    </div>
    <div wire:loading wire:target="actualizarSoloDatosReferenciales">
        <x-cargando-dos />
    </div>
</div>
<script>
    document.addEventListener('datoPersonalErroneo', function(event) {
        const campo = event.detail.campo;
        Swal.fire({
            title: `VERIFIQUE EL CAMPO <b>${campo}</b> `,
            text: "El dato introducido no concuerda con la base de datos del SEGIP",
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false

        });
    });

    document.addEventListener('datoPersonalVacio', function(event) {
        const campo = event.detail.campo;
        Swal.fire({
            title: `VERIFIQUE EL CAMPO <b>${campo}</b> `,
            text: "No se introdujo ningún dato. Verifique e intente nuevamente",
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('registroModificado', function() {
        Swal.fire({
            title: "<b>DATOS PERSONALES ACTUALIZADOS CORRECTAMENTE</b>",
            text: "Los datos se actualizaron y contrastaron correctamente",
            icon: "success",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('registroDatosReferencialesModificado', function() {
        Swal.fire({
            title: "<b>DATOS REFERNCIALES ACTUALIZADOS CORRECTAMENTE</b>",
            text: "Los datos se actualizaron correctamente",
            icon: "success",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });
</script>
