<x-big-component id="modalPdfPosicion" labelledby="modalPdfPosicionLabel" title="POSICION DE PDF" saveAction="">
    {{-- resources\views\livewire\Administracion\pruebas-formales-ejecucion-codigo\pdf-posicion.blade.php --}}
    {{-- hola --}}
    @livewire('Administracion.pruebas-formales-ejecucion-codigo.pdf-posicion')
</x-big-component>
<script>
    document.addEventListener('abrirModalPdfPosicion', function() {
        $('#modalPdfPosicion').modal('show');
    })
</script>
