<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Support\Helpers\ModelHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use DestroysModelRecords;

    public static $model = Comment::class; // used in multiple destroy trait

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

    public function store(Request $request)
    {
        $model = $request->input('commentable_type');
        $recordID = $request->input('commentable_id');

        $record = $model::withTrashed()->find($recordID);
        $record->storeComment($request->input('body'));

        return redirect()->back();
    }

    public function edit(Comment $record)
    {
        $title = $record->commentable->getCommentsPageTitle();

        return view('comments.edit', compact('record', 'title'));
    }

    public function update(Request $request, Comment $record)
    {
        $record->update($request->all());

        return redirect($request->input('previous_url'));
    }
}
