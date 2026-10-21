<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">RAYOS X - <b> REGISTRAR</b></h4>
        @can('registrar.placas_imagenologia')
            <div class="d-flex">
                @livewire('rayos-x.rayos-x-modal-seleccionar')
            </div>
        @endcan
    </div>
    <br>
    @can('lista.placas_imagenologia')
        <div class="card-body pt-2 pr-0 pl-0">
            @livewire('rayos-x.rayos-x-table')
            @include('livewire.rayos-x.modalVerRayosX')
        </div>
    @endcan
</div>
<script>
    document.addEventListener('cambiarTipoRayosX', function() {
        let tipo = @this.get('tipo');
        Swal.fire({
            title: !tipo ? '¿Rayos X de archivo?' : '¿Rayos X de afuera?',
            text: !tipo ?
                "Rayos X será de archivo." : "Rayos X será de afuera.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: !tipo ? 'Sí, cambiar' : 'Sí, cambiar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarTipo', [true]);
                Swal.fire({
                    title: !tipo ? "Archivo!" : "Afuera!",
                    text: !tipo ?
                        "El tipo de Rayos X ha sido cambiado." :
                        "El tipo de Rayos X ha sido cambiado.",
                    icon: !tipo ? "success" : "success"
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios.",
                    icon: "warning"
                });
            }
        });
    });
</script>
