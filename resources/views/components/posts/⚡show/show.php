<?php

use App\Models\Post;
use App\Models\PostView;
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

        // track views
        $this->trackView();
    }

    protected function trackView()
    {
        // increment the counter
        $this->post->increment('views_count');

        // record the detailed view
        PostView::create([
            'post_id' => $this->post->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
            'viewed_at' => now(),
        ]);
    }
};
