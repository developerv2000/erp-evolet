<x-form-templates.edit-template class="warehouse-product-batches-edit-form" :action="route('warehouse.product-batches.update', $record->id)">
    <div class="form__block">
        <h1 class="main-title main-title--marginless">
            {{ __('Available quantity') }}:
            <strong>{{ $record->product->remaining_quantity_for_batches + $record->quantity }}</strong>
        </h1>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Series"
                field="series"
                :model="$record"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Quantity"
                field="quantity"
                :model="$record"
                :isRequired="true"
                type="number"
                :max="$record->product->remaining_quantity_for_batches + $record->quantity" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Manufacturing date"
                field="manufacturing_date"
                :model="$record"
                type="date"
                :initial-value="$record->manufacturing_date?->format('Y-m-d')" />

            <x-form.inputs.record-field-input
                labelText="Expiration date"
                field="expiration_date"
                :model="$record"
                type="date"
                :initial-value="$record->expiration_date?->format('Y-m-d')" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
