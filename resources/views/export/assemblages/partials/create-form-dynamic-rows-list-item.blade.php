<div class="form__row">
    <x-form.selects.selectize.id-based-single-select.default-select
        labelText="Manufacturer"
        :input-name="'batches[' . $inputsIndex . '][manufacturer_id]'"
        :options="$manufacturers"
        :is-required="true" />

    <x-form.selects.selectize.id-based-single-select.default-select
        labelText="MAH"
        :input-name="'batches[' . $inputsIndex . '][marketing_authorization_holder_id]'"
        :options="$MAHs"
        :is-required="true" />

    <x-form.selects.selectize.id-based-single-select.default-select
        labelText="Batch"
        :input-name="'batches[' . $inputsIndex . '][batch_id]'"
        :options="[]"
        :is-required="true" />

    <x-form.inputs.default-input
        labelText="Quantity for assembly"
        :input-name="'batches[' . $inputsIndex . '][quantity_for_assembly]'"
        type="number"
        min="0"
        :is-required="true" />

    <x-form.inputs.default-input
        labelText="Comment"
        :input-name="'batches[' . $inputsIndex . '][comment]'" />

    <x-form.misc.remove-row-button />
</div>
