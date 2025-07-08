<x-form-templates.edit-template :action="route('prd.invoices.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Payment type"
                inputName="readonly_payment_type"
                :initial-value="$record->paymentType->name"
                readonly />

            <x-form.inputs.record-field-input
                labelText="Receive date"
                field="receive_date"
                :model="$record"
                type="datetime-local"
                :initial-value="$record->formatForDateTimeInput('receive_date')"
                :isRequired="true" />

            <x-form.inputs.default-input
                :label-text="__('New') . ' ' . __('File')"
                inputName="pdf"
                type="file"
                accept=".pdf" />
        </div>
    </div>
</x-form-templates.edit-template>
