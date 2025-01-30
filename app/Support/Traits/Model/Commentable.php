<?php

namespace App\Support\Traits\Model;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * Boot the trait and add model events.
     */
    public static function bootCommentable()
    {
        static::deleting(function ($model) {
            // Check if the model uses SoftDeletes
            $isSoftDeleting = in_array(SoftDeletes::class, class_uses_recursive($model));

            if (!$isSoftDeleting || $model->forceDeleting) {
                // Only delete comments if it's a permanent delete
                $model->comments()->each(fn($comment) => $comment->delete());
            }
        });
    }

    /**
     * Store a new comment associated with the model.
     *
     * @param string|null $comment The comment body.
     * @return void
     */
    public function addComment(?string $comment): void
    {
        if (!$comment) {
            return;
        }

        $this->comments()->create([
            'body' => $comment,
            'user_id' => request()->user()->id,
        ]);
    }

    /**
     * Store comment from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function storeCommentFromRequest($request): void
    {
        $comment = $request->input('comment');

        if ($comment) {
            $this->addComment($comment);
        }
    }
}
