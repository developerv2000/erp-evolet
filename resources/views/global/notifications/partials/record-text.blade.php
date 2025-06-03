@php
    $baseClass = class_basename($record->type);
    $partialPath = "global.notifications.partials.types.{$baseClass}";
@endphp

@include($partialPath)
