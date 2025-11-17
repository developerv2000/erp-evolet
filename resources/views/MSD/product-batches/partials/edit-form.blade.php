<x-form-templates.edit-template class="msd-product-batches-edit-form" :action="route('msd.product-batches.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Start date of work"
                field="serialization_start_date"
                :model="$record"
                type="date"
                :initial-value="$record->serialization_start_date?->format('Y-m-d')" />

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
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Factual quantity"
                field="factual_quantity"
                :model="$record"
                type="number" />

            <x-form.inputs.record-field-input
                labelText="Defects quantity"
                field="defects_quantity"
                :model="$record"
                type="number" />

            <x-form.inputs.record-field-input
                labelText="End date of work"
                field="serialization_end_date"
                :model="$record"
                type="date"
                :initial-value="$record->serialization_end_date?->format('Y-m-d')" />

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
