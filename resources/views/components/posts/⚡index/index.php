<?php

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = 'all';

    public function with(): array
    {
        $query = Post::with('user')->latest();

        // filter by search
        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('content', 'like', '%'.$this->search.'%');
        }

        // filter by status
        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // Authorization: Authors only see their own posts
        if (auth()->user()->hasRole('author')) {
            $query->where('user_id', auth()->id());
        }

        return [
            'posts' => $query->paginate(10),
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function deletePost(Post $post)
    {
        // authorize
        if (auth()->user()->can('delete all posts')
            || auth()->user()->can('delete own posts')
                && $post->user_id === auth()->user()->id()) {
            $post->delete();

            session()->flash('success', 'Post deleted successfully!');
        }
    }
};
