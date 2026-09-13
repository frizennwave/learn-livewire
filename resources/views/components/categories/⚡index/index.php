<?php

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->withCount('posts')->latest()->paginate(10);

        return $this->view([
            'categories' => $categories,
        ]);
    }

    public function deleteCategory(Category $category)
    {
        if (auth()->user()->can('manage roles')) {
            $category->delete();

            session('success', 'Category deleted successfully!');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
};
