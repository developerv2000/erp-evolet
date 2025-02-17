@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('meetings.edit', $record->id)" />
    @break

    @case('Year')
        {{ $record->year }}
    @break

    @case('Manufacturer')
        {{ $record->manufacturer->name }}
    @break

    @case('BDM')
        <x-misc.ava image="{{ $record->manufacturer->bdm->photo_asset_url }}" title="{{ $record->manufacturer->bdm->name }}" />
    @break

    @case('Analyst')
        <x-misc.ava image="{{ $record->manufacturer->analyst->photo_asset_url }}" title="{{ $record->manufacturer->analyst->name }}" />
    @break

    @case('Country')
        {{ $record->manufacturer->country->name }}
    @break

    @case('Who met')
        <x-tables.partials.td.max-lines-limited-text :text="$record->who_met" />
    @break

    @case('Plan')
        <x-tables.partials.td.max-lines-limited-text :text="$record->plan" />
    @break

    @case('Topic')
        <x-tables.partials.td.max-lines-limited-text :text="$record->topic" />
    @break

    @case('Result')
        <x-tables.partials.td.max-lines-limited-text :text="$record->result" />
    @break

    @case('Outside the exhibition')
        <x-tables.partials.td.max-lines-limited-text :text="$record->outside_the_exhibition" />
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

    @case('ID')
        {{ $record->id }}
    @break
@endswitch
