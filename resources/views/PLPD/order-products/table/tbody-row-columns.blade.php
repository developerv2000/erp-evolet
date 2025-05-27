@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('plpd.order-products.edit', $record->id)" />
    @break

    @case('BDM')
        <x-misc.ava image="{{ $record->order->manufacturer->bdm->photo_asset_url }}" title="{{ $record->order->manufacturer->bdm->name }}" />
    @break

    @case('Brand Eng')
        {{ $record->process->full_trademark_en }}
    @break

    @case('Brand Rus')
        {{ $record->process->full_trademark_ru }}
    @break

    @case('Order')
        <a class="main-link" href="{{ route('plpd.orders.index', ['id[]' => $record->order_id]) }}"># {{ $record->order_id }}</a>
    @break

    @case('MAH')
        {{ $record->MAH->name }}
    @break

    @case('Quantity')
        <x-tables.partials.td.formatted-price :price="$record->quantity" />
    @break

    @case('Receive date')
        {{ $record->order->receive_date->isoformat('DD MMM Y') }}
    @break

    @case('Manufacturer')
        {{ $record->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->order->country->code }}
    @break

    @case('Sent to BDM')
        {{ $record->order->sent_to_bdm_date?->isoformat('DD MMM Y') }}
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
