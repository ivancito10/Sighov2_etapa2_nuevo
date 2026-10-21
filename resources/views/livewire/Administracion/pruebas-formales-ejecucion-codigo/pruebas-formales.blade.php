<div>
    <h1>PAGINITA PARA EJECUTAR CÓDIGO</h1>
    <p class="mb-0 pb-0">Esta páginita solo la pueden ver los super admins, los más perrones aquí. La idea es que podamos
        poner acá el codigo que queramos ejecutar en producción
        sin tener que estar modificando otros archivos y siendo precavidos al respecto.
        <br>
    </p>
    <p class="mt-0 pt-0 text-right">
        <i>- RisBou</i>
    </p>
    {{-- <x-ver-button wire:click="ejecutarActualizacionStockReal" disabled>
        EJECUTAR ACTUALIZACIÓN STOCK REAL
    </x-ver-button> --}}

    {{-- <x-ver-button wire:click="ejecutarAjusteMovimientoPostInventario" disabled>
        EJECUTAR AJUSTE MOVIMIENTO POST INVENTARIO
    </x-ver-button> --}}

    {{-- <x-ver-button wire:click="ejecutarAjusteMovimientoRecetasPorInventario">
        EJECUTAR AJUSTE MOVIMIENTO <i><b>EN RECETAS</b></i> POST INVENTARIO
    </x-ver-button>

    <x-ver-button wire:click="ejecutarAjusteMovimientoRecetasPorInventarioEntregados">
        EJECUTAR AJUSTE MOVIMIENTO ENTREGAS <i><b>EN RECETAS</b></i> POST INVENTARIO
    </x-ver-button> --}}

    <x-ver-button wire:click="verIdInstitucion">
        ASÍ SE VE EL ID_INSTITUCION EN LA ETAPA 2
    </x-ver-button>
    <x-ver-button wire:click="testJobs">
        PRUEBA DE TAREAS EN SEGUNDO PLANO CON REDIS
    </x-ver-button>
    <x-ver-button wire:click="modalPdfPosicion">
        Modal de seccion de pdf de posicion
    </x-ver-button>
    @include('livewire.Administracion.pruebas-formales-ejecucion-codigo.modalPdfPosision')
    {{-- @livewire('administracion.pruebas-formales-ejecucion-codigo.pdf-posicion') --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
