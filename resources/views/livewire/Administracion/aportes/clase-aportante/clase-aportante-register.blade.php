<div>
    <!--     Para abrir el modal -->
    <x-agregar-button data-toggle="modal" data-target="#modalregistro">AGREGAR</x-agregar-button>

    <!--  //Diseño del modal -->
    <div class="modal fade" id="modalregistro" tabindex="-1" aria-labelledby="modalregistrolabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center text-primary" id="modalregistroLabel">REGISTRO DE CLASE APORTANTE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                        
                        <div class="form-group row">
                            <label for="outlined-basic" class="col-sm-3 col-form-label">CLASE</label>
                            <div class="col-sm-9">
                                <select wire:model="nombre" class="select2 form-control">
                                    <option >Seleccione una clase</option>
                                    <option value="PATRONAL">PATRONAL</option>
                                    <option value="RENTISTA ">RENTISTA</option>
                                    <option value="TRASPASO">TRASPASO</option>
                                    <option value="VOLUNTARIO">VOLUNTARIO</option>
                                </select>
                            </div>
                        </div>
        
                        <div class="form-group row">
                            <label for="outlined-basic" class="col-sm-3 col-form-label">TIPO</label>
                            <div class="col-sm-9">
                                <select wire:model="tipo" class="select2 form-control">
                                    <option value="" disabled selected>Seleccione un tipo</option>
                                    <option value="PATRONAL">PATRONAL</option>
                                    <option value="RENTISTA JUBILADOS">RENTISTA JUBILADO</option>
                                    <option value="RENTISTA POR SORTEO">RENTISTA POR SORTEO</option>
                                    <option value="TRASPASO">TRASPASO</option>
                                    <option value="VOLUNTARIO">VOLUNTARIO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="outlined-basic" class="col-sm-3 col-form-label">PORCENTAJE</label>
                            <div class="col-sm-9">
                                <input wire:model="porcentaje" type="number" step="0.00" class="form-control" id="outlined-basic"
                                    placeholder="Porcentaje">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-cancel-button data-dismiss="modal">
                        CANCELAR</x-cancel-button>
                    <x-save-button onclick="confirmSaveRegisterCA()"> GUARDAR</x-save-button>

                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('livewire:load',function(){
            $('.select2').select2();
        })

        // Función para guardar los datos
        function saveDataRegisterCA() {
            // Llama al método save() del componente Livewire
            Livewire.dispatch('crearClaseAportante');
            // Cierra el modal con el id "exampleModal"
            $('#modalregistro').modal('hide');

        }

        // Función para mostrar la alerta de confirmación
        function confirmSaveRegisterCA() {
            Swal.fire({
                title: '¿Estás seguro de guardar?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, llama a la función saveData()
                    saveDataRegisterCA();

                }
            });


        }
        //fucniona de exito 
        function showSuccessAlertRegisterCA() {
            Swal.fire({
                title: "Se registró correctamente!",
                text: "Los datos se han guardado exitosamente.",
                icon: "success"

            });
        }

        function showCancelAlertRegisterCA() {
            Swal.fire({
                title: "Se cancelo el registro!",
                text: "Completa todos los datos necesarios",
                icon: "error"

            });
        }
        document.addEventListener('CreadaRegisterCA', function() {
            // Cierra el modal con el id "exampleModal"
            showSuccessAlertRegisterCA();
        });
        document.addEventListener('CanceladoRegisterCA', function() {
            // Cierra el modal con el id "exampleModal"
            showCancelAlertRegisterCA();
        });
    </script>

</div>
