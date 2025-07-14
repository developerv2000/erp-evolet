<x-form-templates.create-template class="plpd-order-products-create-form" :action="route('plpd.order-products.store')">
    <input type="hidden" name="order_id" value="{{ $order->id }}">

    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="TM Eng"
                inputName="temporary_process_id"
                :options="$readyForOrderProcesses"
                optionCaptionField="full_trademark_en_with_id"
                :is-required="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="MAH"
                inputName="process_id"
                :options="[]"
                optionCaptionField="mah_name_with_id"
                :is-required="true" />

            <x-form.inputs.default-input
                labelText="Quantity"
                inputName="quantity"
                type="number"
                min="0"
                :is-required="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Serialization type"
                inputName="serialization_type_id"
                :options="$serializationTypes"
                :is-required="true" />
        </div>
    </div>

    {{-- CMD part --}}
    @if ($order->is_sent_to_confirmation)
        <div class="form__block">
            <div class="form__row">
                <x-form.inputs.default-input
                    labelText="Price"
                    inputName="price"
                    type="number"
                    step="0.01"
                    min="0.00"
                    :isRequired="true" />

                <div class="form-group"></div>
                <div class="form-group"></div>
            </div>
        </div>
    @endif

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
