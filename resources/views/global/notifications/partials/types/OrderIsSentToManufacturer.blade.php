{{-- This notification can be sent to users of different departments --}}
@php
    // Detect valid record page for different departments
    $user = auth()->user();
    $departmentRoute = '#';

    // PLPD
    if ($user->can('view-PLPD-orders')) {
        $departmentRoute = route('plpd.orders.index', ['id[]' => $record->data['order_id']]);
    }
    // DD
    elseif ($user->can('view-DD-order-products')) {
        $departmentRoute = route('dd.order-products.index', ['order_name[]' => $record->data['name']]);
    }
@endphp

<div>
    <strong>
        {{ __('Below order has been sent to manufacturer') }}:
    </strong><br>

    {{ __('ID') }}:
    <a class="main-link" href="{{ $departmentRoute }}">
        #{{ $record->data['order_id'] }}
    </a><br>

    {{ __('PO №') }}: {{ $record->data['name'] }}<br>
    {{ __('Products') }}: {{ $record->data['products_count'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['manufacturer'] }}<br>
    {{ __('Country') }}: {{ $record->data['country'] }}<br>
</div>
