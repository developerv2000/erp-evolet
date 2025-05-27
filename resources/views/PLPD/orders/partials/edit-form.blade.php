<x-form-templates.edit-template :action="route('plpd.orders.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Manufacturer"
                inputName="manufacturer_name"
                :initial-value="$record->manufacturer->name"
                readonly />

            <x-form.inputs.default-input
                labelText="Country"
                inputName="country_name"
                :initial-value="$record->country->code"
                readonly />

            <x-form.inputs.record-field-input
                labelText="Receive date"
                field="receive_date"
                :model="$record"
                type="date"
                :initial-value="$record->receive_date->format('Y-m-d')"
                :isRequired="true" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
