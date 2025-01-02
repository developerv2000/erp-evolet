<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Support\Helpers\ModelHelper;
use App\Support\Traits\Controller\DestroysModelRecords;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use DestroysModelRecords;

    // used in multiple destroy trait
    public static $model = Comment::class;

    public function index(Request $request)
    {
        // Retrieve the model and record with comments eagerly loaded
        $model = ModelHelper::addFullNamespaceToModelBasename(
            $request->route('commentable_type')
        );
        $record = $model::withTrashed()->with(['comments'])->findOrFail(
            $request->route('commentable_id')
        );

        // Load comments minified users
        Comment::loadRecordsMinifiedUsers($record->comments);

        // Generate breadcrumbs
        $breadcrumbs = $record->generateBreadcrumbs();
        $breadcrumbs[] = [
            'link' => null,
            'text' => __('Comments'),
        ];

        return view('comments.index', compact('record', 'breadcrumbs'));
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
        $record->load('commentable');

        // Generate breadcrumbs
        $crumbs = $record->commentable->generateBreadcrumbs();
        array_push(
            $crumbs,
            ['link' => null, 'text' => __('Comments')],
            ['link' => null, 'text' => '#' . $record->id]
        );

        return view('comments.edit', compact('record', 'crumbs'));
    }

    public function update(Request $request, Comment $record)
    {
        $record->update($request->all());

        return redirect($request->input('previous_url'));
    }
}
