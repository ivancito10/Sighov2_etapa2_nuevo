<div>
    <div>
    <x-big-component id="permissionsModal" labelledby="permissionsModalLabel" title="EDITAR DATOS DEL TITULAR"
    saveAction="guardarEdicion()">
            <h3> id del usuario:{{$idUsuario}}</h3>
    
        @livewire('permisos-table')
    </x-big-component>



    </div>
</div>
