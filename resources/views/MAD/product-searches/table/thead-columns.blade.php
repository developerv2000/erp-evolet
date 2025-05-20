@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    {{-- Links --}}
    @case('ID')
        <x-tables.partials.th.id />
    @break

    @case('Source EU')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="source_eu" />
    @break

    @case('Source IN')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="source_in" />
    @break

    @case('Status')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="status_id" />
    @break

    @case('Country')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="country_id" />
    @break

    @case('Priority')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="priority_id" />
    @break

    @case('Generic')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="inn_id" />
    @break

    @case('Form')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="form_id" />
    @break

    @case('Dosage')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="dosage" />
    @break

    @case('MAH')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="marketing_authorization_holder_id" />
    @break

    @case('Forecast 1 year')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="forecast_year_1" />
    @break

    @case('Forecast 2 year')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="forecast_year_2" />
    @break

    @case('Forecast 3 year')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="forecast_year_3" />
    @break

    @case('Portfolio manager')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="portfolio_manager_id" />
    @break

    @case('Analyst')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="analyst_user_id" />
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
