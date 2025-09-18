@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('warehouse.product-batches.edit', $record->id)" />
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

    @case('Manufacturer')
        {{ $record->product->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->product->order->country->code }}
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->product->process->full_trademark_en" />
    @break

    @case('Brand Rus')
        <x-tables.partials.td.max-lines-limited-text :text="$record->product->process->full_trademark_ru" />
    @break

    @case('MAH')
        {{ $record->product->process->MAH->name }}
    @break

    @case('Factual quantity')
        <x-tables.partials.td.formatted-price :price="$record->product->factual_quantity" />
    @break

    @case('PO date')
        {{ $record->product->order->purchase_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO №')
        {{ $record->product->order->name }}
    @break

    @case('Arrived at warehouse')
        {{ $record->product->warehouse_arrival_date->isoformat('DD MMM Y') }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
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
