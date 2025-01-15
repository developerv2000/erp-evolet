@switch($record->type)
    @case('App\Notifications\ProcessStageUpdatedToContract')
        <div>
            {{ __('Status of process') }}
            <a class="main-link" href="{{ route('processes.index', ['id[]' => $record->data['process_id']]) }}">
                #{{ $record->data['process_id'] }}
            </a>
            {{ __('has been changed to') }} {{ $record->data['status_name'] }}
        </div>
    @break

    @default
        Undefined
@endswitch
