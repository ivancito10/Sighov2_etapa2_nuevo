<div>
    <x-modal-sin-footer id="registrarVariable" labelledby="registrarVariableLabel" title="AGREGAR  VARIABLE">
        @livewire('atencion-medica.variables.variable-create')
    </x-modal-sin-footer>
</div>


<script>
    window.addEventListener('modalAgregar', event => {
        $('#registrarVariable').modal('show');
    });
 
</script>