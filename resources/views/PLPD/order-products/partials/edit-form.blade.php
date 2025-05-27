<x-form-templates.edit-template :action="route('mad.manufacturers.update', $record->id)">
    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Manufacturer"
                field="name"
                :model="$record"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Category"
                field="category_id"
                :model="$record"
                :options="$categories"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Product class"
                inputName="productClasses[]"
                :model="$record"
                :options="$productClasses"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Analyst"
                field="analyst_user_id"
                :model="$record"
                :options="$analystUsers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="BDM"
                field="bdm_user_id"
                :model="$record"
                :options="$bdmUsers"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-single-select.record-field-select
                labelText="Country"
                field="country_id"
                :model="$record"
                :options="$countriesOrderedByName"
                :isRequired="true" />
        </div>

        <div class="form__row">
            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Zones"
                inputName="zones[]"
                :model="$record"
                :options="$zones"
                :isRequired="true" />

            <x-form.selects.selectize.id-based-multiple-select.record-relation-select
                labelText="Blacklist"
                inputName="blacklists[]"
                :model="$record"
                :options="$blacklists" />

            <x-form.selects.selectize.multiple-select.record-field-multi-select
                labelText="Presence"
                field="presence_names_array"
                inputName="presences[]"
                :model="$record"
                :taggable="true"
                :options="$record->presence_names_array" />
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.radio-buttons.record-field-radio-buttons
                class="radio-group--horizontal"
                labelText="Status"
                field="active"
                :model="$record"
                :options="$statusOptions"
                :isRequired="true" />

            <x-form.radio-buttons.record-field-radio-buttons
                class="radio-group--horizontal"
                labelText="Important"
                field="important"
                :model="$record"
                :options="$booleanOptions"
                :isRequired="true" />

            <div class="form-group"></div>
        </div>
    </div>

    <div class="form__block">
        <div class="form__row">
            <x-form.inputs.record-field-input
                labelText="Website"
                field="website"
                :model="$record" />

            <x-form.inputs.record-field-input
                labelText="Relationship"
                field="relationship"
                :model="$record" />

            <x-form.misc.attach-files-input />
        </div>

        <div class="form__row">
            <x-form.textareas.record-field-textarea
                labelText="About company"
                field="about"
                :model="$record" />
        </div>
    </div>

    <div class="form__block">
        <x-form.misc.comment-inputs-on-model-edit :record="$record" />
    </div>
</x-form-templates.edit-template>
