<x-small-ver-modal id="asignarPermisos" labelledby="asignarPermisosLabel"
    title=" VER PERMISOS ASIGNADOS ">
<!-- AHORA ESTE ES EL MODAL PARA VER LOS PERMISOS ASIGNADOS A UN ROL -->
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <input type="text" class="form-control" aria-label="Username"
                    aria-describedby="basic-addon1" wire:model="name"
                    style="background-color:#f2f3f4">
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <p style="background-color:#dc7633 ; color:white; text-align:center;">
                        <b> PERMISOS ASIGNADOS</b> </p>
                        <div class="scroll-container-especialista" style="max-height: 350px; overflow-y: auto;">
                            @foreach($this->lista_permisos as $permiso)
                                <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16" style="margin-right: 8px;color:green">
                                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                                    </svg>
                                    {{ $permiso->name }}
                                </div>
                            @endforeach
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-small-ver-modal>

<script>
    window.addEventListener('asignarPermisosModal', event => {

        $('#asignarPermisos').modal('show');
    });
</script>