<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class ProcessStatusHistory extends Model
{
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

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Update & destroy
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

    /**
     * Delete the model if it is not the active status history of the process.
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException if the status history is active and cannot be deleted.
     */
    public function destroyFromRequest()
    {
        // Active status history cannot be deleted
        if ($this->isActiveStatusHistory()) {
            throw ValidationException::withMessages([
                'process_status_history_deletion' => trans('validation.custom.process_status_history.is_active_history'),
            ]);
        }

        // Delete the status history record
        $this->delete();
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
     *
     * @return bool True if this is the active status history of the process, false otherwise.
     */
    public function isActiveStatusHistory()
    {
        // Retrieve the process associated with the current process_id
        $process = Process::find($this->process_id);

        // Return false if the process is not found
        if (!$process) {
            return false;
        }

        // Check if this 'status_id' matches the process's 'status_id' and 'end_date' is null
        return $process->status_id == $this->status_id && is_null($this->end_date);
    }
}
