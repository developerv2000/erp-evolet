@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('plpd.order-products.edit', $record->id)" />
    @break

    @case('Manufacturer')
        {{ $record->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->order->country->code }}
    @break

    @case('Order')
        <a class="main-link" href="{{ route('plpd.orders.index', ['id[]' => $record->order->id]) }}">
            {{ $record->order->title }}
        </a>
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_en" />
    @break

    @case('Brand Rus')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_ru" />
    @break

    @case('MAH')
        {{ $record->process->MAH->name }}
    @break

    @case('Quantity')
        <x-tables.partials.td.formatted-price :price="$record->quantity" />
    @break

    @case('Price')
        {{ $record->price }}
    @break

    @case('Currency')
        {{ $record->order->currency?->name }}
    @break

    @case('Total price')
        {{ $record->total_price }}
    @break

    @case('Status')
        <div class="td__order-status">
            <x-tables.partials.td.order-status-badge :status="$record->status" />
        </div>
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Serialization type')
        {{ $record->serializationType->name }}
    @break

    @case('Production status')
        <x-tables.partials.td.max-lines-limited-text :text="$record->production_status" />
    @break

    @case('Layout approved date')
        {{ $record->layout_approved_date?->isoformat('DD MMM Y') }}
    @break

    @case('Prepayment payment date')
        {{ $record->prepayment_invoice?->payment_date?->isoformat('DD MMM Y') }}
    @break

    @case('Production end date')
        {{ $record->production_end_date?->isoformat('DD MMM Y') }}
    @break

    @case('Final payment request date')
        {{ $record->final_or_full_payment_invoice?->sent_for_payment_date?->isoformat('DD MMM Y') }}
    @break

    @case('Final payment due date')
        {{ $record->final_or_full_payment_invoice?->payment_date?->isoformat('DD MMM Y') }}
    @break

    @case('Ready for shipment')
        {{ $record->readiness_for_shipment_date?->isoformat('DD MMM Y') }}
    @break
@endswitch
