@switch($column['name'])
    @case('Edit')
        <x-tables.partials.td.edit :link="route('eld.order-products.edit', $record->id)" />
    @break

    @case('Manufacturer')
        {{ $record->order->manufacturer->name }}
    @break

    @case('Country')
        {{ $record->order->country->code }}
    @break

    @case('Brand Eng')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_en" />
    @break

    @case('Brand Rus')
        <x-tables.partials.td.max-lines-limited-text :text="$record->process->full_trademark_ru" />
    @break

    @case('MAH')
        {{ $record->process->MAH->name }}
    @break

    @case('Quantity')
        <x-tables.partials.td.formatted-price :price="$record->quantity" />
    @break

    @case('PO date')
        {{ $record->order->purchase_date?->isoformat('DD MMM Y') }}
    @break

    @case('PO №')
        {{ $record->order->name }}
    @break

    @case('Comments')
        <x-tables.partials.td.model-comments-link :record="$record" />
    @break

    @case('Last comment')
        <x-tables.partials.td.max-lines-limited-text :text="$record->lastComment?->plain_text" />
    @break

    @case('Packing list')
        @if ($record->packing_list_file)
            <a class="main-link" href="{{ $record->packing_list_asset_url }}" target="_blank">
                {{ $record->packing_list_file }}
            </a>
        @endif
    @break

    @case('COA')
        @if ($record->coa_file)
            <a class="main-link" href="{{ $record->coa_asset_url }}" target="_blank">
                {{ $record->coa_file }}
            </a>
        @endif
    @break

    @case('COO')
        @if ($record->coo_file)
            <a class="main-link" href="{{ $record->coo_asset_url }}" target="_blank">
                {{ $record->coo_file }}
            </a>
        @endif
    @break

    @case('Declaration for EUR1')
        @if ($record->declaration_for_europe_file)
            <a class="main-link" href="{{ $record->declaration_for_europe_asset_url }}" target="_blank">
                {{ $record->declaration_for_europe_file }}
            </a>
        @endif
    @break

    @case('Ready for shipment')
        {{ $record->readiness_for_shipment_date->isoformat('DD MMM Y') }}
    @break

    @case('Shipment from manufacturer start date')
        @if ($record->shipment_from_manufacturer_started)
            {{ $record->shipment_from_manufacturer_start_date->isoformat('DD MMM Y') }}
        @else
            <form action="{{ route('eld.order-products.start-shipment-from-manufacturer', $record->id) }}" method="POST">
                @csrf

                <x-misc.button
                    style="transparent"
                    class="button--arrowed-link button--margined-bottom"
                    icon="line_end_arrow_notch">
                    {{ __('Start shipment') }}
                </x-misc.button>
            </form>
        @endif
    @break

    @case('Shipment ID')
        {{ $record->shipment_id }}
    @break

    @case('Manufacturer country')
        {{ $record->order->manufacturer->country->code }}
    @break

    @case('Volume')
        {{ $record->shipment_volume }}
    @break

    @case('Packs')
        {{ $record->shipment_packs }}
    @break

    @case('Method of shipment')
        {{ $record->shipmentType?->name }}
    @break

    @case('Destination')
        {{ $record->shipmentDestination?->name }}
    @break

    @case('Transportation request')
        @if ($record->delivery_to_warehouse_requested)
            {{ $record->delivery_to_warehouse_request_date->isoformat('DD MMM Y') }}
        @elseif($record->can_request_delivery_to_warehouse)
            <form action="{{ route('eld.order-products.request-delivery-to-warehouse', $record->id) }}" method="POST">
                @csrf

                <x-misc.button
                    style="transparent"
                    class="button--arrowed-link button--margined-bottom"
                    icon="line_end_arrow_notch">
                    {{ __('Request sent') }}
                </x-misc.button>
            </form>
        @endif
    @break

    @case('Rate approved')
        {{ $record->delivery_to_warehouse_rate_approved_date?->isoformat('DD MMM Y') }}
    @break

    @case('Forwarder')
        {{ $record->delivery_to_warehouse_forwarder }}
    @break

    @case('Rate')
        {{ $record->delivery_to_warehouse_price }}
    @break

    @case('Currency')
        {{ $record->deliveryToWarehouseCurrency?->name }}
    @break

    @case('Loading confirmed')
        {{ $record->delivery_to_warehouse_loading_confirmed_date?->isoformat('DD MMM Y') }}
    @break

    @case('Shipment from manufacturer end date')
        @if ($record->shipment_from_manufacturer_ended)
            {{ $record->shipment_from_manufacturer_end_date?->isoformat('DD MMM Y') }}
        @elseif($record->can_end_shipment_from_manufacturer)
            <x-misc.button
                style="transparent"
                class="button--arrowed-link button--margined-bottom"
                icon="line_end_arrow_notch"
                data-click-action="end-shipment-from-manufacturer"
                data-record-id="{{ $record->id }}">
                {{ __('End shipment') }}
            </x-misc.button>
        @endif
    @break

    @case('Invoice')
        @if ($record->hasDeliveryToWarehouseInvoice())
            <a href="{{ route('eld.invoices.index', ['order_product_id' => $record->id]) }}" class="main-link">
                {{ $record->deliveryToWarehouseInvoice->title }}
            </a>
        @elseif ($record->canAddDeliveryToWarehouseInvoice())
            <a href="{{ route('eld.invoices.create', ['order_product_id' => $record->id]) }}" class="main-link">
                {{ __('Create invoice') }}
            </a>
        @endif
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
