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

    @case('PDF')
        <a class="main-link" href="{{ $record->pdf_asset_url }}" target="_blank">
            {{ $record->pdf }}
        </a>
    @break

    @case('Sent for payment date')
        {{ $record->sent_for_payment_date?->isoformat('DD MMM Y') }}
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

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break
@endswitch
