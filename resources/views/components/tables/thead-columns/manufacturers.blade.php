@props(['column'])

@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    {{-- Links --}}
    @case('BDM')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="bdm_user_id" />
    @break

    @case('Analyst')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="analyst_user_id" />
    @break

    @case('Country')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="country_id" />
    @break

    @case('Manufacturer')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="name" />
    @break

    @case('Category')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="category_id" />
    @break

    @case('Status')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="active" />
    @break

    @case('Important')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="important" />
    @break

    @case('Date of creation')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="created_at" />
    @break

    @case('Update date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="updated_at" />
    @break

    {{-- Static text --}}
    @default
        <div title="{{ $column['name'] }}">{{ $column['name'] }}</div>
    @break
@endswitch
