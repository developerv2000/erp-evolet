@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('plpd.orders.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Status')
        <div class="td__order-status">
            @if ($record->status)
                <x-tables.partials.td.order-status-badge :status="$record->status" />
            @endif
        </div>
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
        <a href="{{ route('plpd.order-products.index', ['order_id[]' => $record->id]) }}" class="main-link text-lowercase">
            {{ $record->products_count }} {{ __('Products') }}
        </a>
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Sent to BDM')
        @if ($record->is_sent_to_bdm)
            {{ $record->sent_to_bdm_date->isoformat('DD MMM Y') }}
        @else
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="line_end_arrow_notch"
                data-click-action="toggle-orders-is-sent-to-bdm-attribute"
                data-action-type="send"
                data-record-id="{{ $record->id }}">
                {{ __('Send to BDM') }}
            </x-misc.button>
        @endif
    @break

    @case('PO date')
        {{ $record->purchase_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO №')
        {{ $record->name }}
    @break

    @case('Currency')
        {{ $record->currency?->name }}
    @break

    @case('Sent to confirmation')
        {{ $record->sent_to_confirmation_date?->isoformat('DD MMM Y') }}
    @break

    @case('Confirmation date')
        @if ($record->is_confirmed)
            {{ $record->confirmation_date->isoformat('DD MMM Y') }}
        @elseif ($record->is_sent_to_confirmation)
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="done_all"
                data-click-action="toggle-orders-is-confirmed-attribute"
                data-action-type="confirm"
                data-record-id="{{ $record->id }}">
                {{ __('Confirm') }}
            </x-misc.button>
        @endif
    @break

    @case('Sent to manufacturer')
        {{ $record->sent_to_manufacturer_date?->isoformat('DD MMM Y') }}
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
