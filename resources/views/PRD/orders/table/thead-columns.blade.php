@switch($column['name'])
    {{-- Links --}}
    @case('ID')
        <x-tables.partials.th.id />
    @break

    @case('PO date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="purchase_date" />
    @break

    @case('PO №')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="name" />
    @break

    @case('Currency')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="currency_id" />
    @break

    {{-- Static text --}}

    @default
        <div title="{{ __($column['name']) }}">{{ __($column['name']) }}</div>
    @break
@endswitch
