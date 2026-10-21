@props(['titulo', 'id' => 'fisio-modal'])
<div class="modal d-block" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-titulo"
    style="background: rgba(0, 0, 0, .45);" x-data x-init="$nextTick(() => $el.focus())"
    x-on:keydown.escape.stop="$wire.cerrar()"
    x-on:keydown.tab="const items = [...$el.querySelectorAll('button, input, select, textarea, [tabindex]')].filter(el => !el.disabled && el.tabIndex >= 0 && el.offsetParent !== null); if (items.length && $event.shiftKey && (document.activeElement === items[0] || document.activeElement === $el)) { $event.preventDefault(); items[items.length - 1].focus(); } else if (items.length && !$event.shiftKey && document.activeElement === items[items.length - 1]) { $event.preventDefault(); items[0].focus(); }">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border shadow" style="border-radius: 1rem;">
            <div class="modal-header" style="background: #eaeded; border-radius: 1rem 1rem 0 0;">
                <h2 class="h6 mb-0" id="{{ $id }}-titulo"><i class="fas fa-file-medical-alt text-info mr-2" aria-hidden="true"></i>{{ $titulo }}</h2>
                <button type="button" class="close" wire:click="cerrar" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
