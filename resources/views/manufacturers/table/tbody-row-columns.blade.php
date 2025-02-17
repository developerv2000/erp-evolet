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
        <a class="main-link" href="{{ route('products.index', ['manufacturer_id[]' => $record->id]) }}">
            {{ $record->products_count }} {{ __('products') }}
        </a>
    @break

    @case('Manufacturer')
        {{ $record->name }}
    @break

    @case('Category')
        <span @class([
            'badge',
            'badge--blue' => $record->category->name == 'УДС',
            'badge--yellow' => $record->category->name == 'НПП',
        ])>
            {{ $record->category->name }}
        </span>
    @break

    @case('Status')
        @if ($record->active)
            <span class="badge badge--orange">{{ __('Active') }}</span>
        @else
            <span class="badge badge--grey">{{ __('Stopped') }}</span>
        @endif
    @break

    @case('Important')
        @if ($record->important)
            <span class="badge badge--pink">{{ __('Important') }}</span>
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

    @case('Blacklist')
        @foreach ($record->blacklists as $list)
            {{ $list->name }} <br>
        @endforeach
    @break

    @case('Presence')
        <x-tables.partials.td.max-lines-limited-text :text="$record->presences->pluck('name')->join(' ')" />
    @break

    @case('Website')
        @if ($record->website)
            <div class="td__max-lines-limited-text" data-on-click="toggle-td-text-max-lines">
                <a class="main-link text-lowercase" href="{{ $record->website }}" target="_blank">{{ $record->website }}</a>
            </div>
        @endif
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

    @case('Meetings')
        <a class="main-link" href="{{ route('meetings.index', ['manufacturer_id[]' => $record->id]) }}">
            {{ $record->meetings_count }} {{ __('meetings') }}
        </a>
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Attachments')
        @can('edit-MAD-EPP')
            <x-tables.partials.td.model-attachments-link :record="$record" />
        @endcan

        <x-tables.partials.td.model-attachments-list :attachments="$record->attachments" />
    @break

@endswitch
