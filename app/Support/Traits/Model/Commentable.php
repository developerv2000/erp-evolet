<?php

namespace App\Support\Traits\Model;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Commentable
{
    /**
     * Get all comments associated with the model, ordered by ID in descending order.
     *
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('id', 'desc');
    }

    /**
     * Get the last comment associated with the model.
     *
     * @return MorphOne
     */
    public function lastComment(): MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable')->latestOfMany();
    }

    /**
     * Store a new comment associated with the model.
     *
     * @param string|null $comment The comment body.
     * @return void
     */
    public function storeComment(?string $comment): void
    {
        if (!$comment) {
            return;
        }

        $this->comments()->create([
            'body' => $comment,
            'user_id' => request()->user()->id,
        ]);
    }
}
