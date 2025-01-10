@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('products.edit', $record->id)" />
    @break

    @case('Processes')
        <x-misc.buttoned-link
            style="transparent"
            class="button--arrowed-link button--margined-bottom"
            icon="arrow_forward"
            :link="$record->processes_index_link">
            {{ $record->processes_count }} {{ __('processes') }}
        </x-misc.buttoned-link>

        <a class="main-link" href="{{ route('processes.create', ['product_id' => $record->id]) }}">
            {{ __('New process') }}
        </a>
    @break

    @case('Category')
        <span @class([
            'badge',
            'badge--yellow' => $record->manufacturer->category->name == 'УДС',
            'badge--pink' => $record->manufacturer->category->name == 'НПП',
        ])>
            {{ $record->manufacturer->category->name }}
        </span>
    @break

    @case('Country')
        {{ $record->manufacturer->country->name }}
    @break

    @case('Manufacturer')
        {{ $record->manufacturer->name }}
    @break

    @case('Manufacturer Brand')
        {{ $record->brand }}
    @break

    @case('Generic')
        <x-tables.partials.td.max-lines-limited-text :text="$record->inn->name" />
    @break

    @case('Form')
        {{ $record->form->name }}
    @break

    @case('Basic form')
        {{ $record->form->parent_name }}
    @break

    @case('Dosage')
        <x-tables.partials.td.max-lines-limited-text :text="$record->dosage" />
    @break

    @case('Pack')
        {{ $record->pack }}
    @break

    @case('MOQ')
        {{ $record->moq }}
    @break

    @case('Shelf life')
        {{ $record->shelfLife->name }}
    @break

    @case('Product class')
        <span class="badge badge--green">{{ $record->class->name }}</span>
    @break

    @case('Dossier')
        <x-tables.partials.td.max-lines-limited-text :text="$record->dossier" />
    @break

    @case('Zones')
        @foreach ($record->zones as $zone)
            {{ $zone->name }} <br>
        @endforeach
    @break

    @case('Bioequivalence')
        <x-tables.partials.td.max-lines-limited-text :text="$record->bioequivalence" />
    @break

    @case('Validity period')
        {{ $record->validity_period }}
    @break

    @case('Registered in EU')
        @if ($record->registered_in_eu)
            <span class="badge badge--orange">{{ __('Registered') }}</span>
        @endif
    @break

    @case('Sold in EU')
        @if ($record->sold_in_eu)
            <span class="badge badge--blue">{{ __('Sold') }}</span>
        @endif
    @break

    @case('Down payment')
        {{ $record->down_payment }}
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

    @case('BDM')
        <x-misc.ava image="{{ $record->manufacturer->bdm->photo_asset_url }}" title="{{ $record->manufacturer->bdm->name }}" />
    @break

    @case('Analyst')
        <x-misc.ava image="{{ $record->manufacturer->analyst->photo_asset_url }}" title="{{ $record->manufacturer->analyst->name }}" />
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

    @case('Matched KVPPs')
        Matched KVPPs
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
