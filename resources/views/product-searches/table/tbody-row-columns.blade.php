@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('product-searches.edit', $record->id)" />
    @break

    @case('Source EU')
        @if ($record->source_eu)
            EU
        @endif
    @break

    @case('Source IN')
        @if ($record->source_in)
            IN
        @endif
    @break

    @case('Portfolio manager')
        {{ $record->portfolioManager?->name }}
    @break

    @case('Country')
        {{ $record->country->code }}
    @break

    @case('Status')
        {{ $record->status->name }}
    @break

    @case('Priority')
        <span @class([
            'badge',
            'badge--red' => $record->priority->name == 'A',
            'badge--green' => $record->priority->name == 'B',
            'badge--yellow' => $record->priority->name == 'C',
        ])>
            {{ $record->priority->name }}
        </span>
    @break

    @case('Matched VPS')
        @foreach ($record->matched_processes as $process)
            <a class="main-link" href="{{ route('processes.index', ['id[]' => $process->id]) }}">
                # {{ $process->id }} - {{ $process->status->name }}
            </a><br>
        @endforeach
    @break

    @case('Matched IVP')
        <a class="main-link" href="{{ route('products.index', ['inn_id[]' => $record->inn_id, 'form_id[]' => $record->form_id]) }}">
            {{ $record->matched_products_count }} {{ __('products') }}
        </a><br>
    @break

    @case('Generic')
        <x-tables.partials.td.max-lines-limited-text :text="$record->inn->name" />
    @break

    @case('Form')
        {{ $record->form->name }}
    @break

    @case('Basic form')
        {{ $record->form->parent_name }}
    @break

    @case('Dosage')
        <x-tables.partials.td.max-lines-limited-text :text="$record->dosage" />
    @break

    @case('Pack')
        {{ $record->pack }}
    @break

    @case('MAH')
        {{ $record->MAH->name }}
    @break

    @case('Additional search info')
        <x-tables.partials.td.max-lines-limited-text :text="$record->additional_search_information" />
    @break

    @case('Additional search countries')
        @foreach ($record->additionalSearchCountries as $country)
            {{ $country->code }}<br>
        @endforeach
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

    @case('Analyst')
        @if ($record->analyst)
            <x-misc.ava image="{{ $record->analyst->photo_asset_url }}" title="{{ $record->analyst->name }}" />
        @endif
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

    @case('ID')
        {{ $record->id }}
    @break

@endswitch
