<x-form-templates.edit-template class="cmd-order-products-edit-form" :action="route('cmd.order-products.update', $record->id)">
    <div class="form__block">
        <h2 class="main-title main-title--marginless">{{ __('Product') }}</h2>

        <div class="form__row">
            <x-form.inputs.default-input
                labelText="TM Eng"
                inputName="readonly_trademark"
                :initial-value="$record->process->full_trademark_en_with_id"
                readonly />

            <x-form.inputs.default-input
                labelText="MAH"
                inputName="readonly_mah"
                :initial-value="$record->process->MAH->name"
                readonly />

            <x-form.inputs.default-input
                labelText="Quantity"
                inputName="readonly_quantity"
                :initial-value="$record->quantity"
                readonly />

            <x-form.inputs.record-field-input
                labelText="Price"
                field="price"
                :model="$record"
                :initial-value="$record->price ?: $record->process->agreed_price"
                type="number"
                step="0.01"
                min="0.00"
                :isRequired="true" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
