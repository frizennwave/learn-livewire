<?php

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = 'all';

    public function with(): array
    {
        $query = Comment::with(['user', 'post'])->latest();

        // Filter by search
        if ($this->search) {
            $query->where('content', 'like'.'%'.$this->search.'%');
        }

        // Filter by status
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Author only see comments on their own posts
        if (auth()->user()->hasRole('author')) {
            $query->whereHas('post', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        return [
            'comments' => $query->paginate(20),
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function approveComment(Comment $comment)
    {
        $comment->update(['status' => 'approved']);
        session()->flash('success', 'Comment approved!');
    }

    public function markAsSpam(Comment $comment)
    {
        $comment->update(['status' => 'spam']);
        session()->flash('success', 'Comment marked as spam!');
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        session()->flash('success', 'Comment deleted!');
    }
};
