@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    {{-- Links --}}
    @case('ID')
        <x-tables.partials.th.id />
    @break

    @case('Receive date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="receive_date" />
    @break

    @case('Quantity')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="quantity" />
    @break

    @case('Sent to BDM')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="sent_to_bdm_date" />
    @break

    @case('PO date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="purchase_date" />
    @break

    @case('PO №')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="name" />
    @break

    @case('Price')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="price" />
    @break

    @case('Currency')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="currency_id" />
    @break

    @case('Sent to confirmation')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="sent_to_confirmation_date" />
    @break

    @case('Confirmation date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="confirmation_date" />
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
