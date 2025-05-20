<div class="form__row">
    <x-form.inputs.default-input
        class="specific-formatable-input"
        labelText="Dosage"
        inputName="{{ 'products[' . $inputsIndex . '][dosage]' }}" />

    <x-form.inputs.default-input
        class="specific-formatable-input"
        labelText="Pack"
        inputName="{{ 'products[' . $inputsIndex . '][pack]' }}" />

    <x-form.inputs.default-input
        labelText="MOQ"
        inputName="{{ 'products[' . $inputsIndex . '][moq]' }}"
        type="number"
        min="0" />

    <x-form.misc.remove-row-button />
</div>
