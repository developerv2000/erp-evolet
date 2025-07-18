@switch($column['name'])
    {{-- Edit --}}
    @case('Edit')
        <x-tables.partials.th.edit />
    @break

    {{-- Duplicate --}}
    @case('Duplicate')
        <x-misc.material-symbol class="th__iconed-title unselectable" icon="content_copy" title="{{ __('Duplicate') }}" />
    @break

    {{-- Links --}}
    @case('ID')
        <x-tables.partials.th.id />
    @break

    @case('Search country')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="country_id" />
    @break

    @case('Status')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="status_id" />
    @break

    @case('Manufacturer')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="product_manufacturer_name" />
    @break

    @case('Generic')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="product_inn_name" />
    @break

    @case('Form')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="product_form_name" />
    @break

    @case('Dosage')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="product_dosage" />
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

    @case('Increased price')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="increased_price" />
    @break

    @case('Increased price date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="increased_price_date" />
    @break

    @case('Dossier status')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="dossier_status" />
    @break

    @case('Year Cr/Be')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="clinical_trial_year" />
    @break

    @case('Country ich')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="clinical_trial_ich_country" />
    @break

    @case('Down payment 1')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="down_payment_1" />
    @break

    @case('Down payment 2')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="down_payment_2" />
    @break

    @case('Down payment condition')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="down_payment_condition" />
    @break

    @case('Date of forecast')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="forecast_year_1_update_date" />
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

    @case('Responsible update date')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="responsible_people_update_date" />
    @break

    @case('TM Eng')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="trademark_en" />
    @break

    @case('TM Rus')
        <x-tables.partials.th.order-link text="{{ $column['name'] }}" order-by="trademark_ru" />
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
