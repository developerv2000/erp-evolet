@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('cmd.invoices.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Receive date')
        {{ $record->receive_date->isoformat('DD MMM Y') }}
    @break

    @case('Payment type')
        {{ $record->paymentType->name }}
    @break

    @case('Payment completed')
        {{ $record->payment_completed_date?->isoformat('DD MMM Y') }}
    @break

    @case('PDF')
        <a class="main-link" href="{{ $record->pdf_asset_url }}" target="_blank">
            {{ $record->pdf }}
        </a>
    @break

    @case('Sent for payment date')
        @if ($record->is_sent_for_payment)
            {{ $record->sent_for_payment_date->isoformat('DD MMM Y') }}
        @else
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="line_end_arrow_notch"
                data-click-action="toggle-invoices-is-sent-for-payment-attribute"
                data-action-type="send"
                data-record-id="{{ $record->id }}">
                {{ __('Send for payment') }}
            </x-misc.button>
        @endif
    @break

    @case('Order')
        <a class="main-link" href="{{ route('cmd.orders.index', ['id[]' => $record->order_id]) }}">
            {{ $record->order->title }}
        </a>
    @break

    @case('Manufacturer')
        {{ $record->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->order->country->code }}
    @break

    @case('Accepted date')
        {{ $record->accepted_by_financier_date?->isoformat('DD MMM Y') }}
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
