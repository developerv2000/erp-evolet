<div>
    <strong>
        {{ __('Given order has been sent to manufacturer') }}:
    </strong><br>

    {{ __('ID') }}: <a class="main-link" href="{{ route('dd.order-products.index', ['order_id' => $record->data['order_id']]) }}">
        #{{ $record->data['order_id'] }}
    </a><br>
    {{ __('PO №') }}: {{ $record->data['name'] }}<br>
    {{ __('Products') }}: {{ $record->data['products_count'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['manufacturer'] }}<br>
    {{ __('Country') }}: {{ $record->data['country'] }}<br>
</div>
