<div>
    <strong>
        {{ __('New product for order has been received') }}:
    </strong><br>

    {{ __('Product') }}:
    <a class="main-link" href="{{ route('plpd.processes.ready-for-order.index', ['id[]' => $record->data['process_id']]) }}">
        #{{ $record->data['process_id'] }}
    </a>
    {{ $record->data['full_trademark_en'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['manufacturer'] }}<br>
    {{ __('Country') }}: {{ $record->data['country'] }}<br>
    {{ __('MAH') }}: {{ $record->data['marketing_authorization_holder'] }}<br>
</div>
