@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('msd.order-products.serialized-by-manufacturer.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Status')
        <span @class([
            'badge',
            'badge--grey' => $record->serialization_status == App\Models\OrderProduct::STATUS_PRODUCTION_IS_FINISHED_NAME,
            'badge--pink' => $record->serialization_status == App\Models\OrderProduct::STATUS_SERIALIZATION_CODES_REQUESTED_NAME,
            'badge--yellow' => $record->serialization_status == App\Models\OrderProduct::STATUS_SERIALIZATION_CODES_SENT_NAME,
            'badge--orange' => $record->serialization_status == App\Models\OrderProduct::STATUS_SERIALIZATION_REPORT_RECEIVED_NAME,
            'badge--green' => $record->serialization_status == App\Models\OrderProduct::STATUS_REPORT_SENT_TO_HUB_NAME,
        ])>
            {{ $record->serialization_status }}
        </span>
    @break

    @case('Manufacturer')
        {{ $record->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->order->country->code }}
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_en" />
    @break

    @case('Brand Rus')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_ru" />
    @break

    @case('Quantity')
        <x-tables.partials.td.formatted-price :price="$record->quantity" />
    @break

    @case('Production end date')
        {{ $record->order->production_end_date->isoformat('DD MMM Y') }}
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

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break
@endswitch
