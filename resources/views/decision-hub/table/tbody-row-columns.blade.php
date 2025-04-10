@switch($column['name'])
    @case('Status date')
        {{ $record->statusHistory->last()->start_date->isoformat('DD MMM Y') }}
    @break

    @case('Search country')
        {{ $record->searchCountry->code }}
    @break

    @case('Status')
        {{ $record->status->name }}
    @break

    @case('Status An*')
        {{ $record->status->generalStatus->name_for_analysts }}
    @break

    @case('Manufacturer')
        {{ $record->product->manufacturer->name }}
    @break

    @case('Manufacturer country')
        {{ $record->product->manufacturer->country->name }}
    @break

    @case('BDM')
        {{ $record->product->manufacturer->bdm->name }}
    @break

    @case('Analyst')
        {{ $record->product->manufacturer->analyst->name }}
    @break

    @case('Generic')
        <x-tables.partials.td.max-lines-limited-text :text="$record->product->inn->name" />
    @break

    @case('Form')
        {{ $record->product->form->name }}
    @break

    @case('Dosage')
        <x-tables.partials.td.max-lines-limited-text :text="$record->product->dosage" />
    @break

    @case('Pack')
        {{ $record->product->pack }}
    @break

    @case('MAH')
        {{ $record->MAH?->name }}
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Manufacturer price 1')
        {{ $record->manufacturer_first_offered_price }}
    @break

    @case('Manufacturer price 2')
        {{ $record->manufacturer_followed_offered_price }}
    @break

    @case('Currency')
        {{ $record->currency?->name }}
    @break

    @case('Price in USD')
        {{ $record->manufacturer_offered_price_in_usd }}
    @break

    @case('Agreed price')
        {{ $record->agreed_price }}
    @break

    @case('Our price 2')
        {{ $record->our_followed_offered_price }}
    @break

    @case('Our price 1')
        {{ $record->our_first_offered_price }}
    @break

    @case('Shelf life')
        {{ $record->product->shelfLife->name }}
    @break

    @case('MOQ')
        @if ($record->product->moq)
            <x-tables.partials.td.formatted-price :price="$record->product->moq" />
        @endif
    @break

    @case('Forecast 1 year')
        @if ($record->forecast_year_1)
            <x-tables.partials.td.formatted-price :price="$record->forecast_year_1" />
        @endif
    @break

    @case('Forecast 2 year')
        @if ($record->forecast_year_2)
            <x-tables.partials.td.formatted-price :price="$record->forecast_year_2" />
        @endif
    @break

    @case('Forecast 3 year')
        @if ($record->forecast_year_3)
            <x-tables.partials.td.formatted-price :price="$record->forecast_year_3" />
        @endif
    @break

    @case('Brand Eng')
        {{ $record->trademark_en }}
    @break

    @case('Brand Rus')
        {{ $record->trademark_ru }}
    @break

@endswitch
