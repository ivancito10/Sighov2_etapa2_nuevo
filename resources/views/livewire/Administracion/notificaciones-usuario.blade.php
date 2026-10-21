<div>

    <button wire:click="abrir_modal_notificacion" type="button" type="button" class="btn btn-outline-danger"
        data-toggle="dropdown" aria-expanded="false" wire:poll="numero_notificaciones">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill"
            viewBox="0 0 16 16">
            <path
                d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901">
            </path>
            @if($this->numero!=0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"></span>
            <span class="badge badge-light" >{{ $this->numero }}</span>
            @else
            <span class="badge badge-light">0</span>
            @endif
        </svg>
    </button>

</div>

<script>
    window.addEventListener('ModalNotificaciones', event => {

        $('#ModalNotificaciones').modal('show');
    });
</script>