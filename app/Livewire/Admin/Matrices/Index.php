<?php

namespace App\Livewire\Admin\Matrices;

use App\Enum\CanEnum;
use App\Models\Matrix;
use App\Traits\Livewire\HasTable;
use Livewire\Attributes\{Computed, On};
use Livewire\{Component, WithPagination, WithoutUrlPagination};
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;
    use WithPagination;
    use WithoutUrlPagination;
    use HasTable;

    public bool $drawer = false;

    public function mount()
    {

        $this->authorize(CanEnum::BE_AN_ADMIN->value);
    }

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Delete action
    public function delete($id): void
    {
        $this->warning("Will delete #$id", 'It is fake.', position: 'toast-bottom');
    }

    public function showMatrix($id): void
    {

        $this->dispatch('matrix::show', id: $id)->to('admin.matrices.show');
    }

    // Table headers
    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Matrix', 'class' => 'w-64'],
            ['key' => 'threshold', 'label' => 'Threshold', 'class' => 'w-20'],
            ['key' => 'bandwidth', 'label' => 'Bandwidth', 'class' => 'w-20'],
        ];
    }

    // Reset pagination when any component property changes
    public function updated($property): void
    {
        if (!is_array($property) && $property != "") {
            $this->resetPage();
        }
    }

    #[Computed]
    public function items(): \Illuminate\Pagination\Paginator
    {
        return Matrix::query()
            ->search($this->search, ['name', 'bandwidth'])
            ->orderBy(...array_values($this->sortBy))
            ->simplePaginate();
    }

    #[On('matrices::reload')]
    public function render()
    {
        return view('livewire.admin.matrices.index');
    }
}
