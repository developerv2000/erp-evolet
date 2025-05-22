@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('mad.processes.edit', $record->id)" />
    @break

    @case('Duplicate')
        <x-misc.buttoned-link
            class="td__duplicate"
            style="transparent"
            :link="route('mad.processes.duplication', $record->id)"
            icon="content_copy"
            title="{{ __('Duplicate') }}" />
    @break

    @case('Status date')
        {{ $record->statusHistory->last()->start_date->isoformat('DD MMM Y') }}
    @break

    @case('5Кк')
        @if ($record->isReadyForASPContract() || $record->contracted_in_asp)
            <input class="checkbox td__checkbox" type="checkbox" data-on-toggle="toggle-mad-process-contracted-in-asp-boolean" data-process-id={{ $record->id }} @checked($record->contracted_in_asp)>
        @endif
    @break

    @case('7НПР')
        @if ($record->isReadyForASPRegistration() || $record->registered_in_asp)
            <input class="checkbox td__checkbox" type="checkbox" data-on-toggle="toggle-mad-process-registered-in-asp-boolean" data-process-id={{ $record->id }} @checked($record->registered_in_asp)>
        @endif
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

    @case('General status')
        {{ $record->status->generalStatus->name }}
    @break

    @case('Manufacturer category')
        <span @class([
            'badge',
            'badge--blue' => $record->product->manufacturer->category->name == 'УДС',
            'badge--yellow' => $record->product->manufacturer->category->name == 'НПП',
        ])>
            {{ $record->product->manufacturer->category->name }}
        </span>
    @break

    @case('Manufacturer')
        {{ $record->product->manufacturer->name }}
    @break

    @case('Manufacturer country')
        {{ $record->product->manufacturer->country->name }}
    @break

    @case('BDM')
        <x-misc.ava
            image="{{ $record->product->manufacturer->bdm->photo_asset_url }}"
            title="{{ $record->product->manufacturer->bdm->name }}" />
    @break

    @case('Analyst')
        <x-misc.ava
            image="{{ $record->product->manufacturer->analyst->photo_asset_url }}"
            title="{{ $record->product->manufacturer->analyst->name }}" />
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

    @case('ATX')
        {{ $record->product->atx?->name }}
    @break

    @case('Our ATX')
        {{ $record->product->atx?->short_name }}
    @break

    @case('MAH')
        {{ $record->MAH?->name }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Comments date')
        {{ $record->lastComment?->created_at->isoformat('DD MMM Y') }}
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

    @case('Increased price')
        {{ $record->increased_price }}
    @break

    @case('Increased price %')
        @if ($record->increased_price_percentage)
            {{ $record->increased_price_percentage }} %
        @endif
    @break

    @case('Increased price date')
        {{ $record->increased_price_date?->isoformat('DD MMM Y') }}
    @break

    @case('Shelf life')
        {{ $record->product->shelfLife->name }}
    @break

    @case('MOQ')
        @if ($record->product->moq)
            <x-tables.partials.td.formatted-price :price="$record->product->moq" />
        @endif
    @break

    @case('Dossier status')
        {{ $record->dossier_status }}
    @break

    @case('Year Cr/Be')
        <x-tables.partials.td.max-lines-limited-text :text="$record->clinical_trial_year" />
    @break

    @case('Countries Cr/Be')
        @foreach ($record->clinicalTrialCountries as $country)
            {{ $country->name }}<br>
        @endforeach
    @break

    @case('Country ich')
        {{ $record->clinical_trial_ich_country }}
    @break

    @case('Zones')
        @foreach ($record->product->zones as $zone)
            {{ $zone->name }} <br>
        @endforeach
    @break

    @case('Down payment 1')
        {{ $record->down_payment_1 }}
    @break

    @case('Down payment 2')
        {{ $record->down_payment_2 }}
    @break

    @case('Down payment condition')
        {{ $record->down_payment_condition }}
    @break

    @case('Date of forecast')
        {{ $record->forecast_year_1_update_date?->isoformat('DD MMM Y') }}
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

    @case('Responsible')
        {{ $record->responsiblePerson->name }}
    @break

    @case('Responsible update date')
        {{ $record->responsible_people_update_date?->isoformat('DD MMM Y') }}
    @break

    @case('Days have passed')
        {{ $record->days_past }}
    @break

    @case('Brand Eng')
        {{ $record->trademark_en }}
    @break

    @case('Brand Rus')
        {{ $record->trademark_ru }}
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

    @case('Product class')
        <span class="badge badge--green">{{ $record->product->class->name }}</span>
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('History')
        <x-tables.partials.td.edit :link="route('mad.processes.status-history.index', $record->id)" />
    @break

    @case('ВП')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 0, 'statusStage' => 1])
    @break

    @case('ПО')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 1, 'statusStage' => 2])
    @break

    @case('АЦ')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 2, 'statusStage' => 3])
    @break

    @case('СЦ')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 3, 'statusStage' => 4])
    @break

    @case('Кк')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 4, 'statusStage' => 5])
    @break

    @case('КД')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 5, 'statusStage' => 6])
    @break

    @case('НПР')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 6, 'statusStage' => 7])
    @break

    @case('Р')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 7, 'statusStage' => 8])
    @break

    @case('Зя')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 8, 'statusStage' => 9])
    @break

    @case('Отмена')
        @include('MAD.processes.table.general-status-period', ['arrayKey' => 9, 'statusStage' => 10])
    @break

@endswitch
