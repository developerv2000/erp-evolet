<x-form-templates.create-template class="eld-invoices-create-form" :action="route('eld.invoices.store')">
    <input type="hidden" name="order_product_id" value="{{ $orderProduct->id }}">

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Receive date"
                inputName="receive_date"
                type="datetime-local"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Payment company"
                inputName="payment_company" />

            <x-form.inputs.default-input
                labelText="File"
                inputName="pdf"
                type="file"
                accept=".pdf"
                :isRequired="true" />
        </div>
    </div>
</x-form-templates.create-template>
