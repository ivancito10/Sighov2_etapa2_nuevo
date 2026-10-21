<div>
    <div class="input-group pr-4" style="justify-content: space-between">
        <h4 style="color: black">FISIOTERAPIA - <b>REGISTRO DE POLÍTICAS AGENDA</b></h4>
        @can('fisioterapia.politicasagenda.gestionar')
            <x-agregar-button wire:click="crear" title="REGISTRAR POLÍTICA">REGISTRAR</x-agregar-button>
        @endcan
    </div>
    <div class="card-body pt-2 pr-0 pl-0">
        <div class="card-body">
            @livewire('fisioterapia.administracion.politicas-agenda.politicas-agenda-table')
            @include('livewire.fisioterapia.administracion.politicas-agenda.modalVerPoliticaAgenda')
            @can('fisioterapia.politicasagenda.gestionar')
                @include('livewire.fisioterapia.administracion.politicas-agenda.modalFormularioPoliticaAgenda')
            @endcan
        </div>
    </div>
    @script
    <script>
        $wire.on('fisioterapia:abrir-formulario-politicaagenda', () => $('#formularioPoliticaAgenda').modal('show'));
        $wire.on('fisioterapia:abrir-detalle-politicaagenda', () => $('#verPoliticaAgenda').modal('show'));
        $wire.on('fisioterapia:confirmar-guardar-politicaagenda', async (event) => {
            const result = await Swal.fire({
                title: event.editando ? '¿Actualizar política agenda?' : '¿Registrar política agenda?',
                text: 'Se guardarán los datos ingresados.', icon: 'question',
                showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar', cancelButtonText: 'Cancelar'
            });
            if (result.isConfirmed) await $wire.guardar();
        });
        $wire.on('fisioterapia:confirmar-estado-politicaagenda', async (event) => {
            const { nombre, activar } = event;
            const result = await Swal.fire({
                title: activar ? '¿Reactivar política agenda?' : '¿Dar de baja la política agenda?',
                text: nombre + '. Se conservará el historial de sesiones.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                confirmButtonText: activar ? 'Sí, reactivar' : 'Sí, dar de baja', cancelButtonText: 'Cancelar'
            });
            if (result.isConfirmed) await $wire.cambiarEstado();
            else await $wire.cerrar();
        });
        $wire.on('fisioterapia:politicaagenda-guardada', (event) => {
            $('#formularioPoliticaAgenda').modal('hide');
            Swal.fire({ title: '¡Guardado!', text: event.mensaje, icon: 'success' });
        });
        $wire.on('fisioterapia:error-politicaagenda', (event) => {
            Swal.fire({ title: 'No se pudo completar la operación', text: event.mensaje, icon: 'error' });
        });
    </script>
    @endscript
</div>
