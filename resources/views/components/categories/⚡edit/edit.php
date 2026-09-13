<?php

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    public Category $category;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $description = '';

    #[Validate(['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'])]
    public string $color = '';

    public string $originalColor = '';

    public function mount(Category $category)
    {
        if (! auth()->user()->can('manage roles')) {
            abort(403);
        }

        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->color = $category->color;

        $this->originalColor = $category->color;
    }

    public function resetColor()
    {
        $this->color = $this->originalColor;
    }

    public function update()
    {
        $this->validate();

        $this->category->name = $this->name;
        $this->category->description = $this->description;
        $this->category->color = $this->color;

        $this->category->save();

        session()->flash('success', 'Category updated successfully!');

        $this->redirect(route('categories.index'));
    }
};
