<?php

namespace App\Support\Traits\Controller;

use Illuminate\Http\Request;

/**
 * Trait DestroysModelRecords
 *
 * This trait provides functionality to destroy model records, either by soft deleting or force deleting.
 *
 * **Note:** The parent class using this trait **must declare a public static `$model` variable**,
 * which should hold the fully qualified model class name.
 *
 * @package App\Support\Traits\Controller
 */
trait DestroysModelRecords
{
    /**
     * Destroy model records based on the request parameters.
     *
     * If the 'force_delete' parameter is provided and the user is an admin,
     * the records will be force deleted. Otherwise, they will be soft deleted.
     *
     * @param Request $request The request object.
     * @return \Illuminate\Http\RedirectResponse Redirect back to the previous page.
     */
    public function dashboardDestroy(Request $request)
    {
        // Extract id or ids from request as array to delete through loop
        $ids = (array) ($request->input('id') ?: $request->input('ids'));

        if ($request->input('force_delete')) {
            foreach ($ids as $id) {
                // Check if model exists before force deleting
                $record = static::$model::withTrashed()->find($id);
                if ($record) {
                    $record->forceDelete();
                }
            }
        } else {
            // Soft delete or trash records
            foreach ($ids as $id) {
                // Check if model exists before soft deleting
                $record = static::$model::find($id);

                if ($record) {
                    $record->delete();
                }
            }
        }

        return redirect()->back();
    }
}
