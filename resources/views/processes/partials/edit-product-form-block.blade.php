<div class="form__block">
    <h3 class="main-title main-title--marginless">{{ __('IVP') }}</h3>

    <div class="form__row">
        <x-form.selects.selectize.id-based-single-select.record-field-select
            labelText="Form"
            field="form_id"
            :model="$product"
            :options="$productForms"
            :isRequired="true" />

        <x-form.inputs.record-field-input
            class="specific-formatable-input"
            labelText="Dosage"
            field="dosage"
            :model="$product"
            isRequired="{{ $product->dosage ? true : false }}" />

        <x-form.inputs.record-field-input
            class="specific-formatable-input"
            labelText="Pack"
            field="pack"
            :model="$product"
            isRequired="{{ $product->pack ? true : false }}" />
    </div>

    <div class="form__row">
        <x-form.selects.selectize.id-based-single-select.record-field-select
            labelText="Shelf life"
            field="shelf_life_id"
            :model="$product"
            :options="$shelfLifes"
            :isRequired="true" />

        <x-form.selects.selectize.id-based-single-select.record-field-select
            labelText="Product class"
            field="class_id"
            :model="$product"
            :options="$productClasses"
            :isRequired="true" />

        <div class="form-group"></div>
    </div>
</div>
