<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public Post $post;

    #[Validate('required|string|min:3|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:500')]
    public string $excerpt = '';

    #[Validate('required|string|min:10')]
    public string $content = '';

    #[Validate('nullable|image|max:2048')]
    public $featured_image;

    #[Validate('required|in:draft,published,archived')]
    public string $status = '';

    public string $existing_image = '';

    #[Validate('required|array|min:1')]
    public array $selectedCategories = [];

    #[Validate('nullable|array')]
    public array $selectedTags = [];

    public function mount(Post $post)
    {
        if (! auth()->user()->can('edit all posts') &&
            ! auth()->user()->can('edit own posts') && $post->user_id === auth()->id()) {
            abort(403);
        }

        $this->post = $post;
        $this->title = $post->title;
        $this->excerpt = $post->excerpt ?? '';
        $this->content = $post->content;
        $this->status = $post->status;
        $this->existing_image = $post->featured_image ?? '';

        // load existing categories and tags
        $this->selectedCategories = $post->categories->pluck('id')->toArray();
        $this->selectedTags = $post->tags->pluck('id')->toArray();
    }

    public function with(): array
    {
        return [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ];
    }

    public function update()
    {
        $this->validate();

        $this->post->title = $this->title;
        $this->post->slug = Str::slug($this->title);
        $this->post->excerpt = $this->excerpt;
        $this->post->content = $this->content;
        $this->post->status = $this->status;

        if ($this->featured_image) {
            // Delete old image if exists
            if ($this->existing_image) {
                Storage::disk('public')->delete($this->existing_image);
            }

            $path = $this->featured_image->store('posts', 'public');
            $this->post->featured_image = $path;
            $this->existing_image = $path;
        }

        if ($this->status === 'published' && ! $this->post->published_at) {
            $this->post->published_at = now();
        }

        $this->post->save();

        // Sync categories and tags
        $this->post->categories()->sync($this->selectedCategories);
        $this->post->tags()->sync($this->selectedTags);

        session()->flash('success', 'Post updated successfully!');

        $this->redirect(route('posts.index'), navigate: true);
    }
};
