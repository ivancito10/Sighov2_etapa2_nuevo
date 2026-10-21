<div>
    <!--BOTON SUBIR PLANILLA FORMATO EXCEL -->
    <x-subir-archivo-button data-toggle="modal" data-target="#modalPersona"> VALIDAR PERSONAS EN EXCEL</x-subir-archivo-button>
        <!--  MODAL SUBIR ARCHIVO-->
        <div class="modal fade" id="modalPersona" tabindex="-1" aria-labelledby="planilla" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-xl" >
            <div class="modal-content">
                <div class="modal-header">
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >

                    <div class="card">
                        <div class="card-header" style="background-color:#154360 ">
                            <h7 style="font-family: Verdana, sans-serif ; text-align: left; color:white">SUBIR NUEVA PLANILLA PERSONAS ASUSS</h7>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                   
                                            
                                <div class="col-lg-12"  wire:ignore>
                                <br>
                                    <div class="row">
                                        <div class="col-lg-12" >
                                            <label for="formFile" class="form-label" >SELECCIONAR ARCHIVO: </label>
                                            <p class="h6"><em>Suba el archivo en formato Excel</em></p>
                                            @if ($errors->has('error_message'))
                                                <div class="alert alert-danger">
                                                    {{ $errors->first('error_message') }}
                                                </div>
                                            @endif
                                        <input  class="form-control" type="file" id="formFile" name="archivo" required accept=".xlsx" wire:model.ignore="planilla">
                                        </div>
                                        
                                        <div class="col-lg-4">
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-12" wire:loading >
                                    <x-cargando-dos/> 
                                </div>
                                    
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <x-cancel-button data-dismiss="modal">CANCELAR</x-cancel-button>
                    <x-save-button onclick="savePlanillaPersonas()"   wire:loading.attr="disabled">GUARDAR</x-save-button>   
                </div>
            </div>
        </div>
    </div>
    
     
</div>

<script>

     function savePlanillaPersonas() {
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
                    Livewire.dispatch('SubirPlanilla');
                }
            });
        }

        document.addEventListener('ErrorYaExistePlanilla', function() {
            $('#modalPlanilla').modal('hide');
            Swal.fire({
                title: "Verifique la selección de la planilla!",
                text: "Se cancelo el registro, Planilla duplicada",
                icon: "error"

            });
        });
        document.addEventListener('SubirPlanillaExitoso', function() {
            $('#modalPlanilla').modal('hide');
            Swal.fire({
                title: "<b>Felicidades</b>",
                text: "Se cargo la Planilla y se validó la informacion correctamente!!!",
                icon: "success"

            });
        });

        document.addEventListener('ErrorGeneral', function() {
            $('#modalPlanilla').modal('hide');
            Swal.fire({
                title: "Verifique el formato de su planilla, y comuniquese con el administrador!",
                text: "Se cancelo el registro",
                icon: "error"

            });
        });
        document.addEventListener('ErrorGeneralPlanillaDatos', function() {
            $('#modalPlanilla').modal('hide');
            Swal.fire({
                title: "Error en planilla!",
                text: "Se cancelo el registro, Error en el formato!!!",
                icon: "error"

            });
        });
        

</script>
