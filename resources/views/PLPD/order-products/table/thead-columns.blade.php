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

    @case('Brand Eng')
    @case('Brand Rus')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="process_id" />
    @break

    @case('MAH')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="marketing_authorization_holder_id" />
    @break

    @case('Quantity')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="quantity" />
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
