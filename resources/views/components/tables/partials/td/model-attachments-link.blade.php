@props(['record'])

<a class="td__link td__link--secondary" href="{{ route('attachments.index', [class_basename($record), $record->id]) }}">
    {{ $record->attachments_count }} {{ __('attachments') }}
</a>

<br>
