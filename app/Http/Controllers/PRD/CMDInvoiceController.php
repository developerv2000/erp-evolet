<?php

namespace App\Http\Controllers\PRD;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class PRDInvoiceController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy trait
    public static $model = Invoice::class;

    public function index(Request $request, Order $order)
    {
        $records = $order->invoices()->withBasicRelations()->get();

        return view('CMD.invoices.index', compact('order', 'records'));
    }

    public function create(Request $request, Order $order)
    {
        if (!$order->canAttachNewInvoice()) {
            abort(404);
        }

        return view('CMD.invoices.create', compact('order'));
    }

    public function store(Request $request)
    {
        Invoice::createByCMDFromRequest($request);

        return to_route('cmd.invoices.index', $request->input('order_id'));
    }

    public function edit(Request $request, Invoice $record)
    {
        return view('CMD.invoices.edit', compact('record'));
    }

    public function update(Request $request, Invoice $record)
    {
        $record->updateByCMDFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
