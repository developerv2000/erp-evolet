@switch($column['name'])
    @case('ID')
        {{ $record->id }}
    @break

    @case('PO date')
        {{ $record->purchase_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO №')
        {{ $record->name }}
    @break

    @case('Manufacturer')
        {{ $record->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->country->code }}
    @break

    @case('Products')
        <x-misc.buttoned-link
            style="transparent"
            class="button--arrowed-link button--margined-bottom text-lowercase"
            icon="arrow_forward"
            :link="route('prd.order-products.index', ['order_id' => $record->id])">
            {{ $record->products_count }} {{ __('Products') }}
        </x-misc.buttoned-link>
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Currency')
        {{ $record->currency?->name }}
    @break

    @case('Invoices')
        <x-misc.buttoned-link
            style="transparent"
            class="button--arrowed-link button--margined-bottom text-lowercase"
            icon="arrow_forward"
            :link="route('prd.invoices.index', ['order_id[]' => $record->id])">
            {{ $record->invoices_count }} {{ __('Invoices') }}
        </x-misc.buttoned-link>
    @break
@endswitch
