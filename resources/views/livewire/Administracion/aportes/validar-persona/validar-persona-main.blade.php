<div>
    <!--CARD MENU DE ACCIONES  PRINCIPAL-->
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4">
                        @livewire('administracion.aportes.validar-persona.validar-persona-register')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--CARD LISTA PRINCIPAL-->
    <div class="card">
        <div class="card-header" style="background-color:#154360">
            <h6 style="font-family: Verdana, sans-serif ; text-align: left; color:white">VALIDAR PERSONAS ASSUS</h6>
        </div>
        <div class="card-body">
            @livewire('administracion.aportes.validar-persona.validar-persona-table')
        </div>
    </div> 
</div>
