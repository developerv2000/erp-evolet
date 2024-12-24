<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Support\Helpers\ModelHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use DestroysModelRecords;

    public $model = Comment::class; // used in multiple destroy trait

    public function index(Request $request)
    {
        // Find model
        $modelBaseName = $request->route('commentable_type');
        $model = ModelHelper::addFullNamespaceToModelBasename($modelBaseName);

        // Load model comments
        $recordID = $request->route('commentable_id');
        $record = $model::withTrashed()->find($recordID);
        $record->load('comments');

        // Load comments minified users
        Comment::loadRecordsMinifiedUsers($record->comments);

        return view('comments.index', compact('record'));
    }
}
