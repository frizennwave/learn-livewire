<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostView;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

new class extends Component
{
    public function with(): array
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin') || $user->hasRole('editor');

        // base query filters by author if not adnim
        $postsQuery = $isAdmin ? Post::query() : Post::where('user_id', $user->id);

        // calculate stats
        $stats = [
            'total_posts' => (clone $postsQuery)->count(),
            'published_posts' => (clone $postsQuery)->where('status', 'published')->count(),
            'draft_posts' => (clone $postsQuery)->where('status', 'draft')->count(),
            'total_views' => (clone $postsQuery)->sum('views_count'),
            'total_comments' => $isAdmin ? Comment::count() : Comment::whereHas('post', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'total_users' => $isAdmin ? User::count() : null,
        ];

        // most viewed posts
        $mostViewedPosts = (clone $postsQuery)
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // recent comments
        $recentComments = Comment::with(['user', 'post'])
            ->when(! $isAdmin, function ($q) use ($user) {
                $q->whereHas('post', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            })
            ->latest()
            ->take(5)
            ->get();

        // views over last 7 days
        $RawviewsData = PostView::select(
            DB::raw('DATE(viewed_at) as date'),
            DB::raw('COUNT(*) as count'),
        )
            ->when(! $isAdmin, function ($q) use ($user) {
                $q->whereHas('post', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            })
            ->where('viewed_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date'); // easy for lookup

        // fill in missing dates with 0 - EXACTLY like a last seven overview
        $viewsData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $dateLabel = $date->format('M d');

            $viewsData->push([
                'date' => $dateLabel,
                'count' => isset($RawviewsData[$dateKey]) ? $RawviewsData[$dateKey]->count : 0,
            ]);
        }

        return compact('stats', 'mostViewedPosts', 'recentComments', 'viewsData', 'isAdmin');
    }
};
