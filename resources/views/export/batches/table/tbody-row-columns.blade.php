@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('export.batches.edit', $record->id)" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Assemblage №')
        @foreach ($record->assemblages as $assemblage)
            <a href="{{ route('export.assemblages.index', ['id[]' => $assemblage->id]) }}" class="main-link">
                @if ($loop->count > 1)
                    {{ $loop->iteration }}.
                @endif {{ $assemblage->number }} <br>
            </a>
        @endforeach
    @break

    @case('Assemblage date')
        @foreach ($record->assemblages as $assemblage)
            @if ($loop->count > 1)
                {{ $loop->iteration }}.
            @endif {{ $assemblage->assemblage_date?->isoformat('DD MMM Y') }} <br>
        @endforeach
    @break

    @case('Method of shipment')
        @foreach ($record->assemblages as $assemblage)
            @if ($loop->count > 1)
                {{ $loop->iteration }}.
            @endif {{ $assemblage->shipmentType->name }} <br>
        @endforeach
    @break

    @case('Country')
        @foreach ($record->assemblages as $assemblage)
            @if ($loop->count > 1)
                {{ $loop->iteration }}.
            @endif {{ $assemblage->country->code }} <br>
        @endforeach
    @break

    @case('Series')
        <a href="{{ route('warehouse.product-batches.index', ['id[]' => $record->id]) }}" class="main-link">
            {{ $record->series }}
        </a>
    @break

    @case('Quantity for assembly')
        @foreach ($record->assemblages as $assemblage)
            @if ($loop->count > 1)
                {{ $loop->iteration }}.
            @endif {{ $assemblage->pivot->quantity_for_assembly }} <br>
        @endforeach
    @break

    @case('Batch quantity')
        {{ __('Total') }}: {{ $record->quantity }} <br>
        {{ __('Marked') }}: {{ $record->factual_quantity ?: 0 }} <br>
        {{ __('Defects') }}: {{ $record->defects_quantity ?: 0 }} <br>
        {{ __('Assembled') }}: {{ $record->sum_of_assemblages_quantity ?: 0 }} <br>
        {{ __('Remaining') }}: {{ $record->remaining_quantity ?: 0 }}
    @break

    @case('Additional comment')
        @foreach ($record->assemblages as $assemblage)
            @if ($loop->count > 1)
                {{ $loop->iteration }}.
            @endif {{ $assemblage->pivot->additional_comment }} <br>
        @endforeach
    @break

    @case('Manufacturer')
        {{ $record->product->order->manufacturer->name }}
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->product->process->full_trademark_en" />
    @break

    @case('MAH')
        {{ $record->product->process->MAH->name }}
    @break

    @case('Manufacturing date')
        {{ $record->manufacturing_date?->isoformat('DD MMM Y') }}
    @break

    @case('Expiration date')
        {{ $record->expiration_date?->isoformat('DD MMM Y') }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
