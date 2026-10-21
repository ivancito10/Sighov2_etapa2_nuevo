<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4> FARMACIA - <b> LISTA DE RECETAS MÉDICAS</b></h4>
        <div class="d-flex">
            <div class="mr-2">
                @can('habilitar.recetas_medicas')
                    <x-reportes-button class="btn btn-primary" wire:click="funcionModalLista" title="HABILITAR">
                        HABILITAR MEDICAMENTOS
                    </x-reportes-button>
                @endcan
                @can('registrar.recetas_medicas')
                    <x-agregar-button wire:click="abrirRegistroReceta">REGISTRO MANUAL</x-agregar-button>
                @endcan
            </div>
            @can('registrar.recetas_medicas')
                @include('livewire.farmacia.farmacia.receta.modalregistrarRecetaManual')
            @endcan
            @can('habilitar.recetas_medicas')
                @include('livewire.farmacia.farmacia.receta.modalHabilitarPersonas')
            @endcan
            @can('ver.recetas_medicas')
                @include('livewire.farmacia.farmacia.receta.modalVerReceta')
            @endcan

        </div>
    </div>




    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @livewire('farmacia.farmacia.receta.recetas-table')
        </div>
    </div>





</div>

<script>
    document.addEventListener('preguntaEntregarReceta', function() {
        Swal.fire({
            title: '¿Esta seguro de <b>dispensar la receta</b>?',
            text: "La receta será dispensada",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#008f39',
            cancelButtonColor: '#ff0000',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('recetaEntregada');
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Se canceló la entrega",
                    icon: "warning",
                    confirmButtonColor: '#008f39',
                });
            }
        });
    });

    document.addEventListener('recetaEntregadaSatisfactoriamente', function() {
        Swal.fire({
            title: "¡Receta dispensada satisfactoriamente!",
            text: "La entrega de la receta se registró correctamente",
            icon: "success",
            confirmButtonColor: '#008f39',
        });
    });

    document.addEventListener('recetaNoEntregada', function() {
        Swal.fire({
            title: "Error inesperado",
            text: "Ocurrió un error durante el registro, verifique e intentelo nuevamente",
            icon: "error",
            showConfirmButton: false
        });
    });

    document.addEventListener('preguntarAnularReceta', event => {
        Swal.fire({
            title: '<b>¿Desea anular la receta?</b>',
            text: "Ingrese el motivo de la anulación:",
            input: "text",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#008f39',
            cancelButtonColor: '#ff0000',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'No, cancelar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Debe introducir el motivo.`);
                    return false;
                }
                return motivo_texto;
            },
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('recetaAnulada', {
                    motivo: result.value
                });
            }
        });
    });

    document.addEventListener('recetaAnuladaSatisfactoriamente', function() {
        Swal.fire({
            title: "¡Receta anulada correctamente!",
            text: "La receta fue anulada",
            icon: "success",
            confirmButtonColor: '#008f39',
        });
    });

    document.addEventListener('recetaNoAnulada', function() {
        Swal.fire({
            title: "Error inesperado",
            text: "Ocurrió un error durante el registro, verifique e intentelo nuevamente",
            icon: "error",
            showConfirmButton: false
        });
    });

    document.addEventListener('sinStockSuficiente', function(event) {
        const medicamentos = event.detail.arraycitoMedicamento || [];
        const sinStock = medicamentos.filter(med => med.existe_stock_suficiente === false);
        let htmlLista =
            '<p class="text-start">Los siguientes medicamentos no cuentan con stock suficiente para realizar la entrega:</p><ul class="text-start" style="text-align: left;">';

        sinStock.forEach(med => {
            htmlLista +=
                `<li><strong>${med.codigo_medicamento}</strong> - ${med.nombre_medicamento}</li>`;
        });

        htmlLista += '</ul>';
        Swal.fire({
            icon: 'warning',
            title: 'Stock Insuficiente',
            html: htmlLista,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#FF0000',
        });
    });

    window.addEventListener('abrirPopupReceta', event => {
        const id = event.detail.id;
        const url = `/pdf-receta-preview/${id}`;
        // Cerrar el modal ANTES de abrir el popup
        $('#modalRecList').modal('hide');
        // Abrir popup
        window.open(
            url,
            '_blank',
            'width=900,height=700,scrollbars=yes,resizable=yes'
        );

        // Evitar que la pantalla quede oscura
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
</script>
