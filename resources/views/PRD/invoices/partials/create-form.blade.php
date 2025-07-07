<x-form-templates.create-template class="cmd-invoices-create-form" :action="route('cmd.invoices.store')">
    <input type="hidden" name="order_id" value="{{ $order->id }}">

    <div class="form__block">
        <div class="form__row">
            @if ($order->shouldHaveInvoiceOfFinalPaymentType())
                <x-form.inputs.default-input
                    labelText="Payment type"
                    inputName="readonly_payment_type"
                    :initial-value="$finalPaymentTypeName"
                    :isRequired="true"
                    readonly />
            @else
                <x-form.selects.selectize.id-based-single-select.default-select
                    labelText="Payment type"
                    inputName="payment_type_id"
                    :options="$paymentTypes"
                    :is-required="true" />
            @endif

            <x-form.inputs.default-input
                labelText="Receive date"
                inputName="receive_date"
                type="datetime-local"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="File"
                inputName="pdf"
                type="file"
                accept=".pdf"
                :isRequired="true" />
        </div>
    </div>
</x-form-templates.create-template>
