<div class="form__row">
    <x-form.inputs.default-input
        class="specific-formatable-input"
        labelText="Dosage"
        inputName="{{ 'products[' . $inputsIndex . '][pack]' }}" />

    <x-form.inputs.default-input
        class="specific-formatable-input"
        labelText="Pack"
        inputName="{{ 'products[' . $inputsIndex . '][dosage]' }}" />

    <div class="form-group">
        <x-form.misc.remove-row-button />
    </div>
</div>
