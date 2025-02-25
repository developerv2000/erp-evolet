<div class="form__row">
    <x-form.inputs.default-input
        class="specific-formatable-input"
        labelText="Dosage"
        inputName="{{ 'products[' . $inputsIndex . '][dosage]' }}" />

    <x-form.inputs.default-input
        class="specific-formatable-input"
        labelText="Pack"
        inputName="{{ 'products[' . $inputsIndex . '][pack]' }}" />

    <div class="form-group">
        <x-form.misc.remove-row-button />
    </div>
</div>
