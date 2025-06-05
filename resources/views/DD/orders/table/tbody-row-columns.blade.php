@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('dd.orders.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Layout approved')
        @if ($record->layout_approved)
            <span class="badge badge--green">{{ __('Approved') }}</span>
        @endif
    @break

    @case('Manufacturer')
        {{ $record->process->product->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->process->searchCountry->code }}
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_en" />
    @break

    @case('MAH')
        {{ $record->process->MAH->name }}
    @break

    @case('Quantity')
        <x-tables.partials.td.formatted-price :price="$record->quantity" />
    @break

    @case('Sent to BDM')
        {{ $record->sent_to_bdm_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO №')
        {{ $record->name }}
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

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Sent to manufacturer')
        {{ $record->sent_to_manufacturer_date->isoformat('DD MMM Y') }}
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

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
