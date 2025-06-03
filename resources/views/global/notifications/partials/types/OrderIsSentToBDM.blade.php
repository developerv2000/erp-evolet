<div>
    <strong>
        {{ __('New order has been received') }}:
    </strong><br>

    {{ __('ID') }}: <a class="main-link" href="{{ route('cmd.orders.index', ['id[]' => $record->data['order_id']]) }}">
        #{{ $record->data['order_id'] }}
    </a><br>
    {{ __('Product') }}: {{ $record->data['full_trademark_en'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['manufacturer'] }}<br>
    {{ __('Country') }}: {{ $record->data['country'] }}<br>
    {{ __('MAH') }}: {{ $record->data['marketing_authorization_holder'] }}<br>
    {{ __('Quantity') }}: {{ $record->data['quantity'] }}<br>
</div>
