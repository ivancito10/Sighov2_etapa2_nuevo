<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">ENFERMERÍA - <b> SIGNOS VITALES</b></h4>
    </div>
    <br>

    <div wire:loading wire:target="registrarHistorialClinico">
        <x-cargando-dos />
    </div>

    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.agenda-enfermeria')
            @livewire('atencion-medica.enfermeria.signos-vitales.agenda-general-table')
        @endcan
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('livewire.atencion-medica.enfermeria.signos-vitales.modalRegisterSignosVitales')
        </div>
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>

    <div>

    </div>
</div>
