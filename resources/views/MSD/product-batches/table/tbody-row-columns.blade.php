@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('msd.product-batches.edit', $record->id)" />
    @break

    @case('Series')
        {{ $record->series }}
    @break

    @case('Manufacturing date')
        {{ $record->manufacturing_date?->isoformat('DD MMM Y') }}
    @break

    @case('Expiration date')
        {{ $record->expiration_date?->isoformat('DD MMM Y') }}
    @break

    @case('Quantity')
        {{ $record->quantity }}
    @break

    @case('Country')
        {{ $record->product->order->country->code }}
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->product->process->full_trademark_en" />
    @break

    @case('Number of boxes (full)')
        {{ $record->number_of_full_boxes }}
    @break

    @case('Number of packages in box (full)')
        {{ $record->number_of_packages_in_full_box }}
    @break

    @case('Number of boxes (incomplete)')
        {{ $record->number_of_incomplete_boxes }}
    @break

    @case('Number of packages in box (incomplete)')
        {{ $record->number_of_packages_in_incomplete_box }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Start date of work')
        {{ $record->serialization_start_date?->isoformat('DD MMM Y') }}
    @break

    @case('Factual quantity')
        @if ($record->factual_quantity)
            <x-tables.partials.td.formatted-price :price="$record->factual_quantity" />
        @endif
    @break

    @case('Defects quantity')
        @if ($record->defects_quantity)
            <x-tables.partials.td.formatted-price :price="$record->defects_quantity" />
        @endif
    @break

    @case('End date of work')
        {{ $record->serialization_end_date?->isoformat('DD MMM Y') }}
    @break

    @case('Serialization codes request date')
        {{ $record->serialization_codes_request_date?->isoformat('DD MMM Y') }}
    @break

    @case('Serialization codes sent')
        {{ $record->serialization_codes_sent_date?->isoformat('DD MMM Y') }}
    @break

    @case('Serialization report received')
        {{ $record->serialization_report_recieved_date?->isoformat('DD MMM Y') }}
    @break

    @case('Report sent to hub')
        {{ $record->report_sent_to_hub_date?->isoformat('DD MMM Y') }}
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
