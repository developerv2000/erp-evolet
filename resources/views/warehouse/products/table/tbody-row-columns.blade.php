@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('warehouse.products.edit', $record->id)" />
    @break

    @case('Status')
        <div class="td__order-status">
            <x-tables.partials.td.order-status-badge :status="$record->status" />
        </div>
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

    @case('MAH')
        {{ $record->process->MAH->name }}
    @break

    @case('Quantity')
        <x-tables.partials.td.formatted-price :price="$record->quantity" />
    @break

    @case('PO date')
        {{ $record->order->purchase_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO №')
        {{ $record->order->name }}
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

    @case('Arrived at warehouse')
        {{ $record->warehouse_arrival_date->isoformat('DD MMM Y') }}
    @break

    @case('Original invoice')
        {{ $record->warehouse_invoice_number }}
    @break

    @case('Seller')
        {{ $record->payerCompany?->name }}
    @break

    @case('Customs code')
        {{ $record->customs_code }}
    @break

    @case('Factual quantity')
        {{ $record->factual_quantity }}
    @break

    @case('Packs in boxes')
        {{ $record->full_packs_in_boxes }}
    @break

    @case('Packs in box')
        {{ $record->part_packs_in_box }}
    @break

    @case('Defects quantity')
        {{ $record->defects_quantity }}
    @break

    @case('Batches')
        Add
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break
@endswitch
