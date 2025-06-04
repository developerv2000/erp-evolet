@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    @case('Search country')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="country_id" />
    @break

    @case('Status')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="status_id" />
    @break

    @case('MAH')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="marketing_authorization_holder_id" />
    @break

    @case('Manufacturer price 1')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="manufacturer_first_offered_price" />
    @break

    @case('Manufacturer price 2')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="manufacturer_followed_offered_price" />
    @break

    @case('Currency')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="currency_id" />
    @break

    @case('Agreed price')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="agreed_price" />
    @break

    @case('Our price 2')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="our_followed_offered_price" />
    @break

    @case('Our price 1')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="our_first_offered_price" />
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

    @case('TM Eng')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="trademark_en" />
    @break

    @case('TM Rus')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="trademark_ru" />
    @break

    {{-- Static text --}}

    @default
        <div title="{{ __($column['name']) }}">{{ __($column['name']) }}</div>
    @break
@endswitch
