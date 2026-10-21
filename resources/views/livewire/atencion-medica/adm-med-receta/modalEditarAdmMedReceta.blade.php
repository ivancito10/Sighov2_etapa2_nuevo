<x-big-component id="modalAdmMedicamentos" labelledby="modalAdmMedicamentosLabel"
    title="  ADMINISTRACIÓN POR RECETA" saveAction="editarAdmMedReceta()">
    <div class="card-borde p-2"> 
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                    <p style="color:#2471a3" class="h5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2"><b> Ingrese y complete el campo: </b></em>
                        <span style="color:#cb4335;"><b>*</b></span>
                    </p>
                </div>
            </div>

      
                <div class="row"> 
                    <div class="col-md-5">
                        <label for="tipoConsulta" class="form-label"> TIPO DE CONSULTA:</label>
                        <input type="text"
                            id="tipoConsulta"
                            class="form-control"
                            wire:model="nom_tipo_consulta"
                            style = "border-radius:8px;"
                            disabled>
                    </div>
                    
                    <div class="col-md-5">
                        <label for="numMedReceta" class="form-label">NRO. DE MEDICAMENTOS POR RECETA:<b style="color:#cb4335;"> * </b></label>
                        <input type="number"
                            id="numMedReceta"
                            class="form-control"
                            wire:model="num_med_receta"
                            style = "border-radius:8px;"
                            placeholder="Ingrese el nro."
                            min="0">
                    </div>
                </div>
            </div>

</x-big-component>


<script>
    document.addEventListener('abrirModalAdmMedicamentos', function() {
        $('#modalAdmMedicamentos').modal('show');
    })

    function editarAdmMedReceta() {
        Swal.fire({
            title: '¿Guardar cambios?',
            text: "Se modificarán los siguientes datos.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('editarAdmMedReceta');
            }
        });
    }
    
</script>