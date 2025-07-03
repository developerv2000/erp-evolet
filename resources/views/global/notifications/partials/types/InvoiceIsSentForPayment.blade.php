{{-- This notification can be sent to users of different departments --}}
@php
    // Detect valid record page for different departments
    $user = auth()->user();
    $departmentRoute = '#';

    // PRD
    if ($user->can('view-PRD-invoices')) {
        $departmentRoute = route('prd.invoices.index', ['id[]' => $record->data['invoice_id']]);
    }
    // PLPD
    elseif ($user->can('view-PLPD-invoices')) {
        $departmentRoute = route('plpd.invoices.index', ['id[]' => $record->data['invoice_id']]);
    }
@endphp

<div>
    <strong>
        {{ __('Below invoice has been sent for payment') }}:
    </strong><br>

    {{ __('ID') }}:
    <a class="main-link" href="{{ $departmentRoute }}">
        #{{ $record->data['invoice_id'] }}
    </a><br>

    {{ __('PO №') }}: {{ $record->data['order_name'] }}<br>
    {{ __('Products') }}: {{ $record->data['order_products_count'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['order_manufacturer_name'] }}<br>
    {{ __('Country') }}: {{ $record->data['order_country_code'] }}<br>
</div>







<div>
    <strong>
        {{ __('New invoice for payment has been received') }}:
    </strong><br>

    {{ __('ID') }}: <a class="main-link" href="{{ route('prd.invoices.index', ['id[]' => $record->data['invoice_id']]) }}">
        #{{ $record->data['invoice_id'] }}
    </a><br>
    {{ __('PO №') }}: {{ $record->data['name'] }}<br>
    {{ __('PO date') }}: {{ $record->data['purchase_date'] }}<br>
    {{ __('Products') }}: {{ $record->data['products_count'] }}<br>
</div>
