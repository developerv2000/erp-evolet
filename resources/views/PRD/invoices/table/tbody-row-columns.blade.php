@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('prd.invoices.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Receive date')
        {{ $record->receive_date->isoformat('DD MMM Y') }}
    @break

    @case('Invoice type')
        {{ $record->type->name }}
    @break

    @case('Payment type')
        {{ $record->paymentType->name }}
    @break

    @case('Payment completed')
        @if ($record->payment_is_completed)
            {{ $record->payment_completed_date->isoformat('DD MMM Y') }}
        @elseif($record->payment_date && $record->payment_confirmation_document)
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="done_all"
                data-click-action="toggle-invoices-payment-is-completed-attribute"
                data-action-type="complete"
                data-record-id="{{ $record->id }}">
                {{ __('Complete') }}
            </x-misc.button>
        @endif
    @break

    @case('PDF')
        <a class="main-link" href="{{ $record->pdf_asset_url }}" target="_blank">
            {{ $record->pdf }}
        </a>
    @break

    @case('Sent for payment date')
        {{ $record->sent_for_payment_date->isoformat('DD MMM Y') }}
    @break

    @case('Order')
        <a class="main-link" href="{{ route('prd.orders.index', ['id[]' => $record->order_id]) }}">
            {{ $record->order->title }}
        </a>
    @break

    @case('Payment company')
        {{ $record->payment_company }}
    @break

    @case('Manufacturer')
        {{ $record->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->order->country->code }}
    @break

    @case('Accepted date')
        @if ($record->is_accepted_by_financier)
            {{ $record->accepted_by_financier_date->isoformat('DD MMM Y') }}
        @else
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="done_all"
                data-click-action="toggle-invoices-is-accepted-by-financier-attribute"
                data-action-type="accept"
                data-record-id="{{ $record->id }}">
                {{ __('Accept') }}
            </x-misc.button>
        @endif
    @break

    @case('Payment request date')
        {{ $record->payment_request_date_by_financier?->isoformat('DD MMM Y') }}
    @break

    @case('Payment date')
        {{ $record->payment_date?->isoformat('DD MMM Y') }}
    @break

    @case('Invoice №')
        {{ $record->number }}
    @break

    @case('SWIFT')
        @if ($record->payment_confirmation_document)
            <a class="main-link" href="{{ $record->payment_confirmation_document_asset_url }}" target="_blank">
                {{ $record->payment_confirmation_document }}
            </a>
        @endif
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
