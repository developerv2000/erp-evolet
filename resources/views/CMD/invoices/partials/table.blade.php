<x-tables.template.main-template :records="$records" :includePagination="false">
    {{-- thead titles --}}
    <x-slot:thead-rows>
        <tr>
            <th width="50"><x-tables.partials.th.select-all /></th>

            <th>{{ __('Receive date') }}</th>
            <th>{{ __('Payment type') }}</th>
            <th>{{ __('File') }}</th>
            <th>{{ __('Order') }}</th>
            <th>{{ __('Sent for payment date') }}</th>
        </tr>
    </x-slot:thead-rows>

    {{-- tbody rows --}}
    <x-slot:tbody-rows>
        @foreach ($records as $record)
            <tr>
                <td><x-tables.partials.td.checkbox :value="$record->id" /></td>
                <td>{{ $record->receive_date->isoformat('DD MMM Y') }}</td>
                <td>{{ $record->paymentType->name }}</td>

                <td>
                    <a class="main-link" href="{{ $record->pdf_asset_url }}" target="_blank">
                        {{ $record->filename }}
                    </a>
                </td>

                <td>
                    <a class="main-link" href="{{ route('cmd.orders.index', ['id[]' => $record->order_id]) }}">
                        {{ $record->order->title }}
                    </a>
                </td>

                <td>{{ $record->sent_for_payment_date?->isoformat('DD MMM Y') }}</td>
            </tr>
        @endforeach
    </x-slot:tbody-rows>
</x-tables.template.main-template>
