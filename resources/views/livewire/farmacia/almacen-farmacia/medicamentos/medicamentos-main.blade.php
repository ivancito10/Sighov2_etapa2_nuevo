<div>

    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4> ALMACEN DE MEDICAMENTOS - <b> LISTA DE MEDICAMENTOS</b></h4>
        <div class="d-flex">
            <div class="mr-2">
                @can('ver_cuenta_contable.almacen_farmacia_medicamentos')
                    <x-archivo-button wire:click="ver_cuenta_contable">
                        <b>CUENTAS CONTABLES</b>
                    </x-archivo-button>
                @endcan
                @can('registrar.medicamentos')
                    <x-agregar-button wire:click="registrarMedicamento">REGISTRAR</x-agregar-button>
                @endcan
            </div>
            @can('registrar.medicamentos')
                @include('livewire.farmacia.almacen-farmacia.medicamentos.modalRegistrarMedicamento')
            @endcan
        </div>
    </div>


    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @can('lista.medicamentos')
                @livewire('Farmacia.Almacen-farmacia.Medicamentos.Medicamentos-table')
            @endcan
            @can('ver_cuenta_contable.almacen_farmacia_medicamentos')
                @include('livewire.farmacia.almacen-farmacia.medicamentos.modalVerCuentaContable')
            @endcan
            @can('ver.medicamentos')
                @include('livewire.farmacia.almacen-farmacia.medicamentos.modalVerMedicamento')
            @endcan
            @can('editar.medicamentos')
                @include('livewire.farmacia.almacen-farmacia.medicamentos.modalEditarMedicamento')
            @endcan
            @can('lista.medicamentos')
                @include('livewire.farmacia.almacen-farmacia.medicamentos.modalMaxMinMedicamento')
            @endcan


        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>

    </div>


    <script>
        document.addEventListener('habilitado', function() {
            Swal.fire({
                title: ' HABILITADO!',
                text: 'El registro fue habilitado correctamente.',
                icon: 'success'
            });
        });

        document.addEventListener('deshabilitado', function() {
            Swal.fire({
                title: ' DESHABILITADO!',
                text: 'El registro fue deshabilitado correctamente.',
                icon: 'success'
            });
        });

        window.addEventListener('deshabilitar_estado', event => {
            Swal.fire({
                title: '¿Deshabilitar estado?',
                text: "Se cambiará el estado del medicamento registrado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardar_cambiar_estado');
                }
            });
        });


        window.addEventListener('habilitar_estado', event => {
            Swal.fire({
                title: '¿Habilitar estado?',
                text: "Se cambiará el estado del medicamento registrado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, habilitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardar_cambiar_estado');
                }
            });
        });

        document.addEventListener('error_servidor', function() {
            Swal.fire({
                title: ' El servidor se cayó!',
                text: 'Las cuentas contables no pueden ser visualizadas.',
                icon: 'error'
            });
        });

        document.addEventListener('medicamentoYaExiste', function() {
            Swal.fire({
                title: 'El código del medicamento ya se encuentra registrado',
                text: 'Verifique los datos e intente nuevamente',
                icon: 'warning'
            });
        });

        window.addEventListener('deshabilitar_estadoFarmacia', event => {
            Swal.fire({
                title: '¿Deshabilitar medicamento?',
                text: "Se cambiará el estado del medicamento registrado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardar_estadoFarmacia');
                }
            });
        });

        window.addEventListener('habilitar_estadoFarmacia', event => {
            Swal.fire({
                title: '¿Habilitar medicamento?',
                text: "Se cambiará el estado del medicamento registrado.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, habilitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardar_estadoFarmacia');
                }
            });
        });
    </script>
</div>
