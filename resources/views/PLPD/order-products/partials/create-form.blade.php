<x-form-templates.create-template :action="route('plpd.order-products.store')">
    <input type="hidden" name="order_id" value="{{ $order->id }}">

    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Brand Eng"
                inputName="process_id"
                :options="$readyForOrderProcesses"
                optionCaptionField="full_trademark_en"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="MAH"
                inputName="marketing_authorization_holder_id"
                :options="$MAHs"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Quantity"
                inputName="quantity"
                type="number"
                min="0" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
