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

    @case('Payment type')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="payment_type_id" />
    @break

    @case('Sent for payment date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="sent_for_payment_date" />
    @break

    @case('Payment completed')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="payment_completed_date" />
    @break

    @case('Accepted date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="accepted_by_financier_date" />
    @break

    @case('Payment request date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="payment_request_date_by_financier" />
    @break

    @case('Payment date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="payment_date" />
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
