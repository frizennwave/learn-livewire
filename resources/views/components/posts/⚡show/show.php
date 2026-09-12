<?php

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.public')] class extends Component
{
    public Post $post;

    public function mount($slug)
    {
        $this->post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['user', 'categories', 'tags'])
            ->firstOrFail();
    }
};
