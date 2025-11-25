@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('warehouse.product-batches.edit', $record->id)" />
    @break

    @case('Series')
        {{ $record->series }}
    @break

    @case('Manufacturing date')
        {{ $record->manufacturing_date?->isoformat('DD MMM Y') }}
    @break

    @case('Expiration date')
        {{ $record->expiration_date?->isoformat('DD MMM Y') }}
    @break

    @case('Quantity')
        {{ __('Total') }}: {{ $record->quantity }} <br>
        {{ __('Marked') }}: {{ $record->factual_quantity ?: 0 }} <br>
        {{ __('Defects') }}: {{ $record->defects_quantity ?: 0 }} <br>
        {{ __('Assembled') }}: {{ $record->sum_of_assemblages_quantity ?: 0 }} <br>
        {{ __('Remaining') }}: {{ $record->remaining_quantity ?: 0 }}
    @break

    @case('Manufacturer')
        {{ $record->product->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->product->order->country->code }}
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->product->process->full_trademark_en" />
    @break

    @case('Brand Rus')
        <x-tables.partials.td.max-lines-limited-text :text="$record->product->process->full_trademark_ru" />
    @break

    @case('MAH')
        {{ $record->product->process->MAH->name }}
    @break

    @case('Factual product quantity')
        <a class="main-link" href="{{ route('warehouse.products.index', ['id[]' => $record->order_product_id]) }}">
            <x-tables.partials.td.formatted-price :price="$record->product->factual_quantity" />
        </a>
    @break

    @case('PO date')
        {{ $record->product->order->purchase_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO №')
        {{ $record->product->order->name }}
    @break

    @case('Arrived at warehouse')
        {{ $record->product->warehouse_arrival_date->isoformat('DD MMM Y') }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Serialization request date')
        @if ($record->product->serializationType->name == 'Рига')
            @if ($record->serialization_requested)
                {{ $record->serialization_request_date->isoformat('DD MMM Y') }}
            @else
                <form action="{{ route('warehouse.product-batches.request-serialization', $record->id) }}" method="POST">
                    @csrf

                    <x-misc.button
                        style="transparent"
                        class="button--arrowed-link button--margined-bottom"
                        icon="line_end_arrow_notch">
                        {{ __('Request') }}
                    </x-misc.button>
                </form>
            @endif
        @else
            {{ __('Not serialized in Riga') }}
        @endif
    @break

    @case('Number of full boxes')
        {{ $record->number_of_full_boxes }}
    @break

    @case('Number of packages in full box')
        {{ $record->number_of_packages_in_full_box }}
    @break

    @case('Number of incomplete boxes')
        {{ $record->number_of_incomplete_boxes }}
    @break

    @case('Number of packages in incomplete box')
        {{ $record->number_of_packages_in_incomplete_box }}
    @break

    @case('Additional comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->additional_comment" />
    @break

    @case('ID')
        {{ $record->id }}
    @break

    @case('Date of creation')
        {{ $record->created_at->isoformat('DD MMM Y') }}
    @break

    @case('Update date')
        {{ $record->updated_at->isoformat('DD MMM Y') }}
    @break

@endswitch
