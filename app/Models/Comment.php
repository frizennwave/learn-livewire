<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['post_id', 'user_id', 'parent_id', 'content', 'status'])]
class Comment extends Model
{
    /**
     * @return BelongsTo<Post, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Comment, $this>
     */
    public function parent(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * @return HasMany<Comment, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'replies');
    }

    #[Scope]
    /**
    * @param Builder<Comment> $query
    */
    public function approved(Builder $query): void
    {
        $query->where('status', 'approved');
    }

    #[Scope]
    /**
    * @param Builder<Comment> $query
    */
    public function topLevel(Builder $query): void
    {
        $query->whereNull('parent_id');
    }
}
