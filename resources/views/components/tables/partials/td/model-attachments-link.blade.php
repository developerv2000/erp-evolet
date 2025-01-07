@props(['record'])

<x-misc.buttoned-link
    style="transparent"
    class="button--arrowed-link button--margined-bottom"
    icon="arrow_forward"
    link="{{ route('attachments.index', [class_basename($record), $record->id]) }}">
    {{ $record->attachments_count }} {{ __('attachments') }}
</x-misc.buttoned-link>
