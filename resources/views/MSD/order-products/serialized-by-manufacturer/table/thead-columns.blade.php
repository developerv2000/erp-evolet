@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    {{-- Links --}}
    @case('ID')
        <x-tables.partials.th.id />
    @break

    @case('Production end date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="order_production_end_date" />
    @break

    @case('Serialization codes request date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="serialization_codes_request_date" />
    @break

    @case('Serialization codes sent')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="serialization_codes_sent_date" />
    @break

    @case('Serialization report received')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="serialization_report_recieved_date" />
    @break

    @case('Report sent to hub')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="report_sent_to_hub_date" />
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
