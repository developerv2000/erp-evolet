<?php


namespace App\Http\Controllers\global;

use App\Http\Controllers\Controller;
use App\Models\Process;
use Illuminate\Http\Request;

/**
 * Shared route actions, which are used by multiple departments
 */
class SharedController extends Controller
{
    /**
     * Ajax request on "Order" create & "OrderProduct" create/edit pages.
     *
     * Used to select specific process with required MAH, from different similar processes,
     * with the same product and country context.
     */
    public function getProcessWithItSimilarRecordsForOrder(Request $request)
    {
        $process = Process::findOrFail($request->input('process_id'));

        return response()->json([
            'processWithItSimilarRecords' => $process->getProcessWithItSimilarRecordsForOrder(true),
        ]);
    }
}
