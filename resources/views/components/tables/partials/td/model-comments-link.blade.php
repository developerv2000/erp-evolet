@props(['record'])

<a class="td__link" href="{{ route('comments.index', [class_basename($record), $record->id]) }}">
    {{ $record->comments_count }} {{ __('comments') }}
</a>
