@switch($record->type)
    @case('App\Notifications\ProcessStageUpdatedToContract')
        <div>
            {{ __('Status of process') }}
            <a class="main-link" href="{{ route('mad.processes.index', ['id[]' => $record->data['process_id']]) }}">
                #{{ $record->data['process_id'] }}
            </a>
            {{ __('has been updated to') }} {{ $record->data['status_name'] }}
        </div>
    @break

    @case('App\Notifications\ProcessMarkedAsReadyForOrder')
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
    @break

    @case('App\Notifications\ProcessUnmarkedAsReadyForOrder')
        <div>
            <strong>
                {{ __('Product for order has been removed') }}:
            </strong><br>

            {{ __('Product') }}: #{{ $record->data['process_id'] }} {{ $record->data['full_trademark_en'] }}<br>
            {{ __('Manufacturer') }}: {{ $record->data['manufacturer'] }}<br>
            {{ __('Country') }}: {{ $record->data['country'] }}<br>
            {{ __('MAH') }}: {{ $record->data['marketing_authorization_holder'] }}<br>
        </div>
    @break

    @default
        Undefined
@endswitch
