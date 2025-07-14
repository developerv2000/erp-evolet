<x-form-templates.edit-template :action="route('msd.order-products.serialized-by-manufacturer.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Serialization codes request date"
                field="serialization_codes_request_date"
                :model="$record"
                type="datetime-local"
                :initial-value="$record->formatForDateTimeInput('serialization_codes_request_date')" />

            <x-form.inputs.record-field-input
                labelText="Serialization codes sent"
                field="serialization_codes_sent_date"
                :model="$record"
                type="datetime-local"
                :initial-value="$record->formatForDateTimeInput('serialization_codes_sent_date')" />

            <x-form.inputs.record-field-input
                labelText="Serialization report received"
                field="serialization_report_recieved_date"
                :model="$record"
                type="datetime-local"
                :initial-value="$record->formatForDateTimeInput('serialization_report_recieved_date')" />

            <x-form.inputs.record-field-input
                labelText="Report sent to hub"
                field="report_sent_to_hub_date"
                :model="$record"
                type="datetime-local"
                :initial-value="$record->formatForDateTimeInput('report_sent_to_hub_date')" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
