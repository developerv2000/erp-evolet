<?php

namespace App\Models;

use App\Support\Traits\Model\FormatsAttributeForDateTimeInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class ProcessStatusHistory extends Model
{
    use FormatsAttributeForDateTimeInput;

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public $timestamps = false;
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function status()
    {
        return $this->belongsTo(ProcessStatus::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class)->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::deleting(function ($record) {
            // Active status history cannot be deleted
            if ($record->isActiveStatusHistory()) {
                throw ValidationException::withMessages([
                    'process_status_history_deletion' => trans('validation.custom.process_status_history.is_active_history'),
                ]);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    /**
     * Update the model's attributes from the given request.
     *
     * @param \Illuminate\Http\Request $request The request object containing input data.
     * @return void
     */
    public function updateFromRequest($request)
    {
        // Update start_date from the request input
        $this->start_date = $request->input('start_date');

        // 'status_id' and 'end_date' can`t be updated for active status history
        if (!$this->isActiveStatusHistory()) {
            $this->status_id = $request->input('status_id');
            $this->end_date = $request->input('end_date');
            $this->duration_days = (int) $this->start_date->diffInDays($this->end_date);
        }

        $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | Misc
    |--------------------------------------------------------------------------
    */

    /**
     * Close status history by updating the 'end_date' and calculating the 'duration_days'.
     *
     * Called when process status is being changed.
     *
     * @return void
     */
    public function close()
    {
        $this->update([
            'end_date' => now(),
            'duration_days' => $this->start_date->diffInDays(now()),
        ]);
    }

    /**
     * Determine if this status history is the active history of the associated process.
     */
    public function isActiveStatusHistory()
    {
        return $this->end_date ? false : true;
    }
}
