<div class="form__row">
    <x-form.inputs.default-input
        labelText="Series"
        inputName="{{ 'batches[' . $inputsIndex . '][series]' }}"
        :isRequired="true" />

    <x-form.inputs.default-input
        labelText="Quantity"
        inputName="{{ 'batches[' . $inputsIndex . '][quantity]' }}"
        :isRequired="true"
        type="number" />

    <x-form.inputs.default-input
        labelText="Manufacturing date"
        inputName="{{ 'batches[' . $inputsIndex . '][manufacturing_date]' }}"
        type="date" />

    <x-form.inputs.default-input
        labelText="Expiration date"
        inputName="{{ 'batches[' . $inputsIndex . '][expiration_date]' }}"
        type="date" />

    <x-form.misc.remove-row-button />
</div>
