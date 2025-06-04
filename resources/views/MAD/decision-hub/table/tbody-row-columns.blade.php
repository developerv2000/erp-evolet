@switch($column['name'])
    @case('Status date')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->statusHistory->last()->start_date->isoformat('DD MMM Y')" />
        </td>
    @break

    @case('Search country')
        <td>
            {{ $record->searchCountry->code }}
        </td>
    @break

    @case('Status')
        <td>
            {{ $record->status->name }}
        </td>
    @break

    @case('Status An*')
        <td>
            {{ $record->status->generalStatus->name_for_analysts }}
        </td>
    @break

    @case('Manufacturer')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->product->manufacturer->name" />
        </td>
    @break

    @case('Manufacturer country')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->product->manufacturer->country->name" />
        </td>
    @break

    @case('BDM')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->product->manufacturer->bdm->name" />
        </td>
    @break

    @case('Analyst')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->product->manufacturer->analyst->name" />
        </td>
    @break

    @case('Generic')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->product->inn->name" />
        </td>
    @break

    @case('Form')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->product->form->name" />
        </td>
    @break

    @case('Dosage')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->product->dosage" />
        </td>
    @break

    @case('Pack')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->product->pack" />
        </td>
    @break

    @case('MAH')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->MAH?->name" />
        </td>
    @break

    @case('Last comment')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
        </td>
    @break

    @case('Manufacturer price 1')
        <td class="backgrounded-text--4 text-right">
            @if ($record->manufacturer_first_offered_price)
                {{ round($record->manufacturer_first_offered_price, 2) }}
            @endif
        </td>
    @break

    @case('Manufacturer price 2')
        <td class="backgrounded-text--4 text-right">
            @if ($record->manufacturer_followed_offered_price)
                {{ round($record->manufacturer_followed_offered_price, 2) }}
            @endif
        </td>
    @break

    @case('Currency')
        <td class="backgrounded-text--4 text-right">
            {{ $record->currency?->name }}
        </td>
    @break

    @case('Price in USD')
        <td class="backgrounded-text--4 text-center">
            @if ($record->manufacturer_offered_price_in_usd)
                <strong>{{ round($record->manufacturer_offered_price_in_usd, 2) }}</strong>
            @endif
        </td>
    @break

    @case('Agreed price')
        <td class="backgrounded-text--4 text-center">
            @if ($record->agreed_price)
                <strong>{{ round($record->agreed_price, 2) }}</strong>
            @endif
        </td>
    @break

    @case('Our price 2')
        <td class="backgrounded-text--4 text-right">
            @if ($record->our_followed_offered_price)
                {{ round($record->our_followed_offered_price, 2) }}
            @endif
        </td>
    @break

    @case('Our price 1')
        <td class="backgrounded-text--4 text-right">
            @if ($record->our_first_offered_price)
                {{ round($record->our_first_offered_price, 2) }}
            @endif
        </td>
    @break

    @case('Shelf life')
        <td class="text-right">
            {{ $record->product->shelfLife->name }}
        </td>
    @break

    @case('MOQ')
        <td class="text-right">
            @if ($record->product->moq)
                <x-tables.partials.td.formatted-price :price="$record->product->moq" />
            @endif
        </td>
    @break

    @case('Forecast 1 year')
        <td class="text-right">
            @if ($record->forecast_year_1)
                <x-tables.partials.td.formatted-price :price="$record->forecast_year_1" />
            @endif
        </td>
    @break

    @case('Forecast 2 year')
        <td class="text-right">
            @if ($record->forecast_year_2)
                <x-tables.partials.td.formatted-price :price="$record->forecast_year_2" />
            @endif
        </td>
    @break

    @case('Forecast 3 year')
        <td class="text-right">
            @if ($record->forecast_year_3)
                <x-tables.partials.td.formatted-price :price="$record->forecast_year_3" />
            @endif
        </td>
    @break

    @case('TM Eng')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->trademark_en" />
        </td>
    @break

    @case('TM Rus')
        <td>
            <x-tables.partials.td.max-lines-limited-text :text="$record->trademark_ru" />
        </td>
    @break

@endswitch
