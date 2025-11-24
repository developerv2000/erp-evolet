@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('export.assemblages.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Manufacturer')
        {{ $record->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->country->code }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break
@endswitch
