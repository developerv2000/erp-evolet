@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('plpd.orders.edit', $record->id)" />
    @break

    @case('BDM')
        <x-misc.ava image="{{ $record->manufacturer->bdm->photo_asset_url }}" title="{{ $record->manufacturer->bdm->name }}" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Receive date')
        {{ $record->receive_date->isoformat('DD MMM Y') }}
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
            :link="route('plpd.order-products.index', ['order_id[]' => $record->id])">
            {{ $record->products_count }} {{ __('Products') }}
        </x-misc.buttoned-link>

        <a class="main-link" href="{{ route('plpd.order-products.create', ['order_id' => $record->id]) }}">
            {{ __('Add product') }}
        </a>
    @break

    @case('Sent to BDM')
        {{ $record->sent_to_bdm_date?->isoformat('DD MMM Y') }}
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
@endswitch
