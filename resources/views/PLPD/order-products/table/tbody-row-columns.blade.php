@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('plpd.order-products.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('BDM')
        <x-misc.ava
            image="{{ $record->order->manufacturer->bdm->photo_asset_url }}"
            title="{{ $record->order->manufacturer->bdm->name }}" />
    @break

    @case('Order')
        <a class="main-link" href="{{ route('plpd.orders.index', ['id[]' => $record->order->id]) }}">
            {{ $record->order->title }}
        </a>
    @break

    @case('Status')
        <div class="td__order-status">
            <x-tables.partials.td.order-status-badge :status="$record->order->status" />
        </div>
    @break

    @case('Receive date')
        {{ $record->order->receive_date->isoformat('DD MMM Y') }}
    @break

    @case('Manufacturer')
        {{ $record->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->order->country->code }}
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_en" />
    @break

    @case('Brand Rus')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_ru" />
    @break

    @case('MAH')
        {{ $record->process->MAH->name }}
    @break

    @case('Quantity')
        <x-tables.partials.td.formatted-price :price="$record->quantity" />
    @break

    @case('Serialization type')
        {{ $record->serializationType->name }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Sent to BDM')
        {{ $record->order->sent_to_bdm_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO date')
        {{ $record->order->purchase_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO №')
        {{ $record->order->name }}
    @break

    @case('TM Eng')
        {{ $record->process->trademark_en }}
    @break

    @case('TM Rus')
        {{ $record->process->trademark_ru }}
    @break

    @case('Generic')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->product->inn->name" />
    @break

    @case('Form')
        {{ $record->process->product->form->name }}
    @break

    @case('Dosage')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->product->dosage" />
    @break

    @case('Pack')
        {{ $record->process->product->pack }}
    @break

    @case('Price')
        {{ $record->price }}
    @break

    @case('Total price')
        {{ $record->total_price }}
    @break

    @case('Currency')
        {{ $record->order->currency?->name }}
    @break

    @case('Sent to confirmation')
        {{ $record->order->sent_to_confirmation_date?->isoformat('DD MMM Y') }}
    @break

    @case('Confirmation date')
        {{ $record->confirmation_date?->isoformat('DD MMM Y') }}
    @break

    @case('Sent to manufacturer')
        {{ $record->order->sent_to_manufacturer_date?->isoformat('DD MMM Y') }}
    @break

    @case('Layout status')
        <span @class([
            'badge',
            'badge--yellow' => $record->new_layout,
            'badge--blue' => !$record->new_layout,
        ])>
            {{ $record->new_layout ? __('New') : __('No changes') }}
        </span>
    @break

    @case('Layout sent date')
        {{ $record->date_of_sending_new_layout_to_manufacturer?->isoformat('DD MMM Y') }}
    @break

    @case('Print proof receive date')
        {{ $record->date_of_receiving_print_proof_from_manufacturer?->isoformat('DD MMM Y') }}
    @break

    @case('Box article')
        {{ $record->box_article }}
    @break

    @case('Layout approved date')
        {{ $record->layout_approved_date?->isoformat('DD MMM Y') }}
    @break

    @case('Production status')
        <x-tables.partials.td.max-lines-limited-text :text="$record->production_status" />
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break
@endswitch
