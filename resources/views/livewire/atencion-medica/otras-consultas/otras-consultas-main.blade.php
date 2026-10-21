<div>
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <h4 style="color:black;">ATENCIÓN MÉDICA - <b> LISTA DE OTRAS CONSULTAS </b></h4>
        </div>
        @can('nueva_atencion.otras_consultas')
            <div class="col-lg-4 col-md-12 col-sm-12" style="text-align:right;">
                <x-agregar-button wire:click="registrarConsulta"><b>REGISTRAR NUEVA CONSULTA</b> </x-agregar-button>
            </div>
        @endcan
        <div wire:loading wire:target="registrarConsulta">
            <x-cargando-dos />
        </div>
    </div>
    <br>
    @can('lista.otras_consultas')
        <div>
            @livewire('atencion-medica.otras-consultas.otras-consultas-table')
        </div>
    @endcan

    @include('livewire.atencion-medica.otras-consultas.modalSelectPacienteConsulta')
    @can('ver.otras_consultas')
        @include('livewire.atencion-medica.consulta-externa.historia-clinica.modalVerHistoriaClinica')


        @include('livewire.atencion-medica.consulta-externa.baja-medica.modalVerBajaMedica')
        @include('livewire.atencion-medica.consulta-externa.fisioterapia.modalVerFisioterapia')
        @include('livewire.atencion-medica.consulta-externa.imagenologia.modalVerImagenologia')
        @include('livewire.atencion-medica.consulta-externa.internacion.modalVerInternacion')
        @include('livewire.atencion-medica.consulta-externa.laboratorio.modalVerLaboratorio')
        @include('livewire.atencion-medica.consulta-externa.receta.modalVerReceta')
        @include('livewire.atencion-medica.consulta-externa.referencia.modalVerReferencia')
    @endcan

</div>
