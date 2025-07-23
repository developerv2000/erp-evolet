<x-form-templates.edit-template class="cmd-invoices-edit-form" :action="route('cmd.invoices.update', $record->id)">
    <div class="form__block">
        <h2 class="main-title main-title--marginless">{{ __('Invoice') }}</h2>

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
                :label-text="__('New') . ' ' . __('PDF')"
                inputName="pdf"
                type="file"
                accept=".pdf" />
        </div>
    </div>

    <x-misc.invoices-toggleable-order-products-list
        :invoice="$record"
        :available-order-products="$availableOrderProducts"
        :disabled="$record->payment_type_id == App\Models\InvoicePaymentType::PREPAYMENT_ID" />
</x-form-templates.edit-template>
