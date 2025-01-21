<x-form-templates.edit-template :action="route('products.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Manufacturer"
                field="manufacturer_id"
                :model="$record"
                :options="$manufacturers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Generic"
                field="inn_id"
                :model="$record"
                :options="$inns"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Form"
                field="form_id"
                :model="$record"
                :options="$productForms"
                :isRequired="true" />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                class="specific-formatable-input"
                labelText="Dosage"
                field="dosage"
                :model="$record" />

            <x-form.inputs.record-field-input
                class="specific-formatable-input"
                labelText="Pack"
                field="pack"
                :model="$record" />

            <x-form.inputs.record-field-input
                labelText="Brand"
                field="brand"
                :model="$record" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Product class"
                field="class_id"
                :model="$record"
                :options="$productClasses"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="MOQ"
                field="moq"
                :model="$record"
                type="number"
                min="1" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Shelf life"
                field="shelf_life_id"
                :model="$record"
                :options="$shelfLifes"
                :isRequired="true" />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Dossier"
                field="dossier"
                :model="$record" />

            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Zones"
                inputName="zones[]"
                :model="$record"
                :options="$zones"
                :isRequired="true" />

            <x-form.inputs.record-field-input
                labelText="Bioequivalence"
                field="bioequivalence"
                :model="$record" />
        </div>

        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Down payment"
                field="down_payment"
                :model="$record" />

            <x-form.inputs.record-field-input
                labelText="Validity period"
                field="validity_period"
                :model="$record" />

            <x-form.misc.attach-files-input />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.radio-buttons.record-field-radio-buttons
                class="radio-group--horizontal"
                labelText="Registered in EU"
                field="registered_in_eu"
                :model="$record"
                :options="$booleanOptions"
                :isRequired="true" />

            <x-form.radio-buttons.record-field-radio-buttons
                class="radio-group--horizontal"
                labelText="Sold in EU"
                field="sold_in_eu"
                :model="$record"
                :options="$booleanOptions"
                :isRequired="true" />

            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
