<x-ver-modal id="modalSelectCie10" labelledby="modalSelectCie10" title="LISTADO DE CIE 10" saveAction="">

    <div class="card-comun">
        @if ($mostrandoModalCie10)
            {{-- @livewire('atencion-medica.otras-consultas.soap-cie10-table', [], key('modal-cie10-' . now()->timestamp)) --}}
            @livewire('atencion-medica.consulta-externa.historia-clinica.cie10-select-table', [], key('modal-cie10-' . now()->timestamp))
        @endif
    </div>

</x-ver-modal>

<x-ver-modal id="modalCie10Soap" labelledby="modalCie10Soap" title="LISTADO DE CIE 10" saveAction="">
    <div class="card-comun">
        {{-- Solo renderiza la tabla si el modal está realmente activo --}}
        {{-- @if ($mostrandoModalCie10) --}}
        @livewire('atencion-medica.otras-consultas.soap-cie10-table' /* , [], key('modal-cie10-' . now()->timestamp) */)
        {{-- @endif --}}
    </div>
</x-ver-modal>

<script>
    window.addEventListener('modalSelectCie10', event => {
        $('#modalSelectCie10').modal('show');
    });
    document.addEventListener('cerrarSelectCie10', event => {
        $('#modalSelectCie10').modal('hide');
        // setTimeout(function() {
        //     $('#modalSelectCie10').modal('show');
        // }, 1000);
        Swal.fire({
            title: "¡Agregado!",
            text: "Diagnostico CIE-10 agregado.",
            icon: "success",
            timer: 1000,
            showConfirmButton: false,
            timerProgressBar: true
        });
    });
    document.addEventListener('DiagnosticoYaRegistrado', function() {
        $('#modalSelectCie10').modal('hide');
        Swal.fire({
            title: "Diagnostico ya registrado",
            text: "El diagnostico ya fue registrado!",
            icon: "warning"
        });
    });

    //Cie 10
    window.addEventListener('modalCie10Soap', event => {
        $('#modalCie10Soap').modal('show');
    });
    document.addEventListener('cerrarCie10Soap', event => {
        $('#modalCie10Soap').modal('hide');
        Swal.fire({
            title: "Agregado!",
            text: "El diagnostico fue agregado a exitosamente",
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
        });
    });
    document.addEventListener('DiagnosticoYaRegistradoSoap', function() {
        $('#modalCie10Soap').modal('hide');
        Swal.fire({
            title: "Diagnostico ya registrado",
            text: "El diagnostico ya fue registrado!",
            icon: "warning",
            showConfirmButton: false,
            timer: 1800,
        });
    });
</script>
