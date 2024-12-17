<?php

namespace App\Support\Traits\Model;

use App\Models\Like;
use Illuminate\Database\Eloquent\Model;

/**
 * Important: This trait assumes that the 'likes' relationship is eager loaded.
 * If the 'likes' relationship is not eager loaded, it will result in additional queries.
 */
trait Likeable
{
    /**
     * Get all likes for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check if the model is liked by the current authenticated user.
     *
     * Important: This method assumes that the 'likes' relationship is eager loaded.
     * If the 'likes' relationship is not eager loaded, it will result in additional queries.
     *
     * @return bool
     */
    public function isLikedByCurrentUser()
    {
        return $this->likes->contains('user_id', auth()->id());
    }

    /**
     * Add a like from the current authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Model|bool
     */
    public function likeByCurrentUser()
    {
        if (!$this->isLikedByCurrentUser()) {
            return $this->likes()->create([
                'user_id' => auth()->id(),
            ]);
        }

        return false;
    }

    /**
     * Remove the like from the current authenticated user.
     *
     * @return bool|null
     */
    public function unlikeByCurrentUser()
    {
        if ($this->isLikedByCurrentUser()) {
            return $this->likes()->where('user_id', auth()->id())->delete();
        }

        return false;
    }

    /**
     * Toggle like status for the current authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Model|bool|null
     */
    public function toggleLikeByCurrentUser()
    {
        if ($this->isLikedByCurrentUser()) {
            return $this->unlikeByCurrentUser();
        }

        return $this->likeByCurrentUser();
    }

    /**
     * Count the number of likes for the model.
     *
     * Important: This method assumes that the 'likes' relationship is eager loaded.
     * If the 'likes' relationship is not eager loaded, it will result in additional queries.
     *
     * @return int
     */
    public function likesCount()
    {
        return $this->likes->count();
    }
}
