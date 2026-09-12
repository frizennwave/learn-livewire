<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    public string $search = '';

    #[Layout('layouts.public')]
    #[Title('Blog')]
    public function render(): View
    {
        $posts = Post::with('user')
            ->where('status', 'published')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('excerpt', 'like', '%'.$this->search.'%');
            })
            ->latest('published_at')
            ->paginate(9);

        return view('livewire.post-list', [
            'posts' => $posts,
        ]);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}
