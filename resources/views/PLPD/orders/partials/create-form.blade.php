<x-form-templates.create-template class="plpd-orders-create-form" :action="route('plpd.orders.store')">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Manufacturer"
                inputName="manufacturer_id"
                :options="$manufacturers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Country"
                inputName="country_id"
                :options="$countriesOrderedByProcessesCount"
                optionCaptionField="code"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Receive date"
                inputName="receive_date"
                type="date"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Brand Eng"
                inputName="process_id"
                :options="[]"
                optionCaptionField="full_trademark_en"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="MAH"
                inputName="marketing_authorization_holder_id"
                :options="[]"
                :isRequired="true" />

            <x-form.inputs.default-input
                labelText="Quantity"
                inputName="quantity"
                type="number"
                min="0"
                :isRequired="true" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
