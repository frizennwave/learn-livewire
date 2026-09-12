<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    #[Validate('required|string|min:3|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:500')]
    public string $excerpt = '';

    #[Validate('required|string|min:10')]
    public string $content = '';

    #[Validate('nullable|image|max:2048')]
    public $featured_image;

    #[Validate('required|in:draft,published')]
    public string $status = 'draft';

    #[Validate('required|array|min:1')]
    public array $selectedCategories = [];

    #[Validate('nullable|array')]
    public array $selectedTags = [];

    // get the categories and tags
    public function with(): array
    {
        return [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ];
    }

    public function save()
    {
        $this->validate();

        $post = new Post;
        $post->user_id = auth()->id();
        $post->title = $this->title;
        $post->slug = Str::slug($this->title);
        $post->excerpt = $this->excerpt;
        $post->content = $this->content;
        $post->status = $this->status;

        if ($this->featured_image) {
            $path = $this->featured_image->store('posts', 'public');
            $post->featured_image = $path;
        }

        if ($this->status === 'published') {
            $post->published_at = now();
        }

        $post->save();

        // attach the categories and tags
        $post->categories()->attach($this->selectedCategories);

        if (! empty($this->selectedTags)) {
            $post->tags()->attach($this->selectedTags);
        }

        session()->flash('success', 'Post created successfully!');

        $this->redirect(route('posts.index'), navigate: true);
    }
};
