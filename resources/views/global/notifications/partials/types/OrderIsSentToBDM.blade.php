<div>
    <strong>
        {{ __('New order has been received') }}:
    </strong><br>

    {{ __('ID') }}: <a class="main-link" href="{{ route('cmd.orders.index', ['id[]' => $record->data['order_id']]) }}">
        #{{ $record->data['order_id'] }}
    </a><br>
    {{ __('Products') }}: {{ $record->data['products_count'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['manufacturer'] }}<br>
    {{ __('Country') }}: {{ $record->data['country'] }}<br>
</div>
