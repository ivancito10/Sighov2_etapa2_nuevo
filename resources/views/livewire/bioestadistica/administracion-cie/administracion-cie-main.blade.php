<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4 style="color:black;">BIOESTADISTICA - <b> LISTA CIE 10</b></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end w-100">

                {{-- @can('ficha_extra.consulta_externa') --}}
                {{-- @if ($fecha_hoy) --}}
                <div class="ml-2">
                    <x-agregar-button wire:click="modalCieRegister">
                        <b>REGISTRAR</b>
                    </x-agregar-button>
                </div>
                {{-- @endif --}}
                {{-- @endcan --}}
            </div>
        </div>
    </div>
    <br>
    {{-- The whole world belongs to you. --}}
    {{-- <livewire:bioestadistica.administracion-cie10-table /> --}}
    {{-- app/Livewire/Bioestadistica/AdministracionCie/AdministracionCieTable.php --}}
    @livewire('Bioestadistica.AdministracionCie.AdministracionCieTable')
    {{-- <livewire:bioestadistica.administracioncie.administracion-cie-table /> --}}
    {{-- resources/views/livewire/bioestadistica/administracion-cie/modalCieRegister.blade.php --}}
    @include('livewire.bioestadistica.administracion-cie.modalCieRegister')
    @include('livewire.bioestadistica.administracion-cie.modalCieEdit')
</div>
<script>
    document.addEventListener('confirmarEstadoDato', function() {
        Swal.fire({
            title: '¿Modificar el estado del CIE?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deshabilitarCie');
            }
        });
    });
    document.addEventListener('Ciehabilitado', function() {
        Swal.fire({
            title: '¡CIE habilitada!',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
        });
    });
    document.addEventListener('CieDeshabilitado', function() {
        Swal.fire({
            title: '¡CIE deshabilitada!',
            icon: 'warning',
            showConfirmButton: false,
            timer: 1500
        });
    });
</script>