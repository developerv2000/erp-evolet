@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('plpd.orders.edit', $record->id)" />
    @break

    @case('BDM')
        <x-misc.ava image="{{ $record->manufacturer->bdm->photo_asset_url }}" title="{{ $record->manufacturer->bdm->name }}" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Receive date')
        {{ $record->receive_date->isoformat('DD MMM Y') }}
    @break

    @case('Manufacturer')
        {{ $record->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->country->name }}
    @break

    @case('Sent to BDM')
        {{ $record->receive_date->isoformat('DD MMM Y') }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Comments date')
        {{ $record->lastComment?->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break
@endswitch
