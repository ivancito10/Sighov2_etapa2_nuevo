<div>
    <x-agregar-button data-toggle="modal" data-target="#modalRayosXAsegurados"> <b>REGISTRAR</b></x-agregar-button>
    <!--MODAL PARA VER EL LISTADO DE APORTANTES-->

    <x-ver-modal-sin-titulo id="modalRayosXAsegurados" labelledby="modalRayosXAseguradosLabel"
        title=" LISTADO DE ASEGURADOS ">
        <div class="input-group pr-4" style="justify-content: space-between; ">
            <h4 style="color:black;">RAYOS X - <b> LISTADO DE ASEGURADOS</b></h4>
        </div>
        <br>
        @livewire('rayos-x.listado-asegurados')
    </x-ver-modal-sin-titulo>
</div>
