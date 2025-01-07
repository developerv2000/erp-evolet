<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Comment extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the parent commentable model.
     *
     * This defines a polymorphic relationship where a comment can belong to any model.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function ($record) {
            $record->created_at = $record->created_at ?: now();
        });

        static::created(function ($record) {
            if ($record->commentable_type == Manufacturer::class) {
                $record->commentable->updateSelfOnCommentCreate();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Additional attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Get plain text without HTML tags.
     * Used on displaying last comment.
     */
    public function getPlainTextAttribute()
    {
        // Add a space after each closing tag to prevent text from joining
        $withSpaces = preg_replace('/>(?!\s)/', '> ', $this->body);

        // Strip HTML tags
        $plainText = strip_tags($withSpaces);

        // Normalize by decoding HTML entities
        $decodedText = htmlspecialchars_decode($plainText);

        // Replace multiple spaces with a single space
        $normalizedText = preg_replace('/\s+/', ' ', $decodedText);

        // Trim the result
        return Str::of($normalizedText)->trim();
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Used in comments.index page
     */
    public static function loadRecordsMinifiedUsers($comments)
    {
        return $comments->load(['user' => function ($query) {
            $query->select('id', 'name', 'photo')
                ->withOnly([]);
        }]);
    }
}
