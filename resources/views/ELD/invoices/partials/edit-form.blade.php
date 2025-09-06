<x-form-templates.edit-template class="eld-invoices-edit-form" :action="route('eld.invoices.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Payment company"
                field="payment_company"
                :model="$record" />

            <x-form.inputs.record-field-input
                labelText="Receive date"
                field="receive_date"
                :model="$record"
                type="datetime-local"
                :initial-value="$record->formatForDateTimeInput('receive_date')"
                :isRequired="true" />

            <x-form.inputs.default-input
                :label-text="__('New') . ' ' . __('PDF')"
                inputName="pdf"
                type="file"
                accept=".pdf" />
        </div>
    </div>
</x-form-templates.edit-template>
