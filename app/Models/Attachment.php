<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
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
     * Get the parent attachable model (morphTo relationship).
     */
    public function attachable()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($instance) {
            $instance->created_at = now();
        });

        static::deleting(function ($instance) {
            $instance->deleteFileFromStorage();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Delete the file associated with the attachment from storage.
     *
     * @return void
     */
    public function deleteFileFromStorage()
    {
        // Construct the full file path relative to the public directory
        $filePath = public_path($this->file_path);

        // Ensure the file exists before trying to delete it
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Get the file size in megabytes.
     *
     * @return float
     */
    public function getFileSizeInMegabytesAttribute()
    {
        return round($this->file_size / (1024 * 1024), 2);
    }
}
