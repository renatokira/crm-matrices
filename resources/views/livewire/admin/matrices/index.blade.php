<div>
    <!-- HEADER -->
    <x-header title="Matrices" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <div class="flex items-center justify-between m-4">
        <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        <x-button label="Create Matrix" @click="$dispatch('matrix::create')" icon="o-plus" class="btn-primary" />
    </div>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$this->headers" :rows="$this->items" :sort-by="$sortBy">

            @scope('cell_bandwidth', $mtx)
                {{ $mtx['bandwidth'] . '' . $mtx['bandwidth_unit'] }}
            @endscope

            @scope('actions', $mtx)
                <x-button id="update-btn-{{ $mtx->id }}" wire:key="update-btn-{{ $mtx->id }}" icon="o-pencil"
                    @click="$dispatch('matrix::update', { id: {{ $mtx->id }}})" spinner class="btn-sm" />
                <x-button icon="o-eye" wire:key="show-btn-{{ $mtx->id }}" id="show-btn-{{ $mtx->id }}"
                    wire:click="showMatrix('{{ $mtx->id }}')" spinner class="btn-ghost btn-sm" />
                <x-button icon="o-trash" wire:click="delete({{ $mtx['id'] }})" spinner class="btn-ghost btn-sm" />
            @endscope
        </x-table>

        <div class="mt-7">
            {{ $this->items->links(data: ['scrollTo' => false]) }}
        </div>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass"
            @keydown.enter="$wire.drawer = false" />

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>

    <livewire:admin.matrices.show />
    <livewire:admin.matrices.create />
    <livewire:admin.matrices.update />
</div>
