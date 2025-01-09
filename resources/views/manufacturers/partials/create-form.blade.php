<x-form-templates.create-template :action="route('manufacturers.store')">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Manufacturer"
                inputName="name"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Category"
                inputName="category_id"
                :options="$categories"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Product class"
                inputName="productClasses[]"
                :options="$productClasses"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Analyst"
                inputName="analyst_user_id"
                :initialValue="auth()->user()->id"
                :options="$analystUsers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="BDM"
                inputName="bdm_user_id"
                :options="$bdmUsers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.default-select
                labelText="Country"
                inputName="country_id"
                :options="$countriesOrderedByName"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Zones"
                inputName="zones[]"
                :options="$zones"
                :initialValues="$defaultSelectedZoneIDs"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.default-select
                labelText="Blacklist"
                inputName="blacklists[]"
                :options="$blacklists" />

            <x-form.selects.selectize.multiple-select.default-select
                labelText="Presence"
                inputName="presences[]"
                :taggable="true"
                :options="[]" />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.radio-buttons.default-radio-buttons
                class="radio-group--horizontal"
                labelText="Status"
                inputName="active"
                :options="$statusOptions"
                :initialValue="true"
                :isRequired="true" />

            <x-form.radio-buttons.default-radio-buttons
                class="radio-group--horizontal"
                labelText="Important"
                inputName="important"
                :options="$booleanOptions"
                :isRequired="true" />

            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.default-input
                labelText="Website"
                inputName="website" />

            <x-form.inputs.default-input
                labelText="Relationship"
                inputName="relationship" />

            <x-form.misc.attach-files-input />
        </div>

        <div class="form__row">
            <x-form.textareas.default-textarea
                labelText="About company"
                inputName="about" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-create />
    </div>
</x-form-templates.create-template>
