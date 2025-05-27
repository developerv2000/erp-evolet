<x-form-templates.edit-template :action="route('plpd.order-products.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Brand Eng"
                inputName="brand_eng"
                :initial-value="$record->process->full_trademark_en"
                readonly />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="MAH"
                field="marketing_authorization_holder_id"
                :model="$record"
                :options="$MAHs"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Quantity"
                field="quantity"
                :model="$record"
                type="number"
                min="0" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
