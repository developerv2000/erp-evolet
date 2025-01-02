<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Not used yet.
     */
    public function getPlainTextAttribute()
    {
        // Strip HTML tags
        $plainText = strip_tags($this->body);

        // Normalize and decode
        $decodedText = htmlspecialchars_decode($plainText);

        // Trimme whitespace
        $trimmedText = trim($decodedText);

        return $trimmedText;
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    public static function loadRecordsMinifiedUsers($comments)
    {
        return $comments->load(['user' => function ($query) {
            $query->select('id', 'name', 'photo')
                ->withOnly([]);
        }]);
    }
}
