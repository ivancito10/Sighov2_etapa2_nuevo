<div>
    <x-modal-sin-footer id="registerVariableEsp" labelledby="registerVariableEspLabel" title="AGREGAR VARIABLES" >
        @livewire('atencion-medica.especialidad-variable.especialidad-variable-create') 
    </x-modal-sin-footer>
</div>


<script>
    window.addEventListener('modalRegistrarVariables', event => {
        $('#registerVariableEsp').modal('show');
    });

    function guardarEspVariables() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) { 
                Livewire.dispatch('guardarEspVariablesNuevo');
            }
        });
    }

     
      
 
</script>