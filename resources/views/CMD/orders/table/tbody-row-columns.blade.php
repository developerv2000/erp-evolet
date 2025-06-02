@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('cmd.orders.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('BDM')
        <x-misc.ava
            image="{{ $record->process->product->manufacturer->bdm->photo_asset_url }}"
            title="{{ $record->process->product->manufacturer->bdm->name }}" />
    @break

    @case('Brand Eng')
        {{ $record->process->full_trademark_en }}
    @break

    @case('Brand Rus')
        {{ $record->process->full_trademark_ru }}
    @break

    @case('MAH')
        {{ $record->process->MAH->name }}
    @break

    @case('Quantity')
        <x-tables.partials.td.formatted-price :price="$record->quantity" />
    @break

    @case('Receive date')
        {{ $record->receive_date->isoformat('DD MMM Y') }}
    @break

    @case('Manufacturer')
        {{ $record->process->product->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->process->searchCountry->code }}
    @break

    @case('Sent to BDM')
        @if ($record->is_sent_to_bdm)
            {{ $record->sent_to_bdm_date?->isoformat('DD MMM Y') }}
        @else
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="line_end_arrow_notch"
                data-click-action="toggle-orders-is-sent-to-bdm-attribute"
                data-action-type="send"
                data-record-id="{{ $record->id }}">
                {{ __('Send to BDM') }}
            </x-misc.button>
        @endif
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Comments date')
        {{ $record->lastComment?->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
