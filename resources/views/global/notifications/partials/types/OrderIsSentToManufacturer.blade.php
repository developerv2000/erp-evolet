<div>
    <strong>
        {{ __('Given order has been sent to manufacturer') }}:
    </strong><br>

    {{ __('ID') }}: #{{ $record->data['order_id'] }}<br>
    {{ __('PO №') }}: {{ $record->data['name'] }}<br>
    {{ __('Product') }}: {{ $record->data['full_trademark_en'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['manufacturer'] }}<br>
    {{ __('Country') }}: {{ $record->data['country'] }}<br>
    {{ __('MAH') }}: {{ $record->data['MAH'] }}<br>
</div>
