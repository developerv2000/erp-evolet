<div>
    <strong>
        {{ __('Order has been confirmed') }}:
    </strong><br>

    {{ __('ID') }}: <a class="main-link" href="{{ route('cmd.orders.index', ['id[]' => $record->data['order_id']]) }}">
        #{{ $record->data['order_id'] }}
    </a><br>
    {{ __('PO №') }}: {{ $record->data['name'] }}<br>
    {{ __('PO date') }}: {{ $record->data['purchase_date'] }}<br>
    {{ __('Products') }}: {{ $record->data['products_count'] }}<br>
</div>
