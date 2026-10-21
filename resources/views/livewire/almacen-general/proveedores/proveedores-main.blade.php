<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">ALMACEN GENERAL - <b> LISTA DE PROVEEDORES</b></h4>
        <div class="d-flex">
            @can('registrar.proveedores_almacen_general')
                <div class="mr-2">
                    <x-agregar-button wire:click="abrirModalRegistroProveedor">REGISTRAR</x-agregar-button>
                    @include('livewire.almacen-general.proveedores.modalRegisterProveedor')
                </div>
            @endcan

        </div>
    </div>
    <br>
    @can('lista.proveedores_almacen_general')
        @livewire('AlmacenGeneral.Proveedores.ProveedoresTable')
    @endcan
    @can('ver.proveedores_almacen_general')
        <!-- Modal para Ver datos del proveedor -->
        @include('livewire.almacen-general.proveedores.modalVerProveedor')
    @endcan
    @can('editar.proveedores_almacen_general')
        <!-- Modal para Editar datos del proveedor -->
        @include('livewire.almacen-general.proveedores.modalEditarProveedor')
    @endcan

    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>

<script>
    document.addEventListener('HabilitarEstadoProveedor', function() {
        Swal.fire({
            title: '¿Habilitar Proveedor?',
            text: "Se habilitará el proveedor seleccionado",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, habilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('cambiarEstado');
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Se canceló la operación",
                    icon: "error"
                });
            }
        });
    });

    document.addEventListener('InhabilitarEstadoProveedor', function() {
        console.log('cambiarEstadoProveedor');
        Swal.fire({
            title: '¿Deshabilitar Proveedor?',
            text: "Se deshabilitará el proveedor seleccionado",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, deshabilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                Livewire.dispatch('cambiarEstado');

            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Se canceló la validación",
                    icon: "error"
                });
            }
        });
    });
    document.addEventListener('proveedorHabilitado', function() {
        Swal.fire({
            title: 'Habilitado!',
            text: "Se habilitó el proveedor con éxito.",
            icon: 'success'
        });
    });


    document.addEventListener('proveedorDeshabilitado', function() {
        Swal.fire({
            title: 'Deshabilitado!',
            text: "Se deshabilitó el proveedor con éxito.",
            icon: 'success'
        });
    });
</script>
