@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    {{-- Links --}}
    @case('ID')
        <x-tables.partials.th.id />
    @break

    @case('Layout approved')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="layout_approved_date" />
    @break

    @case('Brand Eng')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="process_id" />
    @break

    @case('Quantity')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="quantity" />
    @break

    @case('Price')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="price" />
    @break

    @case('PO №')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="order_id" />
    @break

    @case('Sent to manufacturer')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="order_sent_to_manufacturer_date" />
    @break

    @case('Layout status')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="new_layout" />
    @break

    @case('Layout sent date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="date_of_sending_new_layout_to_manufacturer" />
    @break

    @case('Print proof receive date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="date_of_receiving_print_proof_from_manufacturer" />
    @break

    @case('Layout approved date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="layout_approved_date" />
    @break

    @case('Date of creation')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="created_at" />
    @break

    @case('Update date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="updated_at" />
    @break

    {{-- Static text --}}

    @default
        <div title="{{ __($column['name']) }}">{{ __($column['name']) }}</div>
    @break
@endswitch
