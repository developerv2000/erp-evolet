@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    {{-- Links --}}
    @case('ID')
        <x-tables.partials.th.id />
    @break

    @case('Order')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="order_id" />
    @break

    @case('Sent to BDM')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="order_sent_to_bdm_date" />
    @break

    @case('Sent to manufacturer')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="order_sent_to_manufacturer_date" />
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
