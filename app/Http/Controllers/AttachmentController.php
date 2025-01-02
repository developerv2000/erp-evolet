<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Support\Helpers\ModelHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttachmentController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy trait
    public static $model = Attachment::class;

    public function index(Request $request)
    {
        // Secure request
        $this->authorizeGates($request->route('attachable_type'));

        // Retrieve the model and record with attachments eagerly loaded
        $model = ModelHelper::addFullNamespaceToModelBasename(
            $request->route('attachable_type')
        );
        $record = $model::withTrashed()->with(['attachments'])->findOrFail(
            $request->route('attachable_id')
        );

        // Generate breadcrumbs
        $breadcrumbs = $record->generateBreadcrumbs();
        $breadcrumbs[] = [
            'link' => null,
            'text' => __('Attachments'),
        ];
        $breadcrumbs[] = [
            'link' => null,
            'text' => $record->attachments->count() . ' ' . __('records'),
        ];

        return view('attachments.index', compact('record', 'breadcrumbs'));
    }

    private function authorizeGates($modelBaseName)
    {
        switch ($modelBaseName) {
            case 'Manufacturer':
                Gate::authorize('edit-MAD-EPP');
                break;
            case 'Product':
                Gate::authorize('edit-MAD-IVP');
                break;
            default:
                abort(404);
        }
    }
}
