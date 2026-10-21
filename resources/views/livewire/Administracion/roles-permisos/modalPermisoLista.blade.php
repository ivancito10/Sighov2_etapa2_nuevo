<div>
    <x-ver-modal id="verPermisoLista" labelledby="verPermisoListaLabel" title="AGREGAR NUEVOS PERMISOS" saveAction="">
        <div class='row'>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                
                <label for="rol"  style='color:#2874A6;'> ROL </label>
                <input class="form-control" name="rol" id='rol' type= 'text' disabled wire:model='name'>
            <br>
            </div>

            <div class='col-lg-12'>
                @livewire('administracion.roles-permisos.permisos-table')
            </div>
        </div>
   
    </x-ver-modal>

</div>
<script>
    window.addEventListener('modalPermisoLista', event => {
        $('#verPermisoLista').modal('show');
    });
</script>