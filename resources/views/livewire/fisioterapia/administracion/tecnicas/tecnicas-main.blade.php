<div>
    <div class="input-group pr-4" style="justify-content: space-between">
        <h4 style="color: black">FISIOTERAPIA - <b>REGISTRO DE TÉCNICAS</b></h4>
        @can('fisioterapia.tecnicas.gestionar')
            <x-agregar-button wire:click="crear" title="REGISTRAR TÉCNICA">REGISTRAR</x-agregar-button>
        @endcan
    </div>
    <div class="card-body pt-2 pr-0 pl-0">
        <div class="card-body">
            @livewire('fisioterapia.administracion.tecnicas.tecnicas-table')
            @include('livewire.fisioterapia.administracion.tecnicas.modalVerTecnica')
            @can('fisioterapia.tecnicas.gestionar')
                @include('livewire.fisioterapia.administracion.tecnicas.modalFormularioTecnica')
            @endcan
        </div>
    </div>
    @script
    <script>
        $wire.on('fisioterapia:abrir-formulario-tecnica', () => $('#formularioTecnica').modal('show'));
        $wire.on('fisioterapia:abrir-detalle-tecnica', () => $('#verTecnica').modal('show'));
        $wire.on('fisioterapia:confirmar-guardar-tecnica', async (event) => {
            const result = await Swal.fire({
                title: event.editando ? '¿Actualizar técnica?' : '¿Registrar técnica?',
                text: 'Se guardarán los datos ingresados.', icon: 'question',
                showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar', cancelButtonText: 'Cancelar'
            });
            if (result.isConfirmed) await $wire.guardar();
        });
        $wire.on('fisioterapia:confirmar-estado-tecnica', async (event) => {
            const { nombre, activar } = event;
            const result = await Swal.fire({
                title: activar ? '¿Reactivar técnica?' : '¿Dar de baja la técnica?',
                text: nombre + '. Se conservará el historial de sesiones.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: activar ? 'Sí, reactivar' : 'Sí, dar de baja', cancelButtonText: 'Cancelar'
            });
            if (result.isConfirmed) await $wire.cambiarEstado();
            else await $wire.cerrar();
        });
        $wire.on('fisioterapia:tecnica-guardada', (event) => {
            $('#formularioTecnica').modal('hide');
            Swal.fire({ title: '¡Guardado!', text: event.mensaje, icon: 'success' });
        });
        $wire.on('fisioterapia:error-tecnica', (event) => {
            Swal.fire({ title: 'No se pudo completar la operación', text: event.mensaje, icon: 'error' });
        });
    </script>
    @endscript
</div>