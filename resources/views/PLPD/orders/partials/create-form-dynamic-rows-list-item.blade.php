<div class="form__row">
    <x-form.selects.selectize.id-based-single-select.default-select
        labelText="TM Eng"
        :input-name="'products[' . $inputsIndex . '][temporary_process_id]'"
        :options="$readyForOrderProcesses"
        optionCaptionField="full_trademark_en_with_id"
        :is-required="true" />

    <x-form.selects.selectize.id-based-single-select.default-select
        labelText="MAH"
        :input-name="'products[' . $inputsIndex . '][process_id]'"
        :options="[]"
        optionCaptionField="mah_name_with_id"
        :is-required="true" />

    <x-form.inputs.default-input
        labelText="Quantity"
        :input-name="'products[' . $inputsIndex . '][quantity]'"
        type="number"
        min="0"
        :is-required="true" />

    <x-form.misc.remove-row-button />
</div>
