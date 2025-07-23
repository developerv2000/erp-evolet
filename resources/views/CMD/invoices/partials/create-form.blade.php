<x-form-templates.create-template class="cmd-invoices-create-form" :action="route('cmd.invoices.store')">
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <input type="hidden" name="payment_type_id" value="{{ $paymentType->id }}">

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Payment type"
                inputName="readonly_payment_type"
                :initial-value="$paymentType->name"
                :isRequired="true"
                readonly />

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

    @include('CMD.invoices.partials.create-form-order-products-list')
</x-form-templates.create-template>
