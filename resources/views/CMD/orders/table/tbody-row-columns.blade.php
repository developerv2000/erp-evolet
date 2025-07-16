@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('cmd.orders.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('BDM')
        <x-misc.ava
            image="{{ $record->manufacturer->bdm->photo_asset_url }}"
            title="{{ $record->manufacturer->bdm->name }}" />
    @break

    @case('Status')
        <div class="td__order-status">
            <x-tables.partials.td.order-status-badge :status="$record->status" />
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
        <x-misc.buttoned-link
            style="transparent"
            class="button--arrowed-link button--margined-bottom text-lowercase"
            icon="arrow_forward"
            :link="route('cmd.order-products.index', ['order_id' => $record->id])">
            {{ $record->products_count }} {{ __('Products') }}
        </x-misc.buttoned-link>
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Sent to BDM')
        {{ $record->sent_to_bdm_date->isoformat('DD MMM Y') }}
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
        @if ($record->is_sent_to_confirmation)
            {{ $record->sent_to_confirmation_date->isoformat('DD MMM Y') }}
        @elseif($record->name)
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="line_end_arrow_notch"
                data-click-action="toggle-orders-is-sent-to-confirmation-attribute"
                data-action-type="send"
                data-record-id="{{ $record->id }}">
                {{ __('Send to confirmation') }}
            </x-misc.button>
        @endif
    @break

    @case('Confirmation date')
        {{ $record->confirmation_date?->isoformat('DD MMM Y') }}
    @break

    @case('Sent to manufacturer')
        @if ($record->is_sent_to_manufacturer)
            {{ $record->sent_to_manufacturer_date->isoformat('DD MMM Y') }}
        @elseif($record->is_confirmed)
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="line_end_arrow_notch"
                data-click-action="toggle-orders-is-sent-to-manufacturer-attribute"
                data-action-type="send"
                data-record-id="{{ $record->id }}">
                {{ __('Send to manufacturer') }}
            </x-misc.button>
        @endif
    @break

    @case('Expected dispatch date')
        {{ $record->expected_dispatch_date }}
    @break

    @case('Invoices')
        <x-misc.buttoned-link
            style="transparent"
            class="button--arrowed-link button--margined-bottom text-lowercase"
            icon="arrow_forward"
            :link="route('cmd.invoices.index', ['order_id[]' => $record->id])">
            {{ $record->invoices_count }} {{ __('Invoices') }}
        </x-misc.buttoned-link>

        @if ($record->canAttachNewInvoice())
            <a class="main-link" href="{{ route('cmd.invoices.create', ['order_id' => $record->id]) }}">
                {{ __('New') }} <span class="text-lowercase">{{ __('Invoice') }}</span>
            </a>
        @endif
    @break

    @case('Production start date')
        @if ($record->production_is_started)
            {{ $record->production_start_date->isoformat('DD MMM Y') }}
        @elseif($record->is_sent_to_manufacturer)
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="line_end_arrow_notch"
                data-click-action="toggle-orders-production-is-started-attribute"
                data-action-type="start"
                data-record-id="{{ $record->id }}">
                {{ __('Start production process') }}
            </x-misc.button>
        @endif
    @break

    @case('Production end date')
        {{ $record->production_end_date?->isoformat('DD MMM Y') }}
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
