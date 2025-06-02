<x-form-templates.edit-template :action="route('cmd.orders.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Manufacturer"
                field="manufacturer_id"
                :model="$record"
                :options="$manufacturers"
                :initial-value="$record->process->product->manufacturer_id"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Country"
                field="country_id"
                :model="$record"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code"
                :initial-value="$record->process->country_id"
                :isRequired="true" />

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
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Brand Eng"
                field="process_id"
                :model="$record"
                :options="$readyForOrderProcesses"
                optionCaptionField="full_trademark_en"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="MAH"
                field="marketing_authorization_holder_id"
                :model="$record"
                :options="$MAHs"
                :initial-value="$record->process->marketing_authorization_holder_id"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Quantity"
                field="quantity"
                :model="$record"
                type="number"
                min="0"
                :isRequired="true" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
