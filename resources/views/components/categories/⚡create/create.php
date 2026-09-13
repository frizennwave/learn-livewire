<?php

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|max:255')]
    public string $description = '';

    #[Validate(['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'])]
    public string $color = '';

    public function save()
    {
        $this->validate();

        $category = new Category;
        $category->name = $this->name;
        $category->slug = Str::slug($this->name);
        $category->description = $this->description;
        $category->color = $this->color;

        $category->save();

        session()->flash('success', 'Category created successfully!');

        $this->redirect(route('categories.index'));
    }
};
