<div wire:poll.10s>
    <i class="fas fa-fw fa-bell"></i>
    @if($count > 0)
        <span class="badge badge-{{ $count > 5 ? 'success' : 'warning' }}">{{ $count }}</span>
    @endif
</div>