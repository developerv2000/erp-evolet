<x-form-templates.edit-template :action="route('prd.invoices.update', $record->id)">
    <div class="form__block">
        <h2 class="main-title main-title--marginless">{{ __('Edit') }}</h2>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Payment request date"
                field="payment_request_date_by_financier"
                :model="$record"
                type="datetime-local"
                :initial-value="$record->formatForDateTimeInput('payment_request_date_by_financier')" />

            <x-form.inputs.record-field-input
                labelText="Invoice №"
                field="number"
                :model="$record" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Payment date"
                field="payment_date"
                :model="$record"
                type="datetime-local"
                :initial-value="$record->formatForDateTimeInput('payment_date')" />

            <x-form.inputs.default-input
                labelText="SWIFT"
                inputName="payment_confirmation_document"
                type="file"
                accept=".pdf" />
        </div>
    </div>

    <x-misc.invoice-products-list :order-products="$orderProducts" :record="$record" />
</x-form-templates.edit-template>
