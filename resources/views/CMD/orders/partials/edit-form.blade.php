<x-form-templates.edit-template :action="route('cmd.orders.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="PO №"
                field="name"
                :model="$record"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Currency"
                field="currency_id"
                :model="$record"
                :options="$currencies"
                :initial-value="$record->currency_id ?: $defaultSelectedCurrencyID"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Price"
                field="price"
                :model="$record"
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
