<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-0 m-0">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 m-0">
                {{ $slot }}
            </div>
            <div class="modal-footer p-0 m-0">
                {{ $footer }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", function () {
            Livewire.on('modalOpen', function () {
                $('#{{ $id }}').modal('show'); // Cambiado a $id en lugar de #myModal
            });

            Livewire.on('modalCerrado', function () {
                $('#{{ $id }}').modal('hide'); // Cambiado a $id en lugar de #myModal
            });
        });
    </script>
@endpush