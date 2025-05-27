<div class="form__row">
    <x-form.selects.selectize.id-based-single-select.default-select
        labelText="Brand Eng"
        inputName="{{ 'products[' . $inputsIndex . '][process_id]' }}"
        :options="$readyForOrderProcesses"
        optionCaptionField="full_trademark_en"
        :isRequired="true" />

    <x-form.selects.selectize.id-based-single-select.default-select
        labelText="MAH"
        inputName="{{ 'products[' . $inputsIndex . '][marketing_authorization_holder_id]' }}"
        :options="$MAHs"
        :isRequired="true" />

    <x-form.inputs.default-input
        labelText="Quantity"
        inputName="{{ 'products[' . $inputsIndex . '][quantity]' }}"
        type="number"
        min="0" />

    <x-form.misc.remove-row-button />
</div>
