@props(['record'])

<a class="main-link" href="{{ route('comments.index', [class_basename($record), $record->id]) }}">
    {{ $record->comments_count }} {{ __('comments') }}
</a>
