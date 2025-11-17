<x-form-templates.edit-template class="warehouse-products-edit-form" :action="route('warehouse.products.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Original invoice"
                field="warehouse_invoice_number"
                :model="$record" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Seller"
                field="payer_company_id"
                :model="$record"
                :options="$payerCompanies"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Customs code"
                field="customs_code"
                :model="$record" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Factual quantity"
                field="factual_quantity"
                :model="$record"
                type="number"
                :is-required="true" />

            <x-form.inputs.record-field-input
                labelText="Number of packages in box (full)"
                field="number_of_packages_in_full_box"
                :model="$record"
                type="number" />

            <x-form.inputs.record-field-input
                labelText="Number of boxes (full)"
                field="number_of_full_boxes"
                :model="$record"
                type="number" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Defects quantity"
                field="defects_quantity"
                :model="$record"
                type="number" />

            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
