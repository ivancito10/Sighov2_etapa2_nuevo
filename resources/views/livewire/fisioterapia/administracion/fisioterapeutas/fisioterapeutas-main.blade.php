<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="input-group pr-4" style="justify-content: space-between">
        <h4 style="color: black">FISIOTERAPIA - <b>ADMINISTRACION FISIOTERAPEUTAS</b></h4>
        @can('admin.registro_usuario')
            <!--BOTON PARA AGREGAR NUEVO USUARIO-->
            <x-agregar-button wire:click="redirectToForm" title="REGISTRAR FISIOTERAPEUTA">
                REGISTRAR NUEVO FISIOTERAPEUTA</x-agregar-button>
        @endcan
    </div>

    <div class="card-body pt-2 pr-0 pl-0">
        <div class="card-body">
            @can('admin.lista_usuarios')
                @livewire('Administracion.usuarios.usuarios-table')
            @endcan
        </div>
    </div>
</div>
