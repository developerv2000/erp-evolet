<?php

namespace App\Http\Controllers\CMD;

use App\Http\Controllers\Controller;
use App\Models\AttachedOrderInvoice;
use App\Models\Order;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class CMDAttachedOrderInvoiceController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy trait
    public static $model = AttachedOrderInvoice::class;

    public function index(Request $request, Order $order)
    {
        $records = $order->attachedInvoices()->withBasicRelations()->get();

        return view('CMD.appended-order-invoices.index', compact('request', 'records'));
    }

    public function create(Request $request, Order $order)
    {
        if (!$order->canAttachNewInvoice()) {
            abort(404);
        }

        return view('CMD.appended-order-invoices.create', compact('order'));
    }

    public function store(Request $request)
    {
        AttachedOrderInvoice::createFromRequest($request);

        return to_route('cmd.attached-order-invoices.index', $request->input('order_id'));
    }

    public function edit(Request $request, AttachedOrderInvoice $record)
    {
        return view('CMD.appended-order-invoices.edit', compact('record'));
    }

    public function update(Request $request, AttachedOrderInvoice $record)
    {
        $record->updateFromRequest($request);

        return redirect($request->input('previous_url'));
    }
}
