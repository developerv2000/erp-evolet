<?php

namespace App\Http\Controllers\MAD;

use App\Http\Controllers\Controller;
use App\Models\Process;
use App\Models\ProcessStatus;
use App\Models\ProcessStatusHistory;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class MADProcessStatusHistoryController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy trait
    public static $model = ProcessStatusHistory::class;

    public function index(Request $request, $processID)
    {
        $process = Process::witRelationsForHistoryPage()->find($processID);
        $product = $process->product;

        return view('process-status-history.index', compact('process', 'product'));
    }

    public function edit($processID, ProcessStatusHistory $record)
    {
        $process = Process::find($processID);
        $statuses = ProcessStatus::all();

        return view('process-status-history.edit', compact('process', 'record', 'statuses'));
    }

    public function update(Request $request, Process $process, ProcessStatusHistory $record)
    {
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
