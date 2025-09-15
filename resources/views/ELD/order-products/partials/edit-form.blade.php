<x-form-templates.edit-template class="eld-order-products-edit-form" :action="route('eld.order-products.update', $record->id)">
    @if ($record->shipment_from_manufacturer_started)
        <div class="form__block">
            <h2 class="main-title main-title--marginless">{{ __('Start of the shipping process from the factory') }}</h2>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Shipment ID"
                    field="shipment_from_manufacturer_id"
                    :model="$record" />

                <x-form.inputs.record-field-input
                    labelText="Volume"
                    field="shipment_from_manufacturer_volume"
                    type="number"
                    :model="$record"
                    min="1.00"
                    step="0.01" />

                <x-form.inputs.record-field-input
                    labelText="Packs"
                    field="shipment_from_manufacturer_packs"
                    :model="$record" />
            </div>

            <div class="form__row">
                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Method of shipment"
                    field="shipment_from_manufacturer_type_id"
                    :model="$record"
                    :options="$shipmentTypes"
                    :isRequired="true" />

                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Destination"
                    field="shipment_from_manufacturer_destination_id"
                    :model="$record"
                    :options="$shipmentDestinations"
                    :isRequired="true" />

                <div class="form-group"></div>
            </div>
        </div>
    @endif

    @if ($record->delivery_to_warehouse_requested)
        <div class="form__block">
            <h2 class="main-title main-title--marginless">{{ __('Transportation request') }}</h2>

            <div class="form__row">
                <x-form.inputs.record-field-input
                    labelText="Rate approved"
                    field="delivery_to_warehouse_rate_approved_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->delivery_to_warehouse_rate_approved_date?->format('Y-m-d')" />

                <x-form.inputs.record-field-input
                    labelText="Forwarder"
                    field="delivery_to_warehouse_forwarder"
                    :model="$record" />

                <x-form.inputs.record-field-input
                    labelText="Rate"
                    field="delivery_to_warehouse_price"
                    type="number"
                    :model="$record"
                    min="1" />
            </div>

            <div class="form__row">
                <x-form.selects.selectize.id-based-single-select.record-field-select
                    labelText="Currency"
                    field="delivery_to_warehouse_currency_id"
                    :model="$record"
                    :options="$currencies" />

                <x-form.inputs.record-field-input
                    labelText="Loading confirmed"
                    field="delivery_to_warehouse_loading_confirmed_date"
                    :model="$record"
                    type="date"
                    :initial-value="$record->delivery_to_warehouse_loading_confirmed_date?->format('Y-m-d')" />

                <div class="form-group"></div>
            </div>
        </div>
    @endif

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
