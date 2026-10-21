@props(['items', 'id', 'label'])
<!--  NO FUNCIONAAAAAAAAAAAAAAAA WAAAAAAAAAAA  -->
<div x-data="{
    open: false,
    search: '',
    selected: @entangle($id),
    items: @json($items),

    get filtered() {
        return this.items.filter(item =>
            item.nombre.toLowerCase().includes(this.search.toLowerCase())
        );
    },
    // Método para seleccionar correctamente
    selectItem(item) {
        this.selected = item.id; // actualiza Alpine
        this.search = item.nombre; // texto visible
        this.open = false;
        // actualiza Livewire correctamente
        $wire.set('{{ $id }}', item.id);
    }
}" class="col-lg-4 col-md-8 col-sm-8 col-8" style="position: relative;">

    <label for="{{ $id }}">
        {{ $label }}: <span class="text-danger">*</span>
    </label>
    <!-- INPUT VISUAL -->
    <input x-model="search" @click="open = true" @input="open = true" type="text"
        class="form-control @error($id) is-invalid @enderror" placeholder="Buscar {{ strtolower($label) }}...">
    <!-- LISTA DESPLEGABLE -->
    <div x-show="open" @click.outside="open = false"
        class="list-group mt-1 border rounded shadow-sm position-absolute w-100 bg-white"
        style="max-height: 200px; overflow-y: auto; z-index: 9999;">
        <!-- SIN RESULTADOS -->
        <div class="list-group-item text-muted" x-show="filtered.length === 0">
            No hay coincidencias
        </div>
        <!-- OPCIONES -->
        <template x-for="item in filtered" :key="item.id">
            <button type="button" class="list-group-item list-group-item-action" @click="selectItem(item)"
                x-text="item.nombre"></button>
        </template>
    </div>

    <input type="hidden" wire:model="{{ $id }}">

    @error($id)
        <div class="text-danger"><b>{{ $message }}</b></div>
    @enderror
</div>
