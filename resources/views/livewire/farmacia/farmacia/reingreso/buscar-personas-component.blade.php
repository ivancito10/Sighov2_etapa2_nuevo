<div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <x-buscar-dos-button style="padding:0px" data-toggle="modal" data-target="#personasAseguradas">BUSCAR
            ASEGURADO</x-buscar-dos-button>
    </div>
    <x-ver-modal id="personasAseguradas" labelledby="personasAseguradas" title="LISTADO DE ASEGURADOS">
        @livewire('Farmacia.Farmacia.Reingreso.PersonasTable')
    </x-ver-modal>
</div>
<script>
    window.addEventListener('cerrarModalPersonas', event => {
        Swal.fire({
            title: "Asegurado seleccionado",
            text: "Se seleccionó al asegurado correctamente.",
            icon: "success"
        });
        $('#personasAseguradas').modal('hide');
    });
</script>
