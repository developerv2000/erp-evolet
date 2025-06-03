<div>
    <strong>
        {{ __('Product for order has been removed') }}:
    </strong><br>

    {{ __('Product') }}: #{{ $record->data['process_id'] }} {{ $record->data['full_trademark_en'] }}<br>
    {{ __('Manufacturer') }}: {{ $record->data['manufacturer'] }}<br>
    {{ __('Country') }}: {{ $record->data['country'] }}<br>
    {{ __('MAH') }}: {{ $record->data['marketing_authorization_holder'] }}<br>
</div>
