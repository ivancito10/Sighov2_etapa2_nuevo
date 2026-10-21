<div>
    <x-small-ver-modal id="modalVerMateriales" labelledby="modalVerMaterialesLabel" title="VISTA DE MATERIAL">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
                <strong style="font-size:90%">ESTADO :</strong>
                @if ($estadoMostrar == true)
                    <span class="h4" style="color: green;"><b> <em> ACTIVO</em> </b></span>
                @else
                    <span class="h4 " style="color: red;"><b><em> INACTIVO</em></b></span>
                @endif
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                <label for="codigo_ver">CÓDIGO: </label>
                <input id="codigo_ver" class="form-control" type="text" wire:model='codigo' maxlength="6" disabled
                    style=" border-radius:8px">
            </div>
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                <label for="unidad_ver">UNIDAD: </label>
                <input id="unidad_ver" class="form-control" type="text" wire:model='unidad' maxlength="6" disabled
                    style=" border-radius:8px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="id_nro_cuenta_register">CUENTA CONTABLE: </label>
                <input id="nro_cuenta_ver" class="form-control" type="text" wire:model='nro_cuenta' maxlength="6"
                    disabled style=" border-radius:8px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="nombre_ver">NOMBRE GENÉRICO: </label>
                <input id="nombre_ver" class="form-control" type="text" wire:model='nombre' disabled
                    oninput="this.value = this.value.toUpperCase()" style=" border-radius:8px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="descripcion_ver">DESCRIPCIÓN: </label>
                <textarea id="descripcion_ver" class="form-control" type="text" wire:model='descripcion' row="3" disabled
                    oninput="this.value = this.value.toUpperCase()" style=" border-radius:8px"> </textarea>
            </div>
        </div>


    </x-small-ver-modal>
</div>
<script>
    document.addEventListener('abrirmodalVerMaterialess', function() {
        $('#modalVerMateriales').modal('show');
    });
</script>
