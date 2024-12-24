@props(['record', 'column'])

@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('manufacturers.edit', $record->id)" />
    @break

    @case('BDM')
        <x-misc.ava image="{{ $record->bdm->photo_asset_url }}" title="{{ $record->bdm->name }}" />
    @break

    @case('Analyst')
        <x-misc.ava image="{{ $record->analyst->photo_asset_url }}" title="{{ $record->analyst->name }}" />
    @break

    @case('Country')
        {{ $record->country->name }}
    @break

    @case('IVP')
        IVP
    @break

    @case('Manufacturer')
        {{ $record->name }}
    @break

    @case('Category')
        <span @class([
            'badge',
            'badge--yellow' => $record->category->name == 'УДС',
            'badge--pink' => $record->category->name == 'НПП',
        ])>
            {{ $record->category->name }}
        </span>
    @break

    @case('Status')
        @if ($record->active)
            <span class="badge badge--blue">{{ __('Active') }}</span>
        @else
            <span class="badge badge--grey">{{ __('Stoped') }}</span>
        @endif
    @break

    @case('Important')
        @if ($record->important)
            <span class="badge badge--orange">{{ __('Important') }}</span>
        @endif
    @break

    @case('Product class')
        <div class="td__badges-wrapper">
            @foreach ($record->productClasses as $class)
                <span class="badge badge--green">{{ $class->name }}</span>
            @endforeach
        </div>
    @break

    @case('Zones')
        @foreach ($record->zones as $zone)
            {{ $zone->name }} <br>
        @endforeach
    @break

    @case('Black list')
        @foreach ($record->blacklists as $list)
            {{ $list->name }} <br>
        @endforeach
    @break

    @case('Presence')
        <x-tables.partials.td.max-lines-limited-text :text="$record->presences->pluck('name')->join(' ')" />
    @break

    @case('Website')
        <x-tables.partials.td.max-lines-limited-text :text="$record->website" />
    @break

    @case('About company')
        <x-tables.partials.td.max-lines-limited-text :text="$record->about" />
    @break

    @case('Relationship')
        <x-tables.partials.td.max-lines-limited-text :text="$record->relationship" />
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->body" />
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

    @case('Meetings')
        Meetings
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Attachments')
        Attachments
    @break

    @case('Edit attachments')
        Attachments
    @break
@endswitch
