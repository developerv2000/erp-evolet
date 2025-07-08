{{-- This notification can be sent to users of different departments --}}
@php
    // Detect valid record page for different departments
    $user = auth()->user();
    $departmentRoute = '#';

    // CMD
    if ($user->can('view-CMD-invoices')) {
        $departmentRoute = route('cmd.invoices.index', ['id[]' => $record->data['invoice_id']]);
    }
    // PLPD
    elseif ($user->can('view-PLPD-invoices')) {
        $departmentRoute = route('plpd.invoices.index', ['id[]' => $record->data['invoice_id']]);
    }
@endphp

<div>
    <strong>
        {{ __('Below invoice payment has been completed') }}:
    </strong><br>

    {{ __('ID') }}:
    <a class="main-link" href="{{ $departmentRoute }}">
        #{{ $record->data['invoice_id'] }}
    </a><br>

    {{ __('PO №') }}: {{ $record->data['order_name'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['order_manufacturer_name'] }}<br>
    {{ __('Country') }}: {{ $record->data['order_country_code'] }}<br>
    {{ __('Invoice №') }}: {{ $record->data['invoice_number'] }}<br>
    {{ __('Payment date') }}: {{ $record->data['payment_date'] }}<br>

</div>
