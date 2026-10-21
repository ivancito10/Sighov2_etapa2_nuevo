<div>
    <div class="row">
        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <h4> ALMACEN GENERAL - <b> LISTADO DE MATERIALES</b></h5>
        </div>

        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">
            @can('registrar.materiales_almacen_general')
                <x-agregar-button wire:click="registrarMaterial">REGISTRAR</x-agregar-button>
            @endcan
        </div>

        @can('registrar.materiales_almacen_general')
            @include('livewire.almacen-general.materiales.modalRegistrarMaterial')
        @endcan
    </div>
    <br>
    <div class="row">
        @can('lista.materiales_almacen_general')
            <div class="col-lg-12">
                @livewire('almacen-general.materiales.materiales-table')
            </div>
        @endcan

        @can('ver.materiales_almacen_general')
            @include('livewire.almacen-general.materiales.modalVerMateriales')
        @endcan

        @can('editar.materiales_almacen_general')
            @include('livewire.almacen-general.materiales.modalEditarMateriales')
        @endcan
    </div>

    <div wire:loading>
        <x-cargando-dos></x-cargando-dos>
    </div>
</div>
<script>
    document.addEventListener('cambiandoEstadoMateriales', function() {
        let estado = @this.get('estado');
        Swal.fire({
            title: !estado ? '¿Habilitar material?' : '¿Deshabilitar material?',
            text: !estado ?
                "Este material será habilitado." : "Este material será deshabilitado.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: !estado ? 'Sí, habilitar' : 'Sí, deshabilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarEstadoMateriales', [true]);
                Swal.fire({
                    title: !estado ? "¡Habilitado!" : "¡Deshabilitado!",
                    text: !estado ?
                        "El material ha sido habilitado correctamente." :
                        "El material ha sido deshabilitado correctamente.",
                    icon: !estado ? "success" : "success"
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en el material.",
                    icon: "warning"
                });
            }
        });
    });
</script>
